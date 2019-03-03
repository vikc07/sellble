<?php
class SpecValue extends AppModel{
    public $title = 'Spec Value';
    private $paginationSettings = array();

    public $belongsTo = array(
        'Spec'
    );

    public $validate = array(
        'spec_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Spec cannot be blank'
            )
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        ),
        'logo' => array(
            'extension' => array(
                'rule' => array(
                    'extension',
                    array('gif', 'jpeg', 'jpg', 'png')
                ),
                'message' => 'File must be GIF, JPEG or PNG'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Invalid file'
            )
        )
    );

    private $contain = array(
        'Spec' => array(
            'SpecSubgroup' => array(
                'SpecGroup'
            )
        )
    );

    public function fullList(){
        $spec_values = $this->find(
            'all',
            array(
                'order' => $this->alias . '.spec_id,' . $this->alias . '.name',
                'contain' => $this->contain
            )
        );

        $spec_values_arr = array();

        foreach($spec_values as $spec_value){
            $spec_values_arr[$spec[$this->alias]['id']] = $spec_value[$this->SpecValueSubgroup->SpecValueGroup->alias]['name'] . ' - ' .
                $spec_value[$this->SpecValueSubGroup->alias]['name'] . ' - ' .
                $spec_value[$this->Spec->alias]['name'] . ' - ' .
                $spec_value[$this->alias]['name'];
        }

        asort($spec_values_arr);
        return $spec_values_arr;
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'order' => $this->alias . '.spec_id,' . $this->alias . '.name',
                'limit' => 50,
                'contain' => $this->contain
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

    public function search($terms){
        $terms['q'] = trim(strtolower($terms['q']));
        $this->paginationSettings = array(
            'contain' => $this->contain,
            'order' => $this->alias . '.name',
            'conditions' => array(
                'and' => array(
                    'lower(' . $this->alias . '.name) like ' => '%' . $terms['q'] . '%'
                )
            ),
            'limit' => -1
        );

        if(isset($terms['spec_id']) and $terms['spec_id']){
            $this->paginationSettings['conditions']['and']['spec_id'] = $terms['spec_id'];
        }

        return $this->paginationSettings;
    }

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['logo']) and $result[$this->alias]['logo']){
                $result[$this->alias]['logoFull'] = Configure::read('urlImg') . 'logos/spec_values/' . $result[$this->alias]['logo'];
            }
            else{
                $result[$this->alias]['logoFull'] = false;
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function uploadArgs($uploadedFileName, $tempFileName){
        return array(
            'destFileName' => $this->id . '.' . pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'logos/spec_values'
        );
    }
}