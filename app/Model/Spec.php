<?php
class Spec extends AppModel{
    public $title = 'Spec';
    private $paginationSettings = array();

    public $belongsTo = array(
        'SpecSubgroup'
    );

    public $hasMany = array(
        'SpecValue'
    );

    public $validate = array(
        'spec_subgroup_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Spec Subgroup cannot be blank'
            )
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        )
    );

    public function fullList(){
        $specs = $this->find(
            'all',
            array(
                'order' => $this->alias . '.spec_subgroup_id,' . $this->alias . '.name',
                'contain' => array(
                    'SpecSubgroup' => array(
                        'SpecGroup'
                    )
                )
            )
        );

        $specs_arr = array();

        foreach($specs as $spec){
            $specs_arr[$spec[$this->alias]['id']] = $spec[$this->SpecSubgroup->alias][$this->SpecSubgroup->SpecGroup->alias]['name'] . ' - ' .
                $spec[$this->SpecSubgroup->alias]['name'] . ' - ' . $spec[$this->alias]['name'];
        }

        asort($specs_arr);
        return $specs_arr;
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'order' => $this->alias . '.spec_subgroup_id,' . $this->alias . '.name',
                'limit' => 50,
                'contain' => array(
                    'SpecSubgroup' => array(
                        'SpecGroup'
                    )
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

    public function forGroup($spec_group_id = null){
        if(!$spec_group_id){
            return false;
        }

        // Find Sub Groups
        $subgroup = $this->SpecSubgroup->find(
            'list',
            array(
                'conditions' => array(
                    $this->SpecSubgroup->alias . '.spec_group_id' => $spec_group_id
                )
            )
        );

        // Return Specs
        $specs = $this->find(
            'all',
            array(
                'contain' => array(
                    'SpecSubgroup',
                    'SpecValue'
                ),
                'conditions' => array(
                    $this->alias . '.spec_subgroup_id' => array_keys($subgroup)
                ),
                'order' => $this->SpecSubgroup->alias . '.order, ' . $this->SpecSubgroup->alias . '.id'
            )
        );

        // Sanitize Spec Value Array
        foreach($specs as &$spec){
            $spec_values = array();
            foreach($spec['SpecValue'] as &$spec_value){
                $spec_values[$spec_value['id']] = $spec_value['name'];
            }
            asort($spec_values);
            $spec['SpecValue'] = $spec_values;
        }
        return $specs;
    }
}