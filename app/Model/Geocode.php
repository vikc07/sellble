<?php

class Geocode extends AppModel{
    public $virtualFields = array(
        'latlng' => "concat(Geocode.lat,',',Geocode.lng)"
    );

    public $primaryKey = 'zip';
    public $hasMany =array(
        'Customer' => array(
            'foreignKey' => 'bill_zip'
        )
    );

    public function checkIt($zip = null){
        if(!$zip){
            return false;
        }

        $record = $this->find(
            'first',
            array(
                'conditions' => array(
                    $this->alias . '.zip' => $zip
                )
            )
        );

        return $record;
    }

    public function codify($zip){
        if($this->checkIt($zip)){
            return true;
        }

        // Get Geocodes
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=" . $zip);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close ($ch);
        $json = json_decode($content);

        $data = array('Geocode');
        if($json){
            $data['Geocode']['lat'] = $json->results[0]->geometry->location->lat;
            $data['Geocode']['lng'] = $json->results[0]->geometry->location->lng;
            $data['Geocode']['zip'] = $zip;

            return $this->save($data, false);
        }
    }
}