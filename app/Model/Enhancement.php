<?php
class Enhancement extends AppModel{
    public $title = 'Enhancement';
    private $paginationSettings = array();

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        )
    );

    public $hasAndBelongsToMany = array(
        'Category'
    );

    public $hasMany = array(
        'Valueadds'
    );

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
                'order' => $this->alias . '.name',
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function search($term){
        $this->paginationSettings = array(
            'order' => $this->alias . '.name',
            'conditions' => array(
                'lower(' . $this->alias . '.name) like ' => '%' . strtolower($term) . '%'
            ),
            'limit' => -1
        );
    }

    public function findListByCategory($categoryId){
        if(!$categoryId){
            return false;
        }
        $enhancements = $this->Category->find(
            'all',
            array(
                'conditions' => array(
                    $this->Category->alias . '.id' => $categoryId
                ),
                'contain' => array(
                    $this->alias
                )
            )
        );

        $enhancements_arr = array();
        if($enhancements){
            if(isset($enhancements[0]['Enhancement'])){
                foreach($enhancements[0]['Enhancement'] as $enhancement){
                    $enhancements_arr[$enhancement['id']] = $enhancement['name'];
                }
            }
        }

        asort($enhancements_arr);
        return $enhancements_arr;
    }

    public function findWithCategories($id){
        return $this->find(
            'first',
            array(
                'contain' => $this->Category->alias,
                'order' => $this->alias . '.name',
                'conditions' => array(
                    'id' => $id
                )
            )
        );
    }
}