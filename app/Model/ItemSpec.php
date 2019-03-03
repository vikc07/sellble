<?php
class ItemSpec extends AppModel{
    public $title = 'Item Spec';
    private $paginationSettings = array();

    public $belongsTo = array(
        'SpecValue',
        'Spec',
        'Item'
    );

    public $validate = array(
        'spec_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Spec cannot be blank'
            )
        ),
        'item_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Item cannot be blank'
            )
        )
    );

    public function specValues($spec_id = null){
        if(!$spec_id){
            return false;
        }

        return $this->SpecValue->find(
            'list',
            array(
                'conditions' => array(
                    $this->SpecValue->alias . '.spec_id' => $spec_id
                )
            )
        );
    }

    public function specs($item_id = null){
        if(!$item_id){
            return false;
        }

        $specs = $this->find(
            'all',
            array(
                'conditions' => array(
                    $this->alias . '.item_id' => $item_id
                )
            )
        );

        // Transform $specs with spec_id as indices
        $specs_arr = array();
        foreach($specs as $spec){
            $specs_arr[$spec['ItemSpec']['spec_id']] = $spec['ItemSpec'];
        }

        return $specs_arr;
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