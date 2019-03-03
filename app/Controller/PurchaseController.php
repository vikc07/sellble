<?php

class PurchaseController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Purchase');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Purchase->search($this->request->data['Purchase']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Purchase->paginationSettings();
        $this->set('purchases', $this->Paginator->paginate());
        $this->set('purchaseStatuses', $this->Purchase->PurchaseStatus->fullList());
        $this->set('marketplaces', $this->Purchase->Marketplace->fullList());
    }
    
    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $purchase = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $purchase = $this->Purchase->checkIt($id);
            $this->set('purchase', $purchase);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Purchase->title,
                $this->Purchase->saveItAll($this->request->data),
                array(
                    'controller' => 'purchase',
                    'action' => 'index'
                )
            );

        }
        $items = $this->Purchase->PurchaseLine->Item->fullList();
        if(!$this->request->data){
            if(isset($purchase)){
                if(isset($purchase['PurchaseLine'])){
                    foreach($purchase['PurchaseLine'] as $key => $val){
                        $purchase['PurchaseLine'][$key]['item_typeahead'] = $items[$val['item_id']];
                    }
                }
                $this->request->data = $purchase;
            }
        }

        $this->set('operation', $operation);
        $this->set('items', $items);
        $this->set('purchaseStatuses', $this->Purchase->PurchaseStatus->fullList());
        $this->set('marketplaces', $this->Purchase->Marketplace->fullList());
    }

    public function del($id) {
        $purchase = $this->Purchase->checkIt($id);
        $doNotDelete = false;
        if($purchase['Purchase']['do_not_delete']){
            $doNotDelete = true;
        }
        $this->set('doNotDelete', $doNotDelete);

        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Purchase->title,
                $this->Purchase->deleteIt($id),
                array(
                    'controller' => 'purchase',
                    'action' => 'index'
                )
            );
        }
    }

    public function markShipped($id){
        $purchaseLine = $this->Purchase->PurchaseLine->checkIt($id);
        $this->set('purchase_line', $purchaseLine);

        if (!$this->request->data) {
            $this->request->data = $purchaseLine;
        }

        $this->set('carriers', $this->Purchase->PurchaseLine->Carrier->fullList());

        if($this->request->is(array('post', 'put'))){
            $this->saveSetFlash(
                $this->Purchase->title,
                $this->Purchase->PurchaseLine->markShipped($this->request->data),
                array(
                    'controller' => 'purchase',
                    'action' => 'index'
                )
            );
        }
    }

    public function removeShipping($id){
        $this->Purchase->PurchaseLine->checkIt($id);

        $this->saveSetFlash(
            $this->Purchase->title,
            $this->Purchase->PurchaseLine->removeShipping(),
            array(
                'controller' => 'purchase',
                'action' => 'index'
            )
        );
    }

    public function uploadInvoice($id){
        $purchase = $this->Purchase->checkIt($id);
        $this->set('purchase', $purchase);
        if($this->request->is(array('post', 'put'))){
            $this->Purchase->id = $id;
            if($this->request->data){
                $args = $this->Purchase->uploadArgs(
                    $this->request->data['Purchase']['file_invoice']['name'],
                    $this->request->data['Purchase']['file_invoice']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Purchase' => array(
                            'id' => $id,
                            'file_invoice' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Purchase->title,
                        $this->Purchase->saveIt($data),
                        array(
                            'controller' => 'purchase',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $purchase;
        }
    }

    function removeInvoice($id){
        $purchase = $this->Purchase->checkIt($id);
        if($purchase['Purchase']['file_invoice']){
            $args = array(
                'fileName' => basename($purchase['Purchase']['file_invoice']),
                'folderName' => 'purchase',
                'nonImg' => true
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Purchase' => array(
                        'file_invoice' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Purchase->title,
                    $this->Purchase->saveIt($data, false),
                    array(
                        'controller' => 'purchase',
                        'action' => 'index'
                    )
                );
            }
            else{
                $this->Session->setFlash(__('There was an error deleting invoice. Try again.'), 'myFlashError');
            }
        }
    }
}