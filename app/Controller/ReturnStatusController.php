<?php

class ReturnStatusController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('ReturnStatus');

    public function index(){
        $this->Paginator->settings = $this->ReturnStatus->paginationSettings();
        $this->set('return_statuses', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $return_status = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $return_status = $this->ReturnStatus->checkIt($id);
            $this->set('return_status', $return_status);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->ReturnStatus->title,
                $this->ReturnStatus->saveIt($this->request->data),
                array(
                    'controller' => 'return_status',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $return_status;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->ReturnStatus->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->ReturnStatus->title,
                $this->ReturnStatus->deleteIt($id),
                array(
                    'controller' => 'return_status',
                    'action' => 'index'
                )
            );
        }
    }
}

?>