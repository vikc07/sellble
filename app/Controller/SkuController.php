<?php

class SkuController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Sku');

    public function index(){
        if(isset($this->request->params['named']['search_reset'])){
            $this->Session->delete('SkuSearch', '');
        }

        if($this->request->is('post', 'put') or $this->Session->read('SkuSearch')){
            if($this->request->data){
                $this->Session->write('SkuSearch', $this->request->data[$this->Sku->alias]);
            }

            $this->Sku->search($this->Session->read('SkuSearch'));

            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }

        $this->Paginator->settings = $this->Sku->paginationSettings();
        $this->set('skus', $this->Paginator->paginate());
        $this->set('categories', $this->Sku->Item->Category->fullList());
    }

    public function barcode($id = null){
        $sku = $this->Sku->checkIt($id);

        $this->Barcode->generate($sku['Sku']['idFormatted']);
        $this->set('skuIdFormatted', $sku['Sku']['idFormatted']);
        $this->layout = 'empty';
    }
}