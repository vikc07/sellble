<?php

class InventoryController extends AppController{
    public $helpers = array('myHtml', 'Time', 'Number', 'myForm');
    public $uses = array('Inventory');

    public function index(){
        $x = 14;
        $this->set('x', $x);
        $this->set('totalLastXDays', $this->Inventory->totallastXDays($x));
        $this->set('totalMonthly', $this->Inventory->totalMonthly());
    }

    public function generate(){
        $this->Inventory->generateTodaysSnapshot();
        return $this->redirect(array('action' => 'index'));
    }
}