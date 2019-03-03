<?php

class ItemController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Item');

    public function index(){
        if(isset($this->request->params['named']['search_reset'])){
            $this->Session->delete('ItemSearch', '');
        }

        if($this->request->is('post', 'put') or $this->Session->read('ItemSearch')){
            if($this->request->data){
                $this->Session->write('ItemSearch', $this->request->data[$this->Item->alias]);
            }

            $this->Item->search($this->Session->read('ItemSearch'));

            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Item->paginationSettings();
        $this->set('items', $this->Paginator->paginate());
        $this->set('categories', $this->Item->Category->fullList());
        $this->set('brands', $this->Item->Brand->fullList());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $item = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $item = $this->Item->checkIt($id);
            $this->set('item', $item);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }
        if($goodToSave){
            debug($this->referer());
            debug('yes');
            $this->saveSetFlash(
                $this->Item->title,
                $this->Item->saveIt($this->request->data),
                array(
                    'controller' => 'item',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $item;
        }

        $this->set('operation', $operation);
        $this->set('items', $item);
        $this->set('categories', $this->Item->Category->fullList());
        $this->set('brands', $this->Item->Brand->fullList());
    }

    public function del($id) {
        $this->Item->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Item->title,
                $this->Item->deleteIt($id),
                array(
                    'controller' => 'item',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $item = $this->Item->checkIt($id);
        $this->set('item', $item);

        if($this->request->is(array('post', 'put'))){
            $this->Item->id = $id;
            if($this->request->data){
                $data = array(
                    'Item' => array(
                        'id' => $id
                    )
                );
                foreach($this->request->data['ItemPhotos']['file_name'] as $photo){
                    if(!empty($photo['name'])){
                        $this->Item->idFormatted = $item['Item']['idFormatted'];

                        $args = $this->Item->uploadArgs(
                            $photo['name'],
                            $photo['tmp_name']
                        );

                        if($this->FileOp->uploadFile($args)){
                            $data['ItemPhoto'][] = array(
                                'item_id' => $id,
                                'file_name' => $args['destFileName']
                            );
                        }
                    }
                }

                $this->saveSetFlash(
                    $this->Item->title,
                    $this->Item->saveAll($data),
                    array(
                        'controller' => 'item',
                        'action' => 'index'
                    )
                );
            }
        }

        if (!$this->request->data) {
            $this->request->data = $item;
        }

        $args = array(
            'conditions' => array(
                'item_id' => $id
            )
        );
        $this->set('item_photos', $this->Item->ItemPhoto->find('all', $args));
    }

    function deletePhoto($id){
        $item = $this->Item->checkIt($id);
        if(isset($this->request['named']['photo'])){
            $itemPhoto = $this->Item->ItemPhoto->findById($this->request['named']['photo']);
            if(!$item){
                throw new NotFoundException(__('Invalid Item Photo'));
            }

            $args = array(
                'fileName' => $itemPhoto['ItemPhoto']['file_name'],
                'folderName' => 'item' . DIRECTORY_SEPARATOR . $item['Item']['idFormatted']
            );

            if($this->FileOp->deleteFile($args)){
                if($this->Item->ItemPhoto->deleteIt($this->request['named']['photo'])){
                    $this->Session->setFlash(__('Photo has been deleted'), 'myFlashSuccess');
                }
                else{
                    $this->Session->setFlash(__('There was an error deleting photo. Try again.'), 'myFlashError');
                }
            }
            else{
                $this->Session->setFlash(__('There was an error deleting photo. Try again.'), 'myFlashError');
            }
        }

        return $this->redirect(array('action' => 'upload', $id));
    }

    function specs($id = null){
        $item = $this->Item->checkIt($id);
        $specs = $this->Item->ItemSpec->specs($id);

        if($this->request->is(array('post', 'put'))){
            //debug($this->request->data);
            //$this->Item->ItemSpec->saveMany($this->request->data['ItemSpec']);
            $this->saveSetFlash(
                $this->Item->title,
                $this->Item->ItemSpec->saveMany($this->request->data['ItemSpec']),
                array(
                    'controller' => 'item',
                    'action' => 'index'
                )
            );
        }

        // Now $specs could be empty or non empty
        // Regardless we want to display all possible specs
        $allSpecs = $this->Item->ItemSpec->Spec->forGroup($this->Item->findSpecGroupId($id));
        $this->set('allSpecs', $allSpecs);
        $this->set('usedSpecs', $specs);
        $this->set('item', $item);
    }
}