<?php

class EnhancementController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Enhancement');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Enhancement->search($this->request->data['Enhancement']['q']);
        }

        $this->Paginator->settings = $this->Enhancement->paginationSettings();
        $this->set('enhancements', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $enhancement = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $this->Enhancement->checkIt($id);
            $enhancement = $this->Enhancement->findWithCategories($id);
            $this->set('enhancement', $enhancement);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Enhancement->title,
                $this->Enhancement->saveIt($this->request->data),
                array(
                    'controller' => 'enhancement',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $enhancement;
        }

        $this->set('operation', $operation);
        $this->set('categories', $this->Enhancement->Category->fullList());
    }

    public function del($id) {
        $this->Enhancement->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Enhancement->title,
                $this->Enhancement->deleteIt($id),
                array(
                    'controller' => 'enhancement',
                    'action' => 'index'
                )
            );
        }
    }
}

?>