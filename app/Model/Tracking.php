<?php
class Tracking extends AppModel{
    public $title = 'Tracking Info';
    private $paginationSettings = array();
    public $virtualFields = array(
        'age' => '(now() - Tracking.modified)/60'
    );

    public $belongsTo = array(
        'Shipment',
        'PurchaseLine',
        'Carrier',
        'SaleReturn'
    );

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

    public function checkItByPurchaseLineId($purchaseLineId){
        if(!$purchaseLineId){
            return false;
        }

        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'purchase_line_id' => $purchaseLineId
                )
            )
        );
    }

    public function checkItByShipmentId($shipmentId){
        if(!$shipmentId){
            return false;
        }

        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'shipment_id' => $shipmentId
                )
            )
        );
    }

    public function checkItBySaleReturnId($returnId){
        if(!$returnId){
            return false;
        }

        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'sale_return_id' => $returnId
                )
            )
        );
    }

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => array(
                    'Carrier',
                    'Shipment' => array(
                        'Sale' => array(
                            'Listing' => array(
                                'Marketplace',
                                'Sku'
                            ),
                            'fields' => array(
                                'id'
                            )
                        )
                    ),
                    'PurchaseLine' => array(
                        'Purchase' => array(
                            'Marketplace'
                        )
                    ),
                    'SaleReturn' => array(
                        'Sale' => array(
                            'Listing' => array(
                                'Marketplace'
                            ),
                            'fields' => array(
                                'id'
                            )
                        )
                    )
                ),
                'order' => $this->alias . '.created desc,' . $this->alias . '.delivered_flg asc, ' . $this->alias . '.eta desc',
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function deleteIt($id){
        return $this->delete($id);
    }

    public function checkIt($id = null){
        if(!$id){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'conditions' => array(
                    $this->alias . '.id' => $id
                )
            )
        );

        if(!$record){
            throw new NotFoundException(__('Invalid ' . $this->title));
        }

        $this->id = $record[$this->alias]['id'];

        return $record;
    }
}