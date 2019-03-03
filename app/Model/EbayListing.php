<?php
class EbayListing extends AppModel{
    public $belongsTo = array(
        'Listing'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['description'])){
                $result[$this->alias]['description'] = htmlspecialchars_decode($result[$this->alias]['description']);
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }
}