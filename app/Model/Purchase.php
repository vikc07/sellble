<?php
class Purchase extends AppModel{
    public $title = 'Purchase';
    private $paginationSettings = array();

    public $virtualFields = array(
        'idFormatted' => "concat('P',lpad(Purchase.id,8,'0'))"
    );

    public $validate = array(
        'total_items' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Total items must be greater than zero'
            )
        ),
        'purchase_status_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Purchase Status cannot be blank'
            )
        ),
        'purchase_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'marketplace_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Marketplace cannot be blank'
            )
        ),
        'shipping_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'sales_tax_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'other_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'file_invoice' => array(
            'extension' => array(
                'rule' => array(
                    'extension',
                    array('pdf')
                ),
                'message' => 'Only PDF allowed'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Invalid file'
            )
        )
    );

    public $belongsTo = array(
        'Marketplace',
        'PurchaseStatus'
    );

    public $hasMany = array(
        'PurchaseLine'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['purchase_dt'])){
                $result[$this->alias]['purchase_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['purchase_dt']));
            }

            if(isset($result[$this->alias]['notes'])){
                $result[$this->alias]['notes'] = htmlspecialchars_decode($result[$this->alias]['notes']);
            }

            if(isset($result[$this->alias]['file_invoice'])){
                $result[$this->alias]['file_invoice'] = Configure::read('urlNonImg') . 'purchase/' .
                    $result[$this->alias]['file_invoice'];
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => array(
                    'PurchaseLine' => array(
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
                        )
                    ),
                    'Marketplace' => array(
                        'fields' => array(
                            'id',
                            'name',
                            'logo'
                        )
                    ),
                    'PurchaseStatus' => array(
                        'fields' => array(
                            'id',
                            'name'
                        )
                    )
                ),
                'limit' => 50,
                'order' => $this->alias . '.purchase_dt desc,' . $this->alias . '.id desc'
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

        if(isset($terms['purchase_status_id']) and $terms['purchase_status_id']){
            $this->paginationSettings['conditions']['and']['purchase_status_id'] = $terms['purchase_status_id'];
        }

        if(isset($terms['marketplace_id']) and $terms['marketplace_id']){
            $this->paginationSettings['conditions']['and']['marketplace_id'] = $terms['marketplace_id'];
        }

        $this->paginationSettings['limit'] = -1;

        // Check Brand
        $purchaseLinePaginationSettings = $this->PurchaseLine->search($terms);
        if(isset($purchaseLinePaginationSettings['conditions'])){
            $purchaseIds = $this->PurchaseLine->find(
                'list',
                array(
                    'fields' => array(
                        $this->PurchaseLine->alias . '.purchase_id'
                    ),
                    'conditions' => $purchaseLinePaginationSettings['conditions']
                )
            );

            if(!empty($purchaseIds)){
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.id'] = array_values($purchaseIds);
            }
        }

        return $this->paginationSettings;
    }

    public function saveItAll($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        $data = array(
            $this->alias => $data[$this->alias],
            $this->PurchaseLine->alias => $data[$this->PurchaseLine->alias]
        );

        if(!isset($data[$this->alias]['id'])){
            $this->create();
            foreach($data[$this->PurchaseLine->alias] as $key => $purchase_line){
                if(!$purchase_line['item_id']){
                    unset($data[$this->PurchaseLine->alias][$key]);
                }
            }
        }
        else{
            $this->id = $data[$this->alias]['id'];
        }

        if($this->saveAll($data)){
            $dataSku = array();

            // Check empty purchase lines and remove them
            $totalMiscAmts = $data['Purchase']['sales_tax_amt'] +
                $data['Purchase']['shipping_amt'] +
                $data['Purchase']['other_amt'];
            $totalUnits = $data['Purchase']['total_quantity'];

            if(!empty($this->PurchaseLine->inserted_ids)){
                foreach($this->PurchaseLine->inserted_ids as $key => $purchase_line_id){
                    $purchase_line = $data[$this->PurchaseLine->alias][$key];
                    $dataSku[] = array(
                        'purchase_line_id' => $purchase_line_id,
                        'item_id' => $purchase_line['item_id'],
                        'quantity_avail' => $purchase_line['quantity'],
                        'per_unit_price_amt' => round($purchase_line['per_unit_price_amt'] +
                            $totalMiscAmts * ($purchase_line['quantity'] / $totalUnits) / $purchase_line['quantity'], 2)
                    );
                }
            }
            else{
                foreach($data[$this->PurchaseLine->alias] as $purchase_line){
                    $sku_id = $this->PurchaseLine->Sku->findIdByPurchaseLineId($purchase_line['id']);
                    if($sku_id){
                        $dataSku[] = array(
                            'id' => $sku_id,
                            'per_unit_price_amt' => round($purchase_line['per_unit_price_amt'] +
                                $totalMiscAmts * ($purchase_line['quantity'] / $totalUnits) / $purchase_line['quantity'], 2)
                        );
                    }
                }
            }

            return $this->PurchaseLine->Sku->saveMany($dataSku);
        }
    }

    public function checkIt($id = null){
        if(!$id){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'contain' => array(
                    'PurchaseLine' => array(
                        'Sku'
                    )
                ),
                'conditions' => array(
                    $this->alias . '.id' => $id
                )
            )
        );

        if(!$record){
            throw new NotFoundException(__('Invalid ' . $this->title));
        }

        $this->idFormatted = $record['Purchase']['idFormatted'];
        return $record;
    }

    public function deleteIt($id){
        // Check
        $purchase = $this->checkIt($id);
        $skuIds = array();
        $purchaseLineIds = array();
        foreach($purchase['PurchaseLine'] as $purchase_line){
            if($purchase_line['Sku']['do_not_delete']){
                return false;
            }
            $skuIds[] = $purchase_line['Sku']['id'];
            $purchaseLineIds[] = $purchase_line['id'];
        }

        if($this->PurchaseLine->Sku->deleteMany($skuIds)){
            if($this->PurchaseLine->deleteMany($purchaseLineIds)){
                return $this->delete($id);
            }
        }

        return false;
    }

    public function uploadArgs($uploadedFileName, $tempFileName){
        return array(
            'destFileName' => $this->idFormatted . '.' . pathinfo($uploadedFileName, PATHINFO_EXTENSION),
            'tempFileName' => $tempFileName,
            'saveFolder' => 'purchase',
            'nonImg' => true
        );
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

    public function idFormatted($id){
        if(!$id){
            return false;
        }

        return 'P' . str_pad($id,8,0);
    }

    public function purchaseYears(){
        $purchase_years = array();
        for($i = 2012; $i <= date('Y'); $i++){
            $purchase_years[$i] = $i;
        }

        return $purchase_years;
    }

    public function purchaseMonths(){
        return array(
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        );
    }
}