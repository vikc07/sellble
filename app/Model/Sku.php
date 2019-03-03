<?php
class Sku extends AppModel{
    public $title = 'SKU';
    private $paginationSettings = array();
    private $contain = array(
        'PurchaseLine' => array(
            'Purchase' => array(
                'id',
                'idFormatted'
            ),
            'fields' => array(
                'id',
                'purchase_id',
                'quantity'
            )
        ),
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
            ),
            'ItemPhoto'
        ),
        'Listing' => array(
            'fields' => array(
                'id',
                'has_ended',
                'idFormatted'
            )
        ),
        'Valueadd' => array(
            'fields' => array(
                'id'
            )
        ),
    );

    public $do_not_delete = false;
    public $virtualFields = array(
        'idFormatted' => "concat('SK',lpad(Sku.id,8,'0'))"
    );

    public $belongsTo = array(
        'PurchaseLine',
        'Item'
    );

    public $hasMany = array(
        'Listing' => array(
            'foreignKey' => 'sku_id'
        ),
        'Valueadd' => array(
            'foreignKey' => 'sku_id'
        ),
        'ExcludedSku' => array(
            'foreignKey' => 'sku_id'
        )
    );

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'order' => $this->alias . '.id desc',
                'conditions' => array(
                    $this->alias . '.quantity_avail >' => 0
                ),
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function findIdByPurchaseLineId($purchaseLineId){
        if(!$purchaseLineId){
            return false;
        }

        $sku = $this->find(
            'first',
            array(
                'conditions' => array(
                    'purchase_line_id' => $purchaseLineId
                )
            )
        );

        if($sku){
            return $sku['Sku']['id'];
        }

        return false;
    }

    public function findItemId($skuId){
        if(!$skuId){
            return false;
        }

        $skus = $this->find('first', array('conditions' => array('Sku.id' => $skuId)));
        if($skus){
            return $skus['Sku']['item_id'];
        }

        return false;
    }

    public function increaseQty($id, $by){
        if(!$id or !$by or $by < 0){
            return false;
        }
        $sku = $this->findById($id);
        if($sku){
            $existingQty = $sku['Sku']['quantity_avail'];
            $this->id = $id;
            return $this->saveField('quantity_avail', $by + $existingQty);
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

    public function findQty($id){
        if(!$id){
            return false;
        }
        $sku = $this->findById($id);
        if($sku){
            return $sku['Sku']['quantity_avail'];
        }

        return false;
    }

    public function decreaseQty($id, $by){
        if(!$id or !$by or $by < 0){
            return false;
        }
        $sku = $this->findById($id);
        if($sku){
            $existingQty = $sku['Sku']['quantity_avail'];
            if(($existingQty - $by) >= 0){
                $this->id = $id;
                return $this->saveField('quantity_avail', $existingQty - $by);
            }
        }

        return false;
    }

    public function findListAvailComponents($exclude = ''){
        $componentCategories = $this->Item->Category->find(
            'list',
            array(
                'fields' => $this->Item->Category->alias . '.id',
                'conditions' => array('is_component' => true),
            )
        );

        // Now find all items that belong to above categories
        $items = $this->Item->find(
            'list',
            array(
                'conditions' => array(
                    $this->Item->alias . '.category_id' => $componentCategories
                )
            )
        );

        $skus = $this->find(
            'all',
            array(
                'contain' => array(
                    'Item' => array(
                        'fields' => array(
                            'id',
                            'fullName'
                        ),
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
                ),
                'conditions' => array(
                    $this->alias . '.item_id' => array_keys($items),
                    'OR' => array(
                        $this->alias .'.quantity_avail >' => 0,
                        $this->alias .'.id' => $exclude
                    )
                )
            )
        );

        $skus_list = array();
        foreach($skus as $sku){
            $brand = $sku['Item']['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
            $item = $manufacturer['name'] . ' - ' . $brand['name'] . ' - ' . $sku['Item']['fullName'];
            $skus_list[$sku['Sku']['id']] = $item .
                ' - ' . $sku['Sku']['idFormatted'] .
                ' - Avail: ' . $sku['Sku']['quantity_avail'] .
                ' Per Unit: ' .  $sku['Sku']['per_unit_price_amt'];
        }
        asort($skus_list);
        return $skus_list;
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

        $this->do_not_delete = $record['Sku']['do_not_delete'];

        return $record;
    }

    public function deleteIt($id = null){
        if($this->do_not_delete){
            return false;
        }

        return $this->delete($id);
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

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

        if($terms['q']){
            $this->paginationSettings['conditions'] = array(
                'or' => array(
                    $this->virtualFields['idFormatted'] . ' like ' => '%' . $terms['q'] . '%'
                )
            );
        }

        if(isset($terms['show_only_avail']) and $terms['show_only_avail']){
            $this->paginationSettings['conditions']['or']['and'][$this->alias . '.quantity_avail >'] = 0;
        }

        $this->paginationSettings['limit'] = -1;

        // Check Purchase
        $purchasePaginationSettings = $this->PurchaseLine->search($terms);
        if(isset($purchasePaginationSettings['conditions'])){
            $purchaseLineIds = $this->PurchaseLine->find(
                'list',
                array(
                    'fields' => array(
                        $this->PurchaseLine->alias . '.id'
                    ),
                    'conditions' => $purchasePaginationSettings['conditions']
                )
            );

            if(!empty($purchaseLineIds)){
                $this->paginationSettings['conditions']['or']['and'][$this->alias . '.purchase_line_id'] = $purchaseLineIds;
            }
        }
        return $this->paginationSettings;
    }
}