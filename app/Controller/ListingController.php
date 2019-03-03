<?php

class ListingController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Listing');

    public function index(){
        if($this->request->is('post', 'put')){
            $this->Listing->search($this->request->data['Listing']);
            if(isset($this->request->params['named']['page'])){
                unset($this->request->params['named']['page']);
            }
        }
        $this->Paginator->settings = $this->Listing->paginationSettings();
        $this->set('listings', $this->Paginator->paginate());
        $this->set('marketplaces', $this->Listing->Marketplace->fullList());

        $statuses = array('Active', 'Ended');
        $this->set('statuses', $statuses);
    }

    public function edit($id = null){
        $goodToSave = false;
        $operation = 'Add';
        $listing = array();
        if(!$id){
            // Assume add
            if(!isset($this->request->params['named']['sku'])){
                return false;
            }

            $skuId = $this->request->params['named']['sku'];
            $sku = $this->Listing->Sku->checkIt($skuId);

            if($this->request->is(array('post'))){
                $goodToSave = true;
            }
        }
        else{
            $listing = $this->Listing->checkIt($id);
            $this->set('listing', $listing);
            $sku = array(
                'Sku' => $listing['Sku'],
                'Item' => $listing['Sku']['Item']
            );
            if($this->request->is(array('post', 'put'))){
                $goodToSave = true;
            }
            $operation = 'Edit';
        }

        if($goodToSave){
            $this->saveSetFlash(
                $this->Listing->title,
                $this->Listing->saveIt($this->request->data),
                array(
                    'controller' => 'listing',
                    'action' => 'index'
                )
            );
        }

        if(!$this->request->data){
            $this->request->data = $listing;
        }

        $this->set('operation', $operation);
        $this->set('listing', $listing);
        $this->set('marketplaces', $this->Listing->Marketplace->fullList());
        $this->set('sku', $sku);
        $this->set('ebay_fee_percent',
            $this->Listing->Sku->Item->Category->getEbayFee(
                $this->Listing->Sku->Item->findCategoryId(
                    $sku['Sku']['item_id']
                )
            )
        );
    }

    public function ebay($id = null){
        $listing = $this->Listing->checkIt($id);
        $sku = array(
            'Sku' => $listing['Sku'],
            'Item' => $listing['Sku']['Item']
        );

        $ebayListing = array();

        if(isset($listing['EbayListing'][0])){
            $ebayListing = $listing['EbayListing'][0];
        }

        if($this->request->is(array('post', 'put'))){
            $this->saveSetFlash(
                $this->Listing->title,
                $this->Listing->saveEbay($this->request->data),
                array(
                    'controller' => 'listing',
                    'action' => 'index'
                )
            );
        }

        if (!$this->request->data) {
            $this->request->data = $ebayListing;
        }

        $this->set('sku', $sku);
        $this->set('listing', $listing);
    }

    public function del($id) {
        $listing = $this->Listing->checkIt($id);
        $doNotDelete = false;
        if($listing['Listing']['do_not_delete']){
            $doNotDelete = true;
        }
        $this->set('doNotDelete', $doNotDelete);

        if($this->request->is(array('post', 'put')) and !$doNotDelete){
            $this->delSetFlash(
                $this->Listing->title,
                $this->Listing->deleteIt($id),
                array(
                    'controller' => 'listing',
                    'action' => 'index'
                )
            );
        }
    }

    public function ebay_generate($id = null){
        $options = array(
            'Options' => array(
                'show_purple_shield' => true,
                'show_wysiwyg' => true,
                'show_tough_skin' => true,
                'show_hippo_verified' => true,
                'show_description' => true,
                'show_specs' => true,
                'show_photos' => true,
                'show_spec_logos' => true,
                'show_brand_logos' => true
            )
        );

        $listing = $this->Listing->ebayListing($id);
        $this->set('listing', $listing);

        if(!$this->request->data){
            $this->request->data = $options;
        }

        $this->set('options', $this->request->data);
        $this->layout = 'ebay';
    }
}