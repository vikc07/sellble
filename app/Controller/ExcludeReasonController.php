<?php

class ExcludeReasonController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('ExcludeReason');

    public function index(){
        $this->Paginator->settings = $this->ExcludeReason->paginationSettings();
        $this->set('exclude_reasons', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $exclude_reason = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $exclude_reason = $this->ExcludeReason->checkIt($id);
            $this->set('exclude_reason', $exclude_reason);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->ExcludeReason->title,
                $this->ExcludeReason->saveIt($this->request->data),
                array(
                    'controller' => 'exclude_reason',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $exclude_reason;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->ExcludeReason->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->ExcludeReason->title,
                $this->ExcludeReason->deleteIt($id),
                array(
                    'controller' => 'exclude_reason',
                    'action' => 'index'
                )
            );
        }
    }
}

?>