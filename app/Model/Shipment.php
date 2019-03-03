<?php
class Shipment extends AppModel{
    public $title = 'Shipment';
    public $idFormatted = '';
    public $virtualFields = array(
        'idFormatted' => "concat('SH',lpad(Shipment.id,8,'0'))"
    );

    public $validate = array(
        'sale_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Sale cannot be blank'
            )
        ),
        'carrier_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Shipping Service cannot be blank'
            )
        ),
        'shipment_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'quantity' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Quantity must be greater than zero'
            )
        ),
        'file_label' => array(
            'extension' => array(
                'rule' => array(
                    'extension',
                    array('pdf')
                ),
                'message' => 'Only PDF allowed'
            )
        )
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
            ),
            'Customer' => array(
                'ShippingUsState',
                'BillingUsState',
                'fields' => array(
                    'id',
                    'listName',
                    'bill_us_state_id',
                    'bill_zip',
                    'bill_city',
                    'bill_country',
                    'ship_us_state_id',
                    'ship_zip',
                    'ship_city',
                    'ship_country'
                )
            )
        ),
        'Tracking'
    );
    private $paginationSettings = array();

    public $belongsTo = array(
        'Sale',
        'Carrier'
    );

    public $hasMany = array(
        'Tracking'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['shipment_dt'])){
                $result[$this->alias]['shipment_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['shipment_dt']));
            }

            if(isset($result[$this->alias]['notes'])){
                $result[$this->alias]['notes'] = htmlspecialchars_decode($result[$this->alias]['notes']);
            }

            if(isset($result[$this->alias]['file_label'])){
                $result[$this->alias]['file_label'] = Configure::read('urlNonImg') . 'shipment/' . $result[$this->alias]['file_label'];
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'limit' => 30,
                'order' => $this->alias . '.shipment_dt desc,' . $this->alias . '.id desc'
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

    public function saveIt($data = null, $validate = true, $fieldList = null){
        $args = array('Tracking');
        if(!$data){
            return false;
        }

        if(!isset($data[$this->alias]['id'])){
            $this->create();
        }
        else{
            $this->id = $data[$this->alias]['id'];
        }

        if($this->save($data, $validate, $fieldList)){
            if(!$this->id){
                $this->id = $this->getInsertID();
            }
            $tracking = $this->Tracking->checkItByShipmentId($this->id);
            if($tracking){
                if($tracking[$this->Tracking->alias]['tracking_no']){
                    $this->Tracking->id = $tracking[$this->Tracking->alias]['id'];
                }
            }

            if($data[$this->alias]['tracking_no']){
                // Save tracking data
                $args[$this->Tracking->alias]['carrier_id'] = $data[$this->alias]['carrier_id'];
                $args[$this->Tracking->alias]['shipment_id'] = $this->id;
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
        if($this->delete($id)){
            $tracking = $this->Tracking->checkItByShipmentId($id);
            if($tracking){
                return $this->Tracking->deleteIt($tracking[$this->Tracking->alias]['id']);
            }
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

    public function uploadArgs($uploadedFileName, $tempFileName){
        return array(
            'destFileName' => $this->idFormatted . '.' . pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'shipment',
            'nonImg' => true
        );
    }
}