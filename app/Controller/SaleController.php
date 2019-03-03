<?php

class SaleController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Sale');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Sale->search($this->request->data['Sale']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Sale->paginationSettings();
        $this->set('sales', $this->Paginator->paginate());
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $sale = array();
        $new_customer = false;
        if(!$id){
            // Assume add
            if(!isset($this->request->params['named']['listing'])){
                return false;
            }

            $listingId = $this->request->params['named']['listing'];
            $listing = $this->Sale->Listing->checkIt($listingId);

            if($this->request->is(array('post'))){
                if(isset($this->request->data['Sale']['customer_typeahead'])){
                    $customer_typeahead = $this->request->data['Sale']['customer_typeahead'];
                    unset($this->request->data['Sale']['customer_typeahead']);
                    $customerIdFormatted = explode(' - ',$customer_typeahead);
                    $customerIdFormatted = array_pop($customerIdFormatted);
                    $this->request->data['Sale']['customer_id'] = $this->Sale->Customer->getIdFromIdFormatted($customerIdFormatted);
                    unset($this->request->data['Customer']);
                }
                else{
                    $new_customer = true;
                }

                $goodToSave = true;
            }
        }
        else{
            $sale = $this->Sale->checkIt($id);
            $this->set('sale', $sale);
            $listing = array(
                'Listing' => $sale['Listing'][0]['Listing'],
                'Sku' => $sale['Listing'][0]['Listing']['Sku']
            );

            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Sale->title,
                $this->Sale->saveItAll($this->request->data),
                array(
                    'controller' => 'sale',
                    'action' => 'index'
                )
            );
            //debug($this->request->data);

        }

        $customers = $this->Sale->Customer->fullList();
        if(!$this->request->data){
            if(isset($sale) and !empty($sale)){
               $sale['Sale']['customer_typeahead'] = $customers[$sale['Sale']['customer_id']];
            }

            $this->request->data = $sale;
        }

        $this->set('operation', $operation);
        $this->set('customers', $customers);
        $this->set(
            'ebay_fee_percent',
            $this->Sale->Listing->Sku->Item->Category->getEbayFee(
                $this->Sale->Listing->Sku->Item->findCategoryId(
                    $listing['Sku']['item_id']
                )
            )
        );

        $us_states = $this->Sale->Customer->UsState->fullList();
        $this->set('billUsStates', $us_states);
        $this->set('shipUsStates', $us_states);
        $this->set('new_customer', $new_customer);
        $this->set('listing', $listing);
    }

    public function del($id) {
        $sale = $this->Sale->checkIt($id);
        $doNotDelete = false;
        if($sale['Sale']['do_not_delete']){
            $doNotDelete = true;
        }
        $this->set('doNotDelete', $doNotDelete);

        if($this->request->is(array('post', 'put')) and !$doNotDelete){
            $this->delSetFlash(
                $this->Sale->title,
                $this->Sale->deleteIt($id),
                array(
                    'controller' => 'sale',
                    'action' => 'index'
                )
            );
        }
    }

    public function detail(){
        $year = date('Y');
        $month = date('n');
        if($this->request->is('post', 'put')){
            $year = $this->request->data['Sale']['sale_year'];
            $month = $this->request->data['Sale']['sale_month'];
        }

        $this->set('sales', $this->Sale->detail($year, $month));
        $this->set('sale_years', $this->Sale->saleYears());
        $this->set('sale_months', $this->Sale->saleMonths());
    }

    public function summary(){
        $year = date('Y');
        $month = date('n');
        $category = false;
        if($this->request->is('post', 'put')){
            $year = $this->request->data['Sale']['sale_year'];
            $month = $this->request->data['Sale']['sale_month'];
            $category = $this->request->data['Sale']['category_id'];
        }

        $this->set('sales', $this->Sale->summary($year, $month, $category));
        $this->set('sale_years', $this->Sale->saleYears());
        $this->set('sale_months', $this->Sale->saleMonths());
        $this->set('categories', $this->Sale->Listing->Sku->Item->Category->fullList());
    }
}