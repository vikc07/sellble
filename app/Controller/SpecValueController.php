<?php

class SpecValueController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('SpecValue');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->SpecValue->search($this->request->data['SpecValue']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->SpecValue->paginationSettings();
        $this->set('spec_values', $this->Paginator->paginate());
        $this->set('specs', $this->SpecValue->Spec->fullList());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $spec_value = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $spec_value = $this->SpecValue->checkIt($id);
            $this->set('spec_value', $spec_value);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->SpecValue->title,
                $this->SpecValue->saveIt($this->request->data),
                array(
                    'controller' => 'spec_value',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $spec_value;
        }

        $this->set('operation', $operation);
        $this->set('specs', $this->SpecValue->Spec->fullList());
    }

    public function del($id) {
        $this->SpecValue->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->SpecValue->title,
                $this->SpecValue->deleteIt($id),
                array(
                    'controller' => 'spec_value',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $spec_value = $this->SpecValue->checkIt($id);
        $this->set('spec_value', $spec_value);
        if($this->request->is(array('post', 'put'))){
            $this->SpecValue->id = $id;
            if($this->request->data){
                $args = $this->SpecValue->uploadArgs(
                    $this->request->data['SpecValue']['logo']['name'],
                    $this->request->data['SpecValue']['logo']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'SpecValue' => array(
                            'id' => $id,
                            'logo' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->SpecValue->title,
                        $this->SpecValue->saveIt($data),
                        array(
                            'controller' => 'spec_value',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $spec_value;
        }
    }

    function removeLogo($id){
        $spec_value = $this->SpecValue->checkIt($id);
        if($spec_value['SpecValue']['logo']){
            $args = array(
                'fileName' => basename($spec_value['SpecValue']['logo']),
                'folderName' => 'logos' . Configure::read('directorySeparator') . 'spec_values'
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'SpecValue' => array(
                        'logo' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->SpecValue->title,
                    $this->SpecValue->saveIt($data, false),
                    array(
                        'controller' => 'spec_value',
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