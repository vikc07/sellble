<?php

class CustomerController extends AppController{
    public $helpers = array('Html', 'Form', 'myHtml', 'Paginator');
    public $uses = array('Customer');
    public $connected = false;

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Customer->search($this->request->data['Customer']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Customer->paginationSettings();
        $this->set('customers', $this->Paginator->paginate());
        $this->set('states', $this->Customer->BillingUsState->fullList());
    }

    public function map(){
        $this->Customer->geocode();
        $this->set('states', $this->Customer->customersByBillState());
        $this->set('geocodes', $this->Customer->Geocode->find('all'));
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $customer = array();
        if(!$id){
            // Assume add
            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $customer = $this->Customer->checkIt($id);
            $this->set('customer', $customer);
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Customer->title,
                $this->Customer->saveIt($this->request->data),
                array(
                    'controller' => 'customer',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $customer;
        }

        $this->set('operation', $operation);
        $this->set('customer', $customer);

        $usStates = $this->Customer->UsState->fullList();
        $this->set('billUsStates',$usStates);
        $this->set('shipUsStates',$usStates);
    }

    public function del($id) {
        $this->Customer->checkIt($id);
        if($this->request->is(array('post', 'put'))){
            $this->delSetFlash(
                $this->Customer->title,
                $this->Customer->deleteIt($id),
                array(
                    'controller' => 'customer',
                    'action' => 'index'
                )
            );
        }
    }
}