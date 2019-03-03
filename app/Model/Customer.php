<?php
class Customer extends AppModel{
    public $title = 'Customer';
    public $virtualFields = array(
        'idFormatted' => "concat('C',lpad(Customer.id,8,'0'))",
        'listName' => "concat(Customer.bill_name,' - ',Customer.email,' - ',Customer.ebay_id,' - ',concat('C',lpad(Customer.id,8,'0')))"
    );
    private $paginationSettings = array();

    public $validate = array(
        'bill_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Name cannot be blank'
            )
        ),
        'bill_address_line_1' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Address Line 1 cannot be blank'
            )
        ),
        'bill_city' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'City cannot be blank'
            )
        ),
        'bill_us_state_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'State cannot be blank'
            )
        ),
        'bill_zip' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Zip cannot be blank'
            )
        ),
        'bill_country' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Country cannot be blank'
            )
        )
    );

    public $belongsTo = array(
        'UsState' => array(
            'className' => 'UsState',
            'foreignKey' => 'bill_us_state_id'
        ),
        'BillingUsState' => array(
            'className' => 'UsState',
            'foreignKey' => 'bill_us_state_id'
        ),
        'ShippingUsState' => array(
            'className' => 'UsState',
            'foreignKey' => 'ship_us_state_id'
        ),
        'Geocode' => array(
            'foreignKey' => 'bill_zip'
        )
    );

    public $displayField = 'listName';

    public function getIdFromIdFormatted($idFormatted){
        return ltrim(str_replace('C', '', $idFormatted), '0');
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => array(
                    'BillingUsState',
                    'ShippingUsState'
                ),
                'limit' => 50,
                'order' => $this->alias . '.id'
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

        $searchCondition  = "lower(concat_ws(''," .  $this->virtualFields['idFormatted'] . ",bill_name,email,ebay_id,bill_address_line_1,bill_address_line_2,";
        $searchCondition .= 'bill_city,bill_country,bill_zip,ship_name,ship_address_line_1,';
        $searchCondition .= 'ship_address_line_2,ship_city,ship_country,ship_zip,ship_country)) like ';

        $this->paginationSettings['conditions'] = array(
            'and' => array(
                $searchCondition => '%' . $terms['q'] . '%',
            )
        );

        if(isset($terms['state']) and $terms['state']){
            $this->paginationSettings['conditions']['and']['or'] =  array(
                $this->alias . '.bill_us_state_id' => $terms['state'],
                $this->alias . '.ship_us_state_id' => $terms['state']
            );
        }

        return $this->paginationSettings;
    }

    public function saveIt($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        // Check email or ebay id and set to default
        if($data[$this->alias]['email'] == ''){
            $data[$this->alias]['email'] = 'noemail@domain.com';
        }

        if($data[$this->alias]['ebay_id'] == ''){
            $data[$this->alias]['ebay_id'] = 'not_provided';
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

        $record = $this->find(
            'first',
            array(
                'contain' => array(
                    'BillingUsState',
                    'ShippingUsState'
                ),
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

    public function fullList(){
        return $this->find('list');
    }

    public function customersByBillState(){
        return $this->find(
            'all',
            array(
                'fields' => array(
                    'count(*) as customerCount'
                ),
                'contain' => array(
                    'BillingUsState' => array(
                        'fields' => array(
                            'full_nm'
                        )
                    )
                ),
                'group' => array(
                    'BillingUsState.name'
                ),
                'order' => array(
                    'customerCount' => 'desc'
                )
            )
        );
    }

    public function geocode(){
        $customers = $this->find(
            'all'
        );

        foreach($customers as $customer){
            if($customer['Customer']['bill_zip']){
                if($this->Geocode->checkIt($customer['Customer']['bill_zip'])){

                }
                else{
                    $this->Geocode->codify($customer['Customer']['bill_zip']);
                }
            }
        }
    }
}