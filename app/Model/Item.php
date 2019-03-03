<?php
class Item extends AppModel{
    public $title = 'Item';
    private $paginationSettings = array();
    public $idFormatted = false;

    public $virtualFields = array(
        'idFormatted' => "concat('I',lpad(Item.id,8,'0'))",
        'fullName' => "concat(Item.model,if(Item.description<>'',concat(' - ',Item.description),''),if(Item.upc<>'',concat(' - ',Item.upc),''), ' - ', concat('I',lpad(Item.id,8,'0')))"
    );

    public $displayField = 'fullName';

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        ),
        'brand_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Brand cannot be blank'
            )
        ),
        'category_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Category cannot be blank'
            )
        ),
        'model' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Model cannot be blank'
            )
        )
    );

    public $belongsTo = array(
        'Brand',
        'Category'
    );

    public $hasMany = array(
        'ItemPhoto',
        'Sku',
        'ItemSpec'
    );

    public function findCategoryId($itemId){
        if(!$itemId){
            return false;
        }

        $items = $this->findById($itemId);

        if($items){
            return $items['Item']['category_id'];
        }

        return false;
    }

    public function findSpecGroupId($itemId){
        if(!$itemId){
            return false;
        }

        $items = $this->find(
            'first',
            array(
                'contain' => array(
                    'Category'
                ),
                'conditions' => array(
                    $this->alias . '.id' => $itemId
                )
            )
        );

        if($items){
            return $items['Category']['spec_group_id'];
        }

        return false;
    }

    public function fullList(){
        $items = $this->find(
            'all',
            array(
                'contain' => array(
                    'Brand' => array(
                        'Manufacturer' => array(
                            'fields' => array(
                                'id',
                                'name'
                            )
                        ),
                        'fields' => array(
                            'id',
                            'name'
                        )
                    )
                )
            )
        );

        $items_arr = array();
        foreach($items as $item){
            $manufacturer = $item['Brand'][0]['Brand']['Manufacturer'][0]['Manufacturer'];
            $brand = $item['Brand'][0]['Brand'];
            $items_arr[$item['Item']['id']] = $manufacturer['name'] . ' - ' . $brand['name'] . ' - ' .
                $item['Item']['fullName'];
        }

        asort($items_arr);

        return $items_arr;
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => array(
                    'Brand' => array(
                        'Manufacturer' => array(
                            'fields' => array(
                                'id',
                                'name'
                            )
                        ),
                        'fields' => array(
                            'id',
                            'name'
                        )
                    ),
                    'Category' => array(
                        'fields' => array(
                            'id',
                            'name'
                        )
                    ),
                    'ItemPhoto' => array(
                        'fields' => array(
                            'id'
                        )
                    )
                ),
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = trim(strtolower($terms['q']));
        $this->paginationSettings();
        $this->paginationSettings['conditions'] = array(
            'and' => array(
                'or' => array(
                    $this->virtualFields['fullName'] . ' like ' => '%' . $terms['q'] . '%'
                )
            )
        );

        if(isset($terms['category_id']) and $terms['category_id']){
            $this->paginationSettings['conditions']['and']['category_id'] = $terms['category_id'];
        }

        $this->paginationSettings['limit'] = -1;

        // Check Brand
        $brandPaginationSettings = $this->Brand->search($terms);
        $brandIds = $this->Brand->find(
            'list',
            array(
                'fields' => array(
                    $this->Brand->alias . '.id'
                ),
                'conditions' => $brandPaginationSettings['conditions']
            )
        );

        if(!empty($brandIds)){
            $this->paginationSettings['conditions']['and']['or']['brand_id'] = $brandIds;
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
            'destFileName' => basename($uploadedFileName, pathinfo($uploadedFileName, PATHINFO_EXTENSION)) .
                pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'item/' . $this->idFormatted
        );
    }
}