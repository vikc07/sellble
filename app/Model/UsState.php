<?php
class UsState extends AppModel{
    public $hasMany = array(
        'Customer'
    );

    public $validates = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'State cannot be blank'
            )
        )
    );

    public function fullList(){
        $states = $this->find('all');
        $states_arr = array();
        foreach($states as $state){
            $states_arr[$state[$this->alias]['id']] = $state[$this->alias]['name'] . ' - ' . $state[$this->alias]['full_nm'];
        }

        asort($states_arr);
        return $states_arr;
    }
}