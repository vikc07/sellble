<?php
class Brand extends AppModel{
    public $title = 'Brand';
    private $paginationSettings = array();

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        ),
        'manufacturer_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Manufacturer cannot be blank'
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

    public $belongsTo = array(
        'Manufacturer'
    );

    public function fullList(){
        $brands = $this->find(
            'all',
            array(
                'contain' => array(
                    'Manufacturer'
                ),
                'order' => $this->alias . '.name, ' . $this->Manufacturer->alias . '.name '
            )
        );

        $brands_arr = array();
        foreach($brands as $brand){
            $brands_arr[$brand['Brand']['id']] = $brand['Manufacturer']['name'] . ' - ' . $brand['Brand']['name'];
        }
        asort($brands_arr);
        return $brands_arr;
    }

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['logo']) and $result[$this->alias]['logo']){
                $result[$this->alias]['logoFull'] = Configure::read('urlImg') . 'logos/brand/' . $result[$this->alias]['logo'];
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
                'contain' => $this->Manufacturer->alias,
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

    public function mfrList(){
        return $this->Manufacturer->fullList();
    }

    public function uploadArgs($uploadedFileName, $tempFileName){
        return array(
            'destFileName' => $this->id . '.' . pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'logos/brand'
        );
    }

    public function search($terms){
        $terms['q'] = trim(strtolower($terms['q']));
        $this->paginationSettings();
        $this->paginationSettings['conditions'] = array(
            $this->alias . '.name like ' => '%' . $terms['q'] . '%'
        );

        $this->paginationSettings['limit'] = -1;

        // Check Manufacturer
        $manufacturerIds = $this->Manufacturer->find(
            'list',
            array(
                'fields' => array(
                    $this->Manufacturer->alias . '.id'
                ),
                'conditions' => array(
                    $this->Manufacturer->alias . '.name like ' => '%' . $terms['q'] . '%'
                )
            )
        );

        if(!empty($manufacturerIds)){
            $this->paginationSettings['conditions'] = array(
                $this->alias . '.manufacturer_id' => $manufacturerIds
            );
        }

        return $this->paginationSettings;
    }
}