<?php

class PurchaseStatusController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('PurchaseStatus');

    public function index(){
        $this->Paginator->settings = $this->PurchaseStatus->paginationSettings();
        $this->set('purchase_statuses', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $purchase_status = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $purchase_status = $this->PurchaseStatus->checkIt($id);
            $this->set('purchase_status', $purchase_status);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->PurchaseStatus->title,
                $this->PurchaseStatus->saveIt($this->request->data),
                array(
                    'controller' => 'purchase_status',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $purchase_status;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->PurchaseStatus->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->PurchaseStatus->title,
                $this->PurchaseStatus->deleteIt($id),
                array(
                    'controller' => 'purchase_status',
                    'action' => 'index'
                )
            );
        }
    }
}

?>