<?php

class SpecController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Spec');

    public function index(){
        $this->Paginator->settings = $this->Spec->paginationSettings();
        $this->set('specs', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $spec = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $spec = $this->Spec->checkIt($id);
            $this->set('spec', $spec);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Spec->title,
                $this->Spec->saveIt($this->request->data),
                array(
                    'controller' => 'spec',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $spec;
        }

        $this->set('operation', $operation);
        $this->set('specSubgroups', $this->Spec->SpecSubgroup->fullList());
    }

    public function del($id) {
        $this->Spec->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Spec->title,
                $this->Spec->deleteIt($id),
                array(
                    'controller' => 'spec',
                    'action' => 'index'
                )
            );
        }
    }
}

?>