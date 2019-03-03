<?php
class PurchaseStatus extends AppModel{
    public $title = 'Purchase Status';
    private $paginationSettings = array();

    public $virtualFields = array(
        'idFormatted'	=>	"lpad(PurchaseStatus.id,2,'0')",
        'fullName' => "concat(lpad(PurchaseStatus.id,2,'0'),' - ',name)"
    );

    public $displayField = 'fullName';

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        )
    );

    public function fullList(){
        return $this->find(
            'list',
            array(
                'order' => $this->alias . '.id'
            )
        );
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'order' => $this->alias . '.id',
                'limit' => 50
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

?>