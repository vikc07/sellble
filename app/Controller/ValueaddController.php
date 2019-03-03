<?php

class ValueaddController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Valueadd');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Valueadd->search($this->request->data['Valueadd']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Valueadd->paginationSettings();
        $this->set('valueadds', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $exclude = '';
        if(!$id){
            // Assume add
            if(!isset($this->request->params['named']['sku'])){
                return false;
            }

            $skuId = $this->request->params['named']['sku'];
            $sku = $this->Valueadd->Sku->checkIt($skuId);

            $itemId = $sku['Sku']['item_id'];

            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $valueadd = $this->Valueadd->checkIt($id);
            $this->set('valueadd', $valueadd);
            $sku = array(
                'Sku' => $valueadd['Sku'],
                'Item' => $valueadd['Sku']['Item']
            );

            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
            $itemId = $valueadd['Sku']['item_id'];
            $exclude = $valueadd['Valueadd']['component_id'];
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Valueadd->title,
                $this->Valueadd->saveIt($this->request->data),
                array(
                    'controller' => 'valueadd',
                    'action' => 'index'
                )
            );

        }

        if(!$this->request->data){
            if(isset($valueadd)){
                $this->request->data = $valueadd;
            }
        }

        $this->set('operation', $operation);

        $categoryId = $this->Valueadd->Sku->Item->findCategoryId($itemId);
        $enhancements = $this->Valueadd->Enhancement->findListByCategory($categoryId);
        $componentSkus = $this->Valueadd->Sku->findListAvailComponents($exclude);

        $this->set('enhancements', $enhancements);
        $this->set('components', $componentSkus);
        $this->set('sku', $sku);
    }

    public function del($id) {
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Valueadd->title,
                $this->Valueadd->deleteIt($id),
                array(
                    'controller' => 'valueadd',
                    'action' => 'index'
                )
            );
        }
    }
}