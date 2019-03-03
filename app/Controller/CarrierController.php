<?php

class CarrierController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Carrier');

    public function index(){
        $this->Paginator->settings = $this->Carrier->paginationSettings();
        $this->set('carriers', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $carrier = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $carrier = $this->Carrier->checkIt($id);
            $this->set('carrier', $carrier);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Carrier->title,
                $this->Carrier->saveIt($this->request->data),
                array(
                    'controller' => 'carrier',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $carrier;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->Carrier->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Carrier->title,
                $this->Carrier->deleteIt($id),
                array(
                    'controller' => 'carrier',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $carrier = $this->Carrier->checkIt($id);
        $this->set('carrier', $carrier);
        if($this->request->is(array('post', 'put'))){
            $this->Carrier->id = $id;
            if($this->request->data){
                $args = $this->Carrier->uploadArgs(
                    $this->request->data['Carrier']['logo']['name'],
                    $this->request->data['Carrier']['logo']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Carrier' => array(
                            'id' => $id,
                            'logo' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Carrier->title,
                        $this->Carrier->saveIt($data),
                        array(
                            'controller' => 'carrier',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $carrier;
        }
    }

    function removeLogo($id){
        $carrier = $this->Carrier->checkIt($id);
        if($carrier['Carrier']['logo']){
            $args = array(
                'fileName' => basename($carrier['Carrier']['logo']),
                'folderName' => 'logos' . Configure::read('directorySeparator') . 'carrier'
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Carrier' => array(
                        'logo' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Carrier->title,
                    $this->Carrier->saveIt($data, false),
                    array(
                        'controller' => 'carrier',
                        'action' => 'index'
                    )
                );
            }
            else{
                $this->Session->setFlash(__('There was an error deleting Logo. Try again.'), 'myFlashError');
            }
        }
    }
}

?>