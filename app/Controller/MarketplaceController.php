<?php

class MarketplaceController extends AppController{
    public $helpers = array('myForm', 'myHtml');
    public $uses = array('Marketplace');

    public function index(){
        $this->Paginator->settings = $this->Marketplace->paginationSettings();
        $this->set('marketplaces', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $marketplace = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $marketplace = $this->Marketplace->checkIt($id);
            $this->set('marketplace', $marketplace);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Marketplace->title,
                $this->Marketplace->saveIt($this->request->data),
                array(
                    'controller' => 'marketplace',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $marketplace;
        }

        $this->set('operation', $operation);
    }

    public function del($id) {
        $this->Marketplace->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Marketplace->title,
                $this->Marketplace->deleteIt($id),
                array(
                    'controller' => 'marketplace',
                    'action' => 'index'
                )
            );
        }
    }

    public function upload($id){
        $marketplace = $this->Marketplace->checkIt($id);
        $this->set('marketplace', $marketplace);
        if($this->request->is(array('post', 'put'))){
            $this->Marketplace->id = $id;
            if($this->request->data){
                $args = $this->Marketplace->uploadArgs(
                    $this->request->data['Marketplace']['logo']['name'],
                    $this->request->data['Marketplace']['logo']['tmp_name']
                );

                if($this->FileOp->uploadFile($args)){
                    $data = array(
                        'Marketplace' => array(
                            'id' => $id,
                            'logo' => $args['destFileName']
                        )
                    );

                    $this->saveSetFlash(
                        $this->Marketplace->title,
                        $this->Marketplace->saveIt($data),
                        array(
                            'controller' => 'marketplace',
                            'action' => 'index'
                        )
                    );
                }
            }
        }

        if (!$this->request->data) {
            $this->request->data = $marketplace;
        }
    }

    function removeLogo($id){
        $marketplace = $this->Marketplace->checkIt($id);
        if($marketplace['Marketplace']['logo']){
            $args = array(
                'fileName' => basename($marketplace['Marketplace']['logo']),
                'folderName' => 'logos' . Configure::read('directorySeparator') . 'marketplace'
            );

            if($this->FileOp->deleteFile($args)){
                $data = array(
                    'Marketplace' => array(
                        'logo' => null,
                        'id' => $id
                    )
                );

                $this->saveSetFlash(
                    $this->Marketplace->title,
                    $this->Marketplace->saveIt($data, false),
                    array(
                        'controller' => 'marketplace',
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