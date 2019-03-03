<?php
class SaleReturn extends AppModel{
    public $title = 'Return';
    public $idFormatted = '';

    public $virtualFields = array(
        'idFormatted' => "concat('R',lpad(SaleReturn.id,8,'0'))"
    );

    public $validate = array(
        'quantity' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Quantity must be greater than zero'
            )
        ),
        'return_status_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Return Status cannot be blank'
            )
        ),
        'return_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'sale_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sale cannot be blank'
            )
        ),
        'refund_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'refund_credit_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        )
    );

    public $belongsTo = array(
        'Carrier',
        'ReturnStatus',
        'Sale'
    );

    public $hasOne = array(
        'Tracking'
    );

    private $contain = array(
        'Sale' => array(
            'Listing' => array(
                'Sku' => array(
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
                    'fields' => array(
                        'id',
                        'idFormatted'
                    )
                ),
                'fields' => array(
                    'id',
                    'idFormatted'
                )
            )
        ),
        'Tracking',
        'ReturnStatus'
    );

    private $paginationSettings = array();

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'limit' => 30,
                'order' => $this->alias . '.return_dt desc,' . $this->alias . '.id desc'
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

        $this->paginationSettings['conditions'] = array(
            'and' => array(
                'or' => array(
                    $this->virtualFields['idFormatted'] . ' like ' => '%' . $terms['q'] . '%'
                )
            )
        );

        $this->paginationSettings['limit'] = -1;

        // Check Sale
        $salePaginationSettings = $this->Sale->search($terms);
        if(isset($salePaginationSettings['conditions'])){
            $saleIds = $this->Sale->find(
                'list',
                array(
                    'fields' => array(
                        $this->Sale->alias . '.id'
                    ),
                    'conditions' => $salePaginationSettings['conditions']
                )
            );

            if(!empty($saleIds)){
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.sale_id'] = array_values($saleIds);
            }
        }

        return $this->paginationSettings;
    }

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['return_dt'])){
                $result[$this->alias]['return_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['return_dt']));
            }

            if(isset($result[$this->alias]['notes'])){
                $result[$this->alias]['notes'] = htmlspecialchars_decode($result[$this->alias]['notes']);
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function saveIt($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        $updateSku = false;

        if(!isset($data[$this->alias]['id'])){
            $this->create();
            $updateSku = true;
        }
        else{
            $this->id = $data[$this->alias]['id'];
        }

        if($this->save($data, $validate, $fieldList)){
            if(!$this->id){
                $this->id = $this->getInsertID();
            }

            if($updateSku){
                $sale = $this->Sale->checkIt($data[$this->alias]['sale_id']);
                $sku_id = $this->Sale->Listing->findSkuId($sale['Sale']['listing_id']);

                // Increase inventory
                $this->Sale->Listing->Sku->increaseQty($sku_id, $data[$this->alias]['quantity']);
            }

            $tracking = $this->Tracking->checkItBySaleReturnId($this->id);
            if($tracking){
                if($tracking[$this->Tracking->alias]['tracking_no']){
                    $this->Tracking->id = $tracking[$this->Tracking->alias]['id'];
                }
            }

            /*if($data[$this->alias]['tracking_no']){
                // Save tracking data
                $args = array(
                    'Tracking' => array(
                        'carrier_id' => $data[$this->alias]['carrier_id'],
                        'sale_return_id' => $this->id,
                        'tracking_no' => $data[$this->alias]['tracking_no']
                    )
                );

                $this->Tracking->saveIt($args);
            }*/

            if($data[$this->alias]['tracking_no']){
                // Save tracking data
                $args[$this->Tracking->alias]['carrier_id'] = $data[$this->alias]['carrier_id'];
                $args[$this->Tracking->alias]['sale_return_id'] = $this->id;
                $args[$this->Tracking->alias]['tracking_no'] = $data[$this->alias]['tracking_no'];

                if($tracking){
                    $args[$this->Tracking->alias]['id'] = $tracking[$this->Tracking->alias]['id'];
                }

                return $this->Tracking->saveIt($args);
            }
            else if($tracking and isset($data[$this->alias]['tracking_no'])){ // We removed the tracking no
                $this->Tracking->deleteIt($tracking[$this->Tracking->alias]['id']);
            }

            return true;
        }

        return false;
    }


    public function deleteIt($id = null){
        $record = $this->checkIt($id);
        if($this->delete($id)){
            $tracking = $this->Tracking->checkItBySaleReturnId($id);
            if($tracking){
                return $this->Tracking->deleteIt($tracking[$this->Tracking->alias]['id']);
            }

            $sale = $this->Sale->checkIt($record[$this->alias]['sale_id']);
            $sku_id = $this->Sale->Listing->findSkuId($sale['Sale']['listing_id']);

            // Reduce Inventory
            $this->Sale->Listing->Sku->decreaseQty($sku_id, $record[$this->alias]['quantity']);

            return true;
        }
    }

    public function checkIt($id = null){
        if(!$id){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'contain' => $this->contain,
                'conditions' => array(
                    $this->alias . '.id' => $id
                )
            )
        );

        if(!$record){
            throw new NotFoundException(__('Invalid ' . $this->title));
        }

        $this->idFormatted = $record[$this->alias]['idFormatted'];

        return $record;
    }
}