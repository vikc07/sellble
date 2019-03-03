<?php
class Category extends AppModel{
    public $title = 'Category';
    private $paginationSettings = array();
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        )
    );

    public $belongsTo = array(
        'SpecGroup'
    );

    public $hasAndBelongsToMany = array(
        'Enhancement'
    );

    public function getEbayFee($id){
        if(!$id){
            return false;
        }

        $category = $this->findById($id);
        if($category){
            return $category['Category']['ebay_fee_percent']/100;
        }
        else{
            return false;
        }
    }

    public function saveIt($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        if(!isset($data[$this->alias]['id'])){
            $this->create();
        }
        else{
            $this->id = $data[$this->alias]['id'];
        }

        return $this->save($data, $validate, $fieldList);
    }

    public function deleteIt($id = null){
        return $this->delete($id);
    }

    public function checkIt($id = null){
        if(!$id){
            return false;
        }

        $record = $this->findById($id);
        if(!$record){
            throw new NotFoundException(__('Invalid ' . $this->title));
        }

        return $record;
    }

    public function fullList(){
        return $this->find(
            'list',
            array(
                'order' => $this->alias . '.name'
            )
        );
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->SpecGroup->alias,
                'order' => $this->alias . '.name',
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function search($term){
        $this->paginationSettings = array(
            'contain' => $this->SpecGroup->alias,
            'order' => $this->alias . '.name',
            'conditions' => array(
                'lower(' . $this->alias . '.name) like ' => '%' . strtolower($term) . '%'
            ),
            'limit' => -1
        );
    }
}