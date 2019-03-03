<?php

class ShipmentController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Shipment');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Shipment->search($this->request->data['Shipment']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Shipment->paginationSettings();
        $this->set('shipments', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $shipment = array();
        if(!$id){
            // Assume add
            if(!isset($this->request->params['named']['sale'])){
                return false;
            }

            $saleId = $this->request->params['named']['sale'];
            $sale = $this->Shipment->Sale->checkIt($saleId);
            if($this->request->is(array('post'))){
                $goodToSave = true;                
            }
        }
        else{
            $shipment = $this->Shipment->checkIt($id);
            $this->set('shipment', $shipment);
            $sale = array(
                'Sale' => $shipment['Sale'][0]['Sale'],
                'Listing' => $shipment['Sale'][0]['Sale']['Listing'],
                'Customer' => $shipment['Sale'][0]['Sale']['Customer']
            );

            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Shipment->title,
                $this->Shipment->saveIt($this->request->data),
                array(
                    'controller' => 'shipment',
                    'action' => 'index'
                )
            );

        }

        if(!$this->request->data){
            $this->request->data = $shipment;
        }

        $this->set('operation', $operation);
        $this->set('sale', $sale);
        $this->set('carriers', $this->Shipment->Carrier->fullList());
    }

    public function del($id) {
        $this->Shipment->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Shipment->title,
                $this->Shipment->deleteIt($id),
                array(
                    'controller' => 'shipment',
                    'action' => 'index'
                )
            );
        }
    }

    public function uploadLabel($id){
        $shipment = $this->Shipment->checkIt($id);
        $this->set('shipment', $shipment);
        if($this->request->is(array('post', 'put'))){
            $this->Shipment->id = $id;
            if($this->request->data){
                $args = $this->Shipment->uploadArgs(
                    $this->request->data['Shipment']['file_label']['name'],
                    $this->request->data['Shipment']['file_label']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Shipment' => array(
                            'id' => $id,
                            'file_label' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Shipment->title,
                        $this->Shipment->saveIt($data),
                        array(
                            'controller' => 'shipment',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $shipment;
        }
    }

    function removeLabel($id){
        $shipment = $this->Shipment->checkIt($id);
        if($shipment['Shipment']['file_label']){
            $args = array(
                'fileName' => basename($shipment['Shipment']['file_label']),
                'folderName' => 'shipment',
                'nonImg' => true
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Shipment' => array(
                        'file_label' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Shipment->title,
                    $this->Shipment->saveIt($data, false),
                    array(
                        'controller' => 'shipment',
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