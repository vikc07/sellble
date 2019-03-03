<?php

class ManufacturerController extends AppController{
	public $helpers = array('myForm', 'myHtml');
	public $uses = array('Manufacturer');

    public function index(){
        $this->Paginator->settings = $this->Manufacturer->paginationSettings();
        $this->set('manufacturers', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $manufacturer = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $manufacturer = $this->Manufacturer->checkIt($id);
            $this->set('manufacturer', $manufacturer);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Manufacturer->title,
                $this->Manufacturer->saveIt($this->request->data),
                array(
                    'controller' => 'manufacturer',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $manufacturer;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->Manufacturer->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Manufacturer->title,
                $this->Manufacturer->deleteIt($id),
                array(
                    'controller' => 'manufacturer',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $manufacturer = $this->Manufacturer->checkIt($id);
        $this->set('manufacturer', $manufacturer);
        if($this->request->is(array('post', 'put'))){
            $this->Manufacturer->id = $id;
            if($this->request->data){
                $args = $this->Manufacturer->uploadArgs(
                    $this->request->data['Manufacturer']['logo']['name'],
                    $this->request->data['Manufacturer']['logo']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Manufacturer' => array(
                            'id' => $id,
                            'logo' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Manufacturer->title,
                        $this->Manufacturer->saveIt($data),
                        array(
                            'controller' => 'manufacturer',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $manufacturer;
        }
    }

    function removeLogo($id){
        $manufacturer = $this->Manufacturer->checkIt($id);
        if($manufacturer['Manufacturer']['logo']){
            $args = array(
                'fileName' => basename($manufacturer['Manufacturer']['logo']),
                'folderName' => 'logos' . Configure::read('directorySeparator') . 'manufacturer'
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Manufacturer' => array(
                        'logo' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Manufacturer->title,
                    $this->Manufacturer->saveIt($data, false),
                    array(
                        'controller' => 'manufacturer',
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