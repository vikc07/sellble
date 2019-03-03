<?php
class SpecSubgroup extends AppModel{
    public $title = 'Spec Subgroup';
    private $paginationSettings = array();

    public $belongsTo = array(
        'SpecGroup'
    );

    public $validate = array(
        'spec_group_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Spec Group cannot be blank'
            )
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        ),
        'order' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Order cannot be blank'
            )
        )
    );

    public function fullList(){
        $subgroups = $this->find(
            'all',
            array(
                'order' => $this->alias . '.spec_group_id,' . $this->alias . '.name,' . $this->alias . '.order',
                'contain' => array(
                    'SpecGroup'
                )
            )
        );

        $subgroups_arr = array();

        foreach($subgroups as $subgroup){
            $subgroups_arr[$subgroup[$this->alias]['id']] = $subgroup[$this->SpecGroup->alias]['name'] . ' - ' . $subgroup[$this->alias]['name'];
        }

        asort($subgroups_arr);
        return $subgroups_arr;
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'order' => $this->alias . '.spec_group_id,' . $this->alias . '.name,' . $this->alias . '.order',
                'limit' => 50,
                'contain' => array(
                    'SpecGroup'
                )
            );
        }

        return $this->paginationSettings;
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
}