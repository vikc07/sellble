<?php

class ExcludedSkuController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('ExcludedSku');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->ExcludedSku->search($this->request->data['ExcludedSku']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->ExcludedSku->paginationSettings();
        $this->set('excluded_skus', $this->Paginator->paginate());
        $this->set('exclusion_years', $this->ExcludedSku->exclusionYears());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $exclude = '';
        if(!$id){
            // Assume add and allow
            if(!isset($this->request->params['named']['sku'])){
                return false;
            }

            $skuId = $this->request->params['named']['sku'];
            $sku = $this->ExcludedSku->Sku->checkIt($skuId);

            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $excluded_sku = $this->ExcludedSku->checkIt($id);
            $this->set('excluded_sku', $excluded_sku);
            $sku = array(
                'Sku' => $excluded_sku['Sku'],
                'Item' => $excluded_sku['Sku']['Item']
            );

            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }

            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->ExcludedSku->title,
                $this->ExcludedSku->saveIt($this->request->data),
                array(
                    'controller' => 'sku',
                    'action' => 'index'
                )
            );

        }

        if(!$this->request->data){
            if(isset($excluded_sku)){
                $this->request->data = $excluded_sku;
            }
        }

        $this->set('operation', $operation);
        $this->set('excludeReasons', $this->ExcludedSku->ExcludeReason->fullList());
        $this->set('sku', $sku);
    }

    public function del($id) {
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->ExcludedSku->title,
                $this->ExcludedSku->deleteIt($id),
                array(
                    'controller' => 'excluded_sku',
                    'action' => 'index'
                )
            );
        }
    }
}