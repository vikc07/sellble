<?php
class BrandController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Brand');

    public function index(){
        $this->Paginator->settings = $this->Brand->paginationSettings();
        $this->set('brands', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $brand = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $brand = $this->Brand->checkIt($id);
            $this->set('brand', $brand);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Brand->title,
                $this->Brand->saveIt($this->request->data),
                array(
                    'controller' => 'brand',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $brand;
        }

        $this->set('manufacturers', $this->Brand->mfrList());
        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->Brand->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Brand->title,
                $this->Brand->deleteIt($id),
                array(
                    'controller' => 'brand',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $brand = $this->Brand->checkIt($id);
        $this->set('brand', $brand);
        if($this->request->is(array('post', 'put'))){
            $this->Brand->id = $id;
            if($this->request->data){
                $args = $this->Brand->uploadArgs(
                    $this->request->data['Brand']['logo']['name'],
                    $this->request->data['Brand']['logo']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Brand' => array(
                            'id' => $id,
                            'logo' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Brand->title,
                        $this->Brand->saveIt($data),
                        array(
                            'controller' => 'brand',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $brand;
        }
    }

    function removeLogo($id){
        $brand = $this->Brand->checkIt($id);
        if($brand['Brand']['logo']){
            $args = array(
                'fileName' => basename($brand['Brand']['logo']),
                'folderName' => 'logos' . Configure::read('directorySeparator') . 'brand'
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Brand' => array(
                        'logo' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Brand->title,
                    $this->Brand->saveIt($data, false),
                    array(
                        'controller' => 'brand',
                        'action' => 'index'
                    )
                );
            }
            else{
                $this->Session->setFlash(__('There was an error deleting Logo. Try again.'), 'myFlashError');
            }
        }
    }
}