<?php

App::uses('Component', 'Controller');

class ShipmentTrackingComponent extends Component{
    private $status = array();

    private function clearStatus(){
        $this->status = array(
            'delivered_flg' => 0,
            'delivered_on_dt' => '',
            'eta' => ''
        );
    }

    public function ups($tracking_no = '1Z223A7R0368066891', $mailInnovations = 0){
        $this->clearStatus();
        $lib_dir = dirname(__FILE__) . "/tracking/ups/";
        $wsdl = $lib_dir . "Track.wsdl";
        try{
            //create soap
            $request = array(
                'Request'		=>	array(
                    'RequestOption'	=> "1"
                ),
                'InquiryNumber'	=>	$tracking_no,
                'TrackingOption'=>	($mailInnovations)?"03":"01"
            );

            $mode = array(
                'soap_version' 	=> 'SOAP_1_1',  // use soap 1.1 client
                'trace' 		=> 1
            );

            // initialize soap client
            $client = new SoapClient($wsdl, $mode);

            //set endpoint url
            $client->__setLocation("https://onlinetools.ups.com/webservices/Track");	// End point


            //create soap header
            $header_args = array(
                'UsernameToken'		=>	array(
                    'Username'	=>	Configure::read('shipmentTracking.ups.user'),
                    'Password'	=>	Configure::read('shipmentTracking.ups.pass')
                ),
                'ServiceAccessToken'=>	array(
                    'AccessLicenseNumber'	=>	Configure::read('shipmentTracking.ups.api_key')
                )
            );

            $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0', 'UPSSecurity', $header_args);
            $client->__setSoapHeaders($header);


            //get response
            $response = $client->__soapCall("ProcessTrack" , array($request));
            if($response){
                $activity = array();
                //debug($response);

                if(isset($response->Shipment->Package) and is_array($response->Shipment->Package) ){
                    if(isset($response->Shipment->Package[0]->Activity) and is_array($response->Shipment->Package[0]->Activity)){
                        $activity = $response->Shipment->Package[0]->Activity;
                    }
                }
                else if(isset($response->Shipment->Package->Activity) and is_array($response->Shipment->Package->Activity)){
                    $activity = $response->Shipment->Package->Activity;
                }

                if(!empty($activity)){
                    if($activity[0]->Status->Type == "D"){
                        $this->status['delivered_flg'] = 1;
                        $this->status['delivered_on_dt'] = strtotime($activity[0]->Date . " " . $activity[0]->Time);
                        $this->status['eta'] = $this->status['delivered_on_dt'];
                    }
                    else if(isset($response->Shipment->DeliveryDetail->Date)){
                        $this->status['eta'] = strtotime($response->Shipment->DeliveryDetail->Date);
                    }
                    else if(isset($response->Shipment->Package->DeliveryDetail)){
                        $this->status['eta'] = strtotime($response->Shipment->Package->DeliveryDetail->Date);
                    }
                }
            }
        }
        catch(Exception $ex){
        }

        return $this->status;
    }

    public function usps($tracking_no = '9400109699937143957885'){
        $this->clearStatus();
        $request = "<TrackFieldRequest USERID='". Configure::read('shipmentTracking.usps.user') . "'><Revision>1</Revision><ClientIp>127.0.0.1</ClientIp><SourceId>" . Configure::read('shipmentTracking.usps.source') ."</SourceId><TrackID ID='$tracking_no'></TrackID></TrackFieldRequest>";
        $url = "http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2&XML=" . rawurlencode($request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);

        if($response){
            $xml = new SimpleXMLElement($response);
            if(isset($xml->TrackInfo->StatusCategory) and $xml->TrackInfo->StatusCategory <> "Delivered"){
                if(isset($xml->TrackInfo->ExpectedDeliveryDate)) {
                    $this->status['eta'] = strtotime($xml->TrackInfo->ExpectedDeliveryDate);
                }
                else if(isset($xml->TrackInfo->PredictedDeliveryDate)){
                    $this->status['eta'] = strtotime($xml->TrackInfo->PredictedDeliveryDate);
                }
            }
            else if(isset($xml->TrackInfo->StatusCategory) and $xml->TrackInfo->StatusCategory == "Delivered"){
                $this->status['delivered_flg'] = 1;
                $this->status['delivered_on_dt'] = strtotime($xml->TrackInfo->TrackSummary->EventDate[0] . " " . $xml->TrackInfo->TrackSummary->EventTime[0]);
                $this->status['eta'] = $this->status['delivered_on_dt'];

            }
        }

        return $this->status;
    }

    public function fedex($tracking_no = '61299998790720014680'){
        $this->clearStatus();
        $wsdl = dirname(__FILE__) . "/tracking/fedex/TrackService_v8.wsdl";

        ini_set("soap.wsdl_cache_enabled", "0");
        $mode = array(
            'trace' 		=> true,
            'exceptions' => true
        );

        $client = new SoapClient($wsdl, $mode); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

        $request['WebAuthenticationDetail'] = array(
            'UserCredential' =>array(
                'Key' => Configure::read('shipmentTracking.fedex.key'),
                'Password' => Configure::read('shipmentTracking.fedex.pass')
            )
        );

        $request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request v8 using PHP ***');
        $request['Version'] = array(
            'ServiceId' => 'trck',
            'Major' => '8',
            'Intermediate' => '0',
            'Minor' => '0'
        );
        $request['SelectionDetails'] = array(
            //'CarrierCode' => 'FDXE',
            'PackageIdentifier' => array(
                'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
                'Value' => $tracking_no // Replace 'XXX' with a valid tracking identifier
            )
        );
        $request['ClientDetail'] = array(
            'AccountNumber' => Configure::read('shipmentTracking.fedex.account'),
            'MeterNumber' => Configure::read('shipmentTracking.fedex.meter')
        );

        try {
            $response = $client->track($request);
            if($response){
                if(isset($response->CompletedTrackDetails) and isset($response->CompletedTrackDetails->TrackDetails)){
                    if(isset($response->CompletedTrackDetails->TrackDetails->EstimatedDeliveryTimestamp)){
                        $this->status['eta'] = strtotime($response->CompletedTrackDetails->TrackDetails->EstimatedDeliveryTimestamp);
                    }
                    else if(isset($response->CompletedTrackDetails->TrackDetails->ActualDeliveryTimestamp)){
                        $this->status['eta'] = strtotime($response->CompletedTrackDetails->TrackDetails->ActualDeliveryTimestamp);
                        $this->status['delivered_on_dt'] = $this->status['eta'];
                        $this->status['delivered_flg'] = 1;
                    }

                }
            }
        }
        catch (SoapFault $exception) {
        }

        return $this->status;
    }
}