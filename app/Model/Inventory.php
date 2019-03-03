<?php

class Inventory extends AppModel{
    public $useTable = 'v_inventory';
    public $virtualFields = array(
        'amount_avail' => "Inventory.quantity_avail*Inventory.per_unit_price_amt"
    );
    public $belongsTo =array(
        'Sku'
    );

    private $contain = array(
        'Sku' => array(
            'Item' => array(
                'Category' => array(
                    'fields' => array(
                        'id',
                        'name'
                    )
                ),
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
        )
    );

    private $paginationSettings = array();

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'fields' => $this->alias . '.snapshot_dt,'  .
                    'sum(' . $this->virtualFields['amount_avail'] . ')',
                'group' => $this->alias . '.snapshot_dt',
                'limit' => -1
            );
        }

        return $this->paginationSettings;
    }

    public function totalLastXDays($x = 7){
        return $this->find(
            'all',
            array(
                'fields' => $this->alias . '.snapshot_dt,'  .
                    'sum(' . $this->virtualFields['amount_avail'] . '+' . $this->alias . '.valueadds_amt) as totalInvValue,' .
                    'sum(' . $this->alias . '.quantity_avail) as totalInvUnits',
                'group' => $this->alias . '.snapshot_dt having ' . $this->alias . ".snapshot_dt > date_add(curdate(),INTERVAL - $x day)",
                'limit' => -1
            )
        );
    }

    public function totalMonthly(){
        return $this->find(
            'all',
            array(
                'fields' => $this->alias . '.snapshot_dt,'  .
                    $this->alias . '.is_month_end,'  .
                    'sum(' . $this->virtualFields['amount_avail'] . '+' . $this->alias . '.valueadds_amt) as totalInvValue,' .
                    'sum(' . $this->alias . '.quantity_avail) as totalInvUnits',
                'group' => $this->alias . '.snapshot_dt having ' . $this->alias . '.is_month_end = 1',
                'limit' => -1
            )
        );
    }

    public function generateTodaysSnapshot(){
        $this->query('CALL inventory_daily_snapshot()');
    }
}