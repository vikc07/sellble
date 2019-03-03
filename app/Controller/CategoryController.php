<?php

class CategoryController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Category');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Category->search($this->request->data['Category']['q']);
        }

        $this->Paginator->settings = $this->Category->paginationSettings();
        $this->set('categories', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $category = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $category = $this->Category->checkIt($id);
            $this->set('category', $category);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Category->title,
                $this->Category->saveIt($this->request->data),
                array(
                    'controller' => 'category',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $category;
        }

        $this->set('operation', $operation);
        $this->set('specGroups', $this->Category->SpecGroup->fullList());
    }

    public function del($id) {
        $this->Category->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Category->title,
                $this->Category->deleteIt($id),
                array(
                    'controller' => 'category',
                    'action' => 'index'
                )
            );
        }
    }
}

?>