<?php
class ItemPhoto extends AppModel{
    public $virtualFields = array(
        'itemIdFormatted'	=>	"concat('I',lpad(ItemPhoto.item_id,8,'0'))"
    );

    public $belongsTo = array(
        'Item'
    );

    public function afterFind($results, $useless = false){
        $resultsArray = $this->prettyResultsArray($results, $this->primaryKey, $this->alias);
        foreach($resultsArray as &$result) {
            if(isset($result[$this->alias]['file_name']) and $result[$this->alias]['file_name']){
                $result[$this->alias]['photo'] = Configure::read('urlImg') . 'item/' .
                    $result[$this->alias]['itemIdFormatted'] . '/' . $result[$this->alias]['file_name'];
            }
        }

        return parent::afterFind($resultsArray, $useless);
    }

    public function deleteIt($id = null){
        return $this->delete($id);
    }
}