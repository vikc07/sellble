<?php
class PurchaseLine extends AppModel{
    public $title = 'Purchase';
    private $paginationSettings = array();

    public $validate = array(
        'item_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Item cannot be blank'
            )
        ),
        'carrier_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Carrier cannot be blank'
            )
        ),
        'quantity' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Quantity must be greater than zero'
            )
        ),
        'shipping_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'per_unit_price_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        )
    );

    public function findQty($id){
        if(!$id){
            return false;
        }

        $existing = $this->findById($id);
        if(!$existing){
            return false;
        }

        return $existing['PurchaseLine']['quantity'];
    }

    public $belongsTo = array(
        'Item',
        'Carrier',
        'Purchase'
    );

    public $hasOne = array(
        'Tracking',
        'Sku'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['shipping_dt'])){
                $result[$this->alias]['shipping_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['shipping_dt']));
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => array(
                    'Item' => array(
                        'Brand' => array(
                            'fields' => array(
                                'id',
                                'name'
                            ),
                            'Manufacturer' => array(
                                'fields' => array(
                                    'id',
                                    'name'
                                )
                            )
                        ),
                        'fields' => array(
                            'id',
                            'fullName'
                        )
                    ),
                    'Carrier' => array(
                        'fields' => array(
                            'id',
                            'FullName',
                            'logo'
                        )
                    )
                ),
                'limit' => 50,
                'order' => $this->alias . '.id desc'
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

        $this->paginationSettings['limit'] = -1;

        // Check Brand
        $itemPaginationSettings = $this->Item->search($terms);
        $itemIds = $this->Item->find(
            'list',
            array(
                'fields' => array(
                    $this->Item->alias . '.id'
                ),
                'conditions' => $itemPaginationSettings['conditions']
            )
        );

        if(!empty($itemIds)){
            $this->paginationSettings['conditions'][$this->alias . '.item_id'] = $itemIds;
        }

        return $this->paginationSettings;
    }

    public function deleteMany($IDs = array()){
        if(empty($IDs)){
            return false;
        }

        foreach($IDs as $id){
            $this->delete($id);
        }

        return true;
    }

    public function checkIt($id = null){
        if(!$id){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'contain' => array(
                    'Tracking',
                    'Carrier'
                ),
                'conditions' => array(
                    $this->alias . '.id' => $id
                )
            )
        );

        if(!$record){
            throw new NotFoundException(__('Invalid ' . $this->title));
        }

        $this->id = $record['PurchaseLine']['id'];

        return $record;
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

    public function markShipped($data = null){
        $args = array('Tracking');
        if(!$data){
            return false;
        }

        if($this->saveIt($data)){
            $tracking = $this->Tracking->checkItByPurchaseLineId($this->id);
            if($tracking){
                if($tracking['Tracking']['tracking_no']){
                    if(!$data['PurchaseLine']['tracking_no']){
                        return $this->Tracking->deleteIt($tracking['Tracking']['id']);
                    }

                    $this->Tracking->id = $tracking['Tracking']['id'];
                    $args['Tracking']['id'] = $tracking['Tracking']['id'];
                }
            }

            if($data[$this->alias]['tracking_no']){
                // Save tracking data
                $args['Tracking']['carrier_id'] = $data[$this->alias]['carrier_id'];
                $args['Tracking']['purchase_line_id'] = $this->id;
                $args['Tracking']['tracking_no'] = $data[$this->alias]['tracking_no'];
                return $this->Tracking->saveIt($args);
            }
            else if($tracking){ // We removed the tracking no
                $this->Tracking->deleteIt($tracking['Tracking']['id']);
            }

            return true;
        }
        return false;
    }

    public function removeShipping(){
        $data = array(
            'PurchaseLine' => array(
                'id' => $this->id,
                'shipping_dt' => null,
                'carrier_id' => null,
                'tracking_no' => ''
            )
        );

        if($this->saveIt($data, false)){
            $tracking = $this->Tracking->checkItByPurchaseLineId($this->id);
            if($tracking){
                return $this->Tracking->deleteIt($tracking['Tracking']['id']);
            }
            return true;
        }

        return false;
    }
}