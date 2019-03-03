<?php
class ExcludedSku extends AppModel{
    public $virtualFields = array(
        'exclusion_year' => 'year(ExcludedSku.created)',
        'idFormattedSku' => "concat('SK',lpad(ExcludedSku.sku_id,8,'0'))"
    );
    public $title = 'Exclusion';
    private $paginationSettings = array();
    private $contain = array(
        'ExcludeReason' => array(
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
        )
    );

    public $belongsTo = array(
        'ExcludeReason',
        'Sku'
    );

    public function paginationSettings(){
        if(empty($this->paginationSettings)){
            $this->paginationSettings = array(
                'contain' => $this->contain,
                'order' => $this->alias . '.created desc,' . $this->alias . '.id desc',
                'conditions' => array(
                    'and' => array(
                        $this->alias . '.exclusion_year' => date('Y')
                    )
                ),
                'limit' => 50
            );
        }

        return $this->paginationSettings;
    }

    public function search($terms){
        $terms['q'] = strtolower(trim($terms['q']));
        $this->paginationSettings();

        $this->paginationSettings['limit'] = -1;

        if($terms['exclusion_year']){
            $this->paginationSettings['conditions'] = array(
                'and' => array(
                    $this->alias . '.exclusion_year' => $terms['exclusion_year']
                )
            );
        }

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
                $this->paginationSettings['conditions']['and'][$this->alias . '.sku_id'] = array_values($skuIds);
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
            // Only insert allowed
            $this->create();
            if($this->save($data)){
                $this->Sku->setDoNotDelete($data[$this->alias]['sku_id']);
                // Decrease Sku Qty
                if($data[$this->alias]['quantity']){
                    return $this->Sku->decreaseQty($data[$this->alias]['sku_id'], $data[$this->alias]['quantity']);
                }
            }
            return true;
        }
        else{
            return $this->save($data);
        }

        return false;
    }

    public function deleteIt($id){
        $exclusion = $this->checkIt($id);
        if($this->delete($id)){
            // Increase Component Qty
            if($exclusion[$this->alias]['quantity']){
                return $this->Sku->increaseQty($exclusion[$this->alias]['sku_id'], $exclusion[$this->alias]['quantity']);
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

    public function exclusionYears(){
        $years = array();
        for($i = 2012; $i <= date('Y'); $i++){
            $years[$i] = $i;
        }

        return $years;
    }
}