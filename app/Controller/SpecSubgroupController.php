<?php

class SpecSubgroupController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('SpecSubgroup');

    public function index(){
        $this->Paginator->settings = $this->SpecSubgroup->paginationSettings();
        $this->set('spec_subgroups', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $spec_subgroup = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $spec_subgroup = $this->SpecSubgroup->checkIt($id);
            $this->set('spec_subgroup', $spec_subgroup);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->SpecSubgroup->title,
                $this->SpecSubgroup->saveIt($this->request->data),
                array(
                    'controller' => 'spec_subgroup',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $spec_subgroup;
        }

        $this->set('operation', $operation);
        $this->set('specGroups', $this->SpecSubgroup->SpecGroup->fullList());
    }

    public function del($id) {
        $this->SpecSubgroup->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->SpecSubgroup->title,
                $this->SpecSubgroup->deleteIt($id),
                array(
                    'controller' => 'spec_subgroup',
                    'action' => 'index'
                )
            );
        }
    }
}

?>