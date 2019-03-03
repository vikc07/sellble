<?php
class Valueadd extends AppModel{
    public $title = 'Value Add';
    private $paginationSettings = array();
    private $contain = array(
        'Enhancement' => array(
            'fields' => array(
                'id',
                'name'
            )
        ),
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
        ),
        'ComponentSku' => array(
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
        ),
    );

    public $virtualFields = array(
        'idFormattedSku' => "concat('SK',lpad(Valueadd.sku_id,8,'0'))",
        'idFormattedComponentSku' => "concat('SK',lpad(Valueadd.component_id,8,'0'))"
    );

    public $belongsTo = array(
        'Enhancement',
        'Sku',
        'ComponentSku' => array(
            'className' => 'Sku',
            'foreignKey' => 'component_id'
        )
    );

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'order' => $this->alias . '.sku_id desc,' . $this->alias . '.id desc',
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

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
                $this->paginationSettings['conditions']['and']['or'][$this->alias . '.component_id'] = array_values($skuIds);
            }
        }

        return $this->paginationSettings;
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

        $this->id = $record[$this->alias]['id'];

        return $record;
    }

    public function saveIt($data = null, $validate = true, $fieldList = null){
        if(!$data){
            return false;
        }

        if(!isset($data[$this->alias]['id'])){
            $this->create();
            if($this->save($data)){
                $this->Sku->setDoNotDelete($data['Valueadd']['sku_id']);
                // Is there any component set?
                if($data['Valueadd']['component_id']){
                    // Decrease Component Qty
                    if($this->Sku->decreaseQty($data['Valueadd']['component_id'], 1)){
                        $this->Sku->setDoNotDelete($data['Valueadd']['component_id']);
                        return true;
                    }
                }
                return true;
            }
        }
        else{
            $valueadd = $this->checkIt($this->id);
            if($this->save($data)){
                $old_component_id = $valueadd['Valueadd']['component_id'];
                $new_component_id = $data['Valueadd']['component_id'];
                if($new_component_id and $old_component_id <> $new_component_id){
                    // Decrease Component Qty
                    if($this->Sku->decreaseQty($new_component_id, 1)){
                        $this->Sku->setDoNotDelete($new_component_id);
                        //Was there any component set previously?
                        if($old_component_id){
                            //Increase Qty for that
                            return $this->Sku->increaseQty($old_component_id, 1);
                        }
                        return true;
                    }
                }

                return true;
            }
        }

        return false;
    }

    public function deleteIt($id){
        $valueadd = $this->checkIt($id);
        if($this->delete($id)){
            if($valueadd['Valueadd']['component_id']){
                // Increase Component Qty
                return $this->Sku->increaseQty($valueadd['Valueadd']['component_id'], 1);
            }
            return true;
        }
        return false;
    }

    public function allForSku($skuId){
        if(!$skuId){
            return false;
        }

        return $this->find(
            'all',
            array(
                'contain' => $this->contain,
                'conditions' => array(
                    'sku_id' => $skuId
                )
            )
        );
    }

    public function totalForSku($skuId){
        $valueAdds = $this->allForSku($skuId);
        $total = array(
            'count' => 0,
            'amount' => 0
        );
        if($valueAdds){
            debug($valueAdds);
            foreach($valueAdds as $valueadd){
            }
        }
    }
}