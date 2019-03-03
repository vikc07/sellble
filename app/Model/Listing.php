<?php
class Listing extends AppModel{
    public $title = 'Listing';
    public $virtualFields = array(
        'idFormatted' => "concat('L',lpad(Listing.id,8,'0'))",
        'est_fee_amt' => 'list_fee_amt+est_fv_fee_amt+est_paypal_fee_amt',
        'est_net_amt' => 'list_price_amt-(list_fee_amt+est_fv_fee_amt+est_paypal_fee_amt+est_shipping_amt+est_other_amt+est_sales_tax_amt)',
        'est_net_amt_grand' => '(list_price_amt-(list_fee_amt+est_fv_fee_amt+est_paypal_fee_amt+est_shipping_amt+est_other_amt+est_sales_tax_amt))*Listing.quantity',
        'has_ended' => 'if(Listing.end_dt > now(),0,1)'
    );

    private $contain = array(
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
                'Category',
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
        'EbayListing'
    );

    private $paginationSettings = array();

    public $validate = array(
        'marketplace_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Marketplace cannot be blank'
            )
        ),
        'listing_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'end_dt' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Date must be in YYYY-MM-DD format'
            )
        ),
        'duration' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Duration must be greater than zero'
            )
        ),
        'return_period' => array(
            'comparison' => array(
                'rule' => array( 'comparison', '>', 0),
                'message' => 'Return period must be greater than zero'
            )
        ),
        'listing_fee_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'list_price_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'est_fv_fee_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'est_paypal_fee_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
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
        )
    );

    public $belongsTo = array(
        'Sku',
        'Marketplace'
    );

    public $hasOne = array(
        'EbayListing'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['listing_dt'])){
                $result[$this->alias]['listing_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['listing_dt']));
            }

            if(isset($result[$this->alias]['end_dt'])){
                $result[$this->alias]['end_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['end_dt']));
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function end($id){
        if(!$id){
            return false;
        }

        $listing = $this->findById($id);
        if($listing){
            $end_dt = date('Y-m-d');
            $this->id = $id;
            return $this->saveField('end_dt', $end_dt);
        }

        return false;
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'limit' => 50,
                'order' => $this->alias . '.listing_dt desc,' . $this->alias . '.id desc',
                'conditions' => array(
                    $this->virtualFields['has_ended'] . ' = ' => false
                )
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

        if(isset($terms['marketplace_id']) and $terms['marketplace_id']){
            $this->paginationSettings['conditions']['and']['marketplace_id'] = $terms['marketplace_id'];
        }

        if(isset($terms['status'])){
            $this->paginationSettings['conditions']['and'][$this->virtualFields['has_ended'] . ' = '] = $terms['status'];
        }

        $this->paginationSettings['limit'] = -1;

        // Check Sku
        $skuPaginationSettings = $this->Sku->search($terms);
        if(isset($skuPaginationSettings['conditions'])){
            $skuIds = $this->Sku->find(
                'list',
                array(
                    'fields' => array(
                        $this->Sku->alias . '.id'
                    ),
                    'conditions' => $skuPaginationSettings['conditions']
                )
            );

            if(!empty($skuIds)){
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.sku_id'] = array_values($skuIds);
            }
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
        $record = $this->checkIt($id);
        if($record['Listing']['do_not_delete']){
            return false;
        }

        return $this->delete($id);
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

        return $record;
    }

    public function ebayListing($id = null){
        $this->contain['Sku']['Item']['ItemPhoto'] = array(
            'fields' => array(
                'id',
                'item_id',
                'itemIdFormatted',
                'file_name'
            )
        );
        $this->contain['Sku']['Item']['Brand']['Manufacturer']['fields'] = array(
            'id',
            'name',
            'logo'
        );

        $this->contain['Sku']['Item']['Brand']['fields'] = array(
            'id',
            'name',
            'logo'
        );

        $this->contain['Sku']['Item']['ItemSpec'] = array(
            'Spec' => array(
                'SpecSubgroup' => array(
                    'SpecGroup'
                )
            ),
            'SpecValue'
        );

        $results = $this->checkIt($id);
        unset($this->contain['Sku']['Item']['Spec']);

        // Sanitize the ItemSpec array
        $spec = array();
        $condition = '';
        foreach($results['Sku']['Item']['ItemSpec'] as &$itemSpec){
            if(($itemSpec['value'] or $itemSpec['spec_value_id']) and !$itemSpec['Spec']['hide_from_public']){
                $spec_value = array('name' => '', 'logoFull' => '');
                if(isset($itemSpec['SpecValue'][0])){
                    $spec_value = $itemSpec['SpecValue'][0]['SpecValue'];
                    if(!isset($spec_value['logoFull'])){
                        $spec_value['logoFull'] = '';
                    }
                }
                $spec[$itemSpec['Spec']['SpecSubgroup']['name']][$itemSpec['Spec']['name']] =
                    array(
                        'name' => ($itemSpec['spec_value_id']) ? $spec_value['name'] : $itemSpec['value'],
                        'logoFull' => $spec_value['logoFull']
                    );
            }
            else if($itemSpec['Spec']['hide_from_public'] and
                $itemSpec['Spec']['SpecSubgroup']['name'] == 'Condition' and
                $itemSpec['Spec']['name'] == 'Rating'){
                $condition = $itemSpec['SpecValue'][0]['SpecValue']['name'];
            }
        }

        ksort($spec);
        $results['Sku']['Item']['ItemSpec'] = $spec;
        $results['Sku']['Item']['Condition'] = $condition;
        return $results;
    }

    public function saveEbay($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        if(!isset($data[$this->EbayListing->alias]['id'])){
            $this->create();
        }
        else{
            $this->EbayListing->id = $data[$this->EbayListing->alias]['id'];
        }


        return $this->EbayListing->save($data, $validate, $fieldList);
    }

    public function findSkuId($id){
        if(!$id){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $id
                )
            )
        );

        if($record){
            return $record['Listing']['sku_id'];
        }

        return false;
    }

    public function setDoNotDelete($id){
        if(!$id){
            return false;
        }
        $this->id = $id;
        return $this->saveField('do_not_delete', true);
    }

    public function unsetDoNotDelete($id){
        if(!$id){
            return false;
        }
        $this->id = $id;
        return $this->saveField('do_not_delete', false);
    }
}