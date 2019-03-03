<?php
class Sale extends AppModel{
    public $title = 'Sale';
    public $virtualFields = array(
        'idFormatted' => "concat('S',lpad(Sale.id,8,'0'))",
        'fee_amt' => 'fv_fee_amt+paypal_fee_amt',
        'net_amt' => 'sale_amt+Sale.shipping_amt+sales_tax_amt-(fv_fee_amt+paypal_fee_amt+other_amt)',
        'sale_year' => 'year(Sale.sale_dt)',
        'sale_month' => 'month(Sale.sale_dt)'
    );

    private $contain = array(
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
            'fields' => array(
                'id',
                'idFormatted',
                'listName'
            )
        ),
        'Shipment',
        'SaleReturn'
    );
    private $paginationSettings = array();

    public $validate = array(
        'listing_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Listing cannot be blank'
            )
        ),
        'customer_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Customer cannot be blank'
            )
        ),
        'sale_dt' => array(
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
        'sale_amt' => array(
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
        'fv_fee_amt' => array(
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Invalid amount'
            )
        ),
        'paypal_fee_amt' => array(
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
        'Listing',
        'Customer'
    );

    public $hasMany = array(
        'Shipment'
    );

    public $hasOne = array(
        'SaleReturn'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['sale_dt'])){
                $result[$this->alias]['sale_dt'] = date( 'Y-m-d', strtotime($result[$this->alias]['sale_dt']));
            }

            if(isset($result[$this->alias]['notes'])){
                $result[$this->alias]['notes'] = htmlspecialchars_decode($result[$this->alias]['notes']);
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'limit' => 30,
                'order' => $this->alias . '.sale_dt desc,' . $this->alias . '.id desc'
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

        // Check Listing
        $listingPaginationSettings = $this->Listing->search($terms);
        if(isset($listingPaginationSettings['conditions'])){
            $listingIds = $this->Listing->find(
                'list',
                array(
                    'fields' => array(
                        $this->Listing->alias . '.id'
                    ),
                    'conditions' => $listingPaginationSettings['conditions']
                )
            );

            if(!empty($listingIds)){
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.listing_id'] = array_values($listingIds);
            }
        }

        // Check Customer
        $customerPaginationSettings = $this->Customer->search($terms);
        if(isset($customerPaginationSettings['conditions'])){
            $customerIds = $this->Customer->find(
                'list',
                array(
                    'fields' => array(
                        $this->Customer->alias . '.id'
                    ),
                    'conditions' => $customerPaginationSettings['conditions']
                )
            );

            if(!empty($customerIds)){
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.customer_id'] = array_values($customerIds);
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
        if($record[$this->alias]['do_not_delete']){
            return false;
        }

        if($this->delete($id)){
            $sku_id = $this->Listing->findSkuId($record[$this->alias]['listing_id']);
            // Increase inventory
            if($this->Listing->Sku->increaseQty($sku_id, $record[$this->alias]['quantity'])){
                return true;
            }
            return true;
        }

        return false;
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

    public function saveItAll($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        $updateSku = true;

        if(!isset($data[$this->alias]['id'])){
            $this->create();
        }
        else{
            $this->id = $data[$this->alias]['id'];
            $updateSku = false;
        }

        if($this->saveAll($data)){
            // Set do not delete for Listing
            $this->Listing->setDoNotDelete($data[$this->alias]['listing_id']);
            if($updateSku){
                $sku_id = $this->Listing->findSkuId($data[$this->alias]['listing_id']);
                // Reduce inventory
                if($this->Listing->Sku->decreaseQty($sku_id, $data[$this->alias]['quantity'])){
                    // All Sold? End Listing
                    if($this->Listing->Sku->findQty($sku_id) == 0){
                        $this->Listing->end($data[$this->alias]['listing_id']);
                    }
                    return true;
                }
            }
            return true;
        }
    }

    public function detail($year, $month){
        $this->paginationSettings();
        $this->paginationSettings['contain']['Listing']['fields'] = array(
            'id',
            'idFormatted',
            'list_fee_amt',
            'quantity'
        );

        $this->paginationSettings['contain']['Listing']['Sku']['fields'] = array(
            'id',
            'idFormatted',
            'per_unit_price_amt'
        );

        if($year){
            $this->paginationSettings['conditions'][$this->alias . '.sale_year'] = $year;
        }

        $this->paginationSettings['order'] = array(
            $this->alias . '.sale_dt'
        );

        if($month){
            $this->paginationSettings['conditions'][$this->alias . '.sale_month'] = $month;
        }

        unset($this->paginationSettings['Customer']);
        $this->paginationSettings['contain']['Listing']['Sku']['Item']['Category'] = array(
            'fields' => array(
                'id',
                'name'
            )
        );

        $this->paginationSettings['limit'] = -1;

        $sales = $this->find(
            'all',
            $this->paginationSettings
        );

        $saleDetail = array();
        foreach($sales as $key => $sale){
            $listing = $sale['Listing'][0]['Listing'];
            $sku = $listing['Sku'];
            $item = $sku['Item'];
            $brand = $item['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
            $category = $listing['Sku']['Item']['Category']['name'];

            // Get Valueadd costs
            $valueadds = $this->Listing->Sku->Valueadd->allForSku($listing['sku_id']);
            $valueadd_amt = 0;
            foreach($valueadds as $valueadd){
                $valueadd_amt += $valueadd['ComponentSku']['per_unit_price_amt'];
            }

            $shipping_amt = 0;
            foreach($sale['Shipment'] as $shipment){
                $shipping_amt += $shipment['shipping_amt'];
            }

            $list_fee_amt = $listing['list_fee_amt'] * $sale['Sale']['quantity'] / $listing['quantity'];
            $return_amt = 0;
            if(isset($sale['SaleReturn'][0]['SaleReturn']['refund_amt'])){
                $return_amt = $sale['SaleReturn'][0]['SaleReturn']['refund_amt'] -
                    $sale['SaleReturn'][0]['SaleReturn']['refund_credit_amt'];
                $sku['per_unit_price_amt'] = 0;
                $valueadd_amt = 0;
            }

            $cost_amt = $sku['per_unit_price_amt'] * $sale['Sale']['quantity'];
            $collected_amt = $sale['Sale']['sale_amt'] + $sale['Sale']['shipping_amt']; // + $sale['Sale']['sales_tax_amt'];
            $fee_amt = $sale['Sale']['fv_fee_amt'] + $sale['Sale']['paypal_fee_amt'] + $sale['Sale']['other_amt'] + $list_fee_amt;
            $net_amt = $collected_amt - $cost_amt - $valueadd_amt - $fee_amt - $list_fee_amt - $shipping_amt - $return_amt;

            $saleDetail[$key]['sale_dt'] = $sale['Sale']['sale_dt'];
            $saleDetail[$key]['sale_year'] = $sale['Sale']['sale_year'];
            $saleDetail[$key]['sale_month'] = $sale['Sale']['sale_month'];
            $saleDetail[$key]['id'] = $sale['Sale']['id'];
            $saleDetail[$key]['idFormatted'] = $sale['Sale']['idFormatted'];
            $saleDetail[$key]['category'] = $category;
            $saleDetail[$key]['sku'] = $sku['idFormatted'];
            $saleDetail[$key]['item'] = $manufacturer['name'] . ' - ' . $brand['name'] . ' - ' . $item['fullName'];
            $saleDetail[$key]['quantity'] = $sale['Sale']['quantity'];
            $saleDetail[$key]['collected_amt'] = $collected_amt;
            $saleDetail[$key]['cost_amt'] = $cost_amt;
            $saleDetail[$key]['valueadd_amt'] = $valueadd_amt;
            $saleDetail[$key]['list_fee_amt'] = $list_fee_amt;
            $saleDetail[$key]['fv_fee_amt'] = $sale['Sale']['fv_fee_amt'];
            $saleDetail[$key]['paypal_fee_amt'] = $sale['Sale']['paypal_fee_amt'];
            $saleDetail[$key]['other_amt'] = $sale['Sale']['other_amt'];
            $saleDetail[$key]['fee_amt'] = $fee_amt;
            $saleDetail[$key]['shipping_amt'] = $shipping_amt;
            $saleDetail[$key]['return_amt'] = $return_amt;
            $saleDetail[$key]['net_amt'] = $net_amt;
            $saleDetail[$key]['tax_collected_amt'] = $sale['Sale']['sales_tax_amt'];
        }
        return $saleDetail;
    }

    public function summary($year, $month, $category){
        $this->detail($year, $month);
        unset($this->paginationSettings['Brand']);
        $sales = $this->find(
            'all',
            $this->paginationSettings
        );

        $saleSummary = array();
        $saleMonths = $this->saleMonths();
        foreach($sales as $sale){
            $listing = $sale['Listing'][0]['Listing'];
            $sku = $listing['Sku'];
            $category_nm = $listing['Sku']['Item']['Category']['name'];
            $category_id = $listing['Sku']['Item']['Category']['id'];

            if($category_id <> $category and $category){
                continue;
            }

            $year = $sale['Sale']['sale_year'];
            $month = $saleMonths[$sale['Sale']['sale_month']];

            // Get Valueadd costs
            $valueadds = $this->Listing->Sku->Valueadd->allForSku($listing['sku_id']);
            $valueadd_amt = 0;
            foreach($valueadds as $valueadd){
                $valueadd_amt += $valueadd['ComponentSku']['per_unit_price_amt'];
            }

            $shipping_amt = 0;
            foreach($sale['Shipment'] as $shipment){
                $shipping_amt += $shipment['shipping_amt'];
            }

            $list_fee_amt = $listing['list_fee_amt'] * $sale['Sale']['quantity'] / $listing['quantity'];
            $return_amt = 0;
            if(isset($sale['SaleReturn'][0]['SaleReturn']['refund_amt'])){
                $return_amt = $sale['SaleReturn'][0]['SaleReturn']['refund_amt'] -
                    $sale['SaleReturn'][0]['SaleReturn']['refund_credit_amt'];
                $sku['per_unit_price_amt'] = 0;
                $valueadd_amt = 0;
            }

            $cost_amt = $sku['per_unit_price_amt'];
            $collected_amt = $sale['Sale']['sale_amt'] + $sale['Sale']['shipping_amt']; // + $sale['Sale']['sales_tax_amt'];
            $fee_amt = $sale['Sale']['fv_fee_amt'] + $sale['Sale']['paypal_fee_amt'] + $sale['Sale']['other_amt'] + $list_fee_amt;
            $net_amt = $collected_amt - $cost_amt - $valueadd_amt - $fee_amt - $list_fee_amt - $shipping_amt - $return_amt;

            if(!isset($saleSummary[$year][$month][$category_nm])){
                $saleSummary[$year][$month][$category_nm] = array(
                    'quantity' => 0,
                    'collected_amt' => 0,
                    'cost_amt' => 0,
                    'valueadd_amt' => 0,
                    //'list_fee_amt' => 0,
                    'fee_amt' => 0,
                    'shipping_amt' => 0,
                    'return_amt' => 0,
                    'net_amt' => 0,
                    'tax_collected_amt' => 0
                );
            }

            $saleSummary[$year][$month][$category_nm]['quantity'] += $sale['Sale']['quantity'];
            $saleSummary[$year][$month][$category_nm]['collected_amt'] += $collected_amt;
            $saleSummary[$year][$month][$category_nm]['cost_amt'] += $cost_amt;
            $saleSummary[$year][$month][$category_nm]['valueadd_amt'] += $valueadd_amt;
            //$saleSummary[$year][$month][$category_nm]['list_fee_amt'] += $list_fee_amt;
            $saleSummary[$year][$month][$category_nm]['fee_amt'] += $fee_amt;
            $saleSummary[$year][$month][$category_nm]['shipping_amt'] += $shipping_amt;
            $saleSummary[$year][$month][$category_nm]['return_amt'] += $return_amt;
            $saleSummary[$year][$month][$category_nm]['net_amt'] += $net_amt;
            $saleSummary[$year][$month][$category_nm]['tax_collected_amt'] += $sale['Sale']['sales_tax_amt'];
        }
        return $saleSummary;
    }

    public function saleYears(){
        $sale_years = array();
        for($i = 2012; $i <= date('Y'); $i++){
            $sale_years[$i] = $i;
        }

        return $sale_years;
    }

    public function saleMonths(){
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