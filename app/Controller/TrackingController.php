<?php

class TrackingController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Tracking');

    public function index(){
        $items = $this->Tracking->PurchaseLine->Item->fullList();

        $this->Paginator->settings = $this->Tracking->paginationSettings();
        $trackings = $this->Paginator->paginate();

        $qry_again = 0;
        foreach($trackings as $key => $tracking){
            if(!$tracking['Tracking']['delivered_flg'] and $tracking['Tracking']['age'] > Configure::read('TrackingDelay')){
                $qry_again = true;
                $track = array(
                    'eta' => '',
                    'delivered_flg' => 0,
                    'delivered_on_dt' => ''
                );
                if($tracking['Carrier'][0]['Carrier']['name'] == 'USPS'){
                    $track = $this->ShipmentTracking->usps($tracking['Tracking']['tracking_no']);
                }
                else if($tracking['Carrier'][0]['Carrier']['name'] == 'UPS'){
                    $mailInnovations = 0;
                    if($tracking['Carrier'][0]['Carrier']['service'] == "Mail Innovations"){
                        $mailInnovations = true;
                    }
                    $track = $this->ShipmentTracking->ups($tracking['Tracking']['tracking_no'], $mailInnovations);
                }
                else if($tracking['Carrier'][0]['Carrier']['name'] == 'FedEx'){
                    $track = $this->ShipmentTracking->fedex($tracking['Tracking']['tracking_no']);
                }

                $trackings[$key]['Tracking']['eta'] = $track['eta'];
                $trackings[$key]['Tracking']['delivered_flg'] = $track['delivered_flg'];
                if($track['delivered_flg']){
                    $trackings[$key]['Tracking']['delivered_on_dt'] = $track['delivered_on_dt'];
                }

                // Save
                $this->Tracking->saveIt(
                    array(
                        'Tracking' => array(
                            'id' => $tracking['Tracking']['id'],
                            'eta' => ($track['eta'])?date('Y-m-d', $track['eta']):'',
                            'delivered_flg' => $track['delivered_flg'],
                            'delivered_on_dt' => ($track['delivered_on_dt'])?date('Y-m-d', $track['delivered_on_dt']):''
                        )
                    )
                );
            }
        }
        if($qry_again){
            $trackings = $this->Paginator->paginate();
        }

        $this->set('trackings', $trackings);
        $this->set('items', $items);
    }

    function mark_delivered($id = null){
        $tracking = $this->Tracking->checkIt($id);
        $data = array(
            $this->Tracking->alias => array(
                'id' => $id,
                'delivered_flg' => true,
                'delivered_on_dt' => date('Y-m-d')
            )
        );

        $this->saveSetFlash(
            $this->Tracking->title,
            $this->Tracking->saveIt($data),
            array(
                'controller' => 'tracking',
                'action' => 'index'
            )
        );
    }
}