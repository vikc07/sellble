<?php
class Marketplace extends AppModel{
    public $title = 'Marketplace';
    private $paginationSettings = array();

    public $validate = array(
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

    public function fullList(){
        return $this->find(
            'list',
            array(
                'order' => $this->alias . '.name'
            )
        );
    }

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['logo']) and $result[$this->alias]['logo']){
                $result[$this->alias]['logoFull'] = Configure::read('urlImg') . 'logos/marketplace/' . $result[$this->alias]['logo'];
            }
            else{
                $result[$this->alias]['logoFull'] = false;
            }
        }

        return parent::afterFind($resultsArray, $useless);
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

    public function uploadArgs($uploadedFileName, $tempFileName){
        return array(
            'destFileName' => $this->id . '.' . pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'logos/marketplace'
        );
    }
}