<?php

class SaleReturnController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('SaleReturn');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->SaleReturn->search($this->request->data['SaleReturn']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->SaleReturn->paginationSettings();
        $this->set('sale_returns', $this->Paginator->paginate());
        
        $this->set('carriers', $this->SaleReturn->Carrier->fullList());
        $this->set('returnStatuses', $this->SaleReturn->ReturnStatus->fullList());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $sale_return = array();
        if(!$id){
            // Assume add
            if(!isset($this->request->params['named']['sale'])){
                return false;
            }

            $saleId = $this->request->params['named']['sale'];
            $sale = $this->SaleReturn->Sale->checkIt($saleId);
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $sale_return = $this->SaleReturn->checkIt($id);
            $this->set('sale_return', $sale_return);
            $sale = array(
                'Sale' => $sale_return['Sale'][0]['Sale'],
                'Listing' => $sale_return['Sale'][0]['Sale']['Listing']
            );

            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->SaleReturn->title,
                $this->SaleReturn->saveIt($this->request->data),
                array(
                    'controller' => 'sale_return',
                    'action' => 'index'
                )
            );

        }

        if(!$this->request->data){
            $this->request->data = $sale_return;
        }

        $this->set('operation', $operation);
        $this->set('sale', $sale);
        $this->set('carriers', $this->SaleReturn->Carrier->fullList());
        $this->set('returnStatuses', $this->SaleReturn->ReturnStatus->fullList());
    }

    public function del($id) {
        $this->SaleReturn->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->SaleReturn->title,
                $this->SaleReturn->deleteIt($id),
                array(
                    'controller' => 'sale_return',
                    'action' => 'index'
                )
            );
        }
    }
}