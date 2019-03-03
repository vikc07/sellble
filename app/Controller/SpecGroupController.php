<?php

class SpecGroupController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('SpecGroup');

    public function index(){
        $this->Paginator->settings = $this->SpecGroup->paginationSettings();
        $this->set('spec_groups', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $spec_group = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $spec_group = $this->SpecGroup->checkIt($id);
            $this->set('spec_group', $spec_group);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->SpecGroup->title,
                $this->SpecGroup->saveIt($this->request->data),
                array(
                    'controller' => 'spec_group',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $spec_group;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->SpecGroup->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->SpecGroup->title,
                $this->SpecGroup->deleteIt($id),
                array(
                    'controller' => 'spec_group',
                    'action' => 'index'
                )
            );
        }
    }
}

?>