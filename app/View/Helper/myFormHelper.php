<?php
class myFormHelper extends AppHelper{
	public $helpers = array( 'myHtml', 'Form' );

    public function datalist($id, $data){
        $out = "<datalist id='$id'>";
        foreach($data as $key => $val){
            $out .= "<option>$val</option>";
        }

        $out .= "</datalist>";

        return $out;
    }
};

?>