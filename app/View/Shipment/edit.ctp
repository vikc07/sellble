<?php
$this->extend('/Common/save');
$this->assign('title', 'Shipment');
$this->assign('operation', $operation);
$readonly = false;
if(isset($shipment['Shipment']['id'])){
    $this->assign('id', $shipment['Shipment']['id']);
    $readonly = true;
}
$this->start('form');
?>
<?php echo $this->Form->create('Shipment', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
<div class='row'>
    <div class='col-md-12'>
        <h4>
        <?php
        $listing = $sale['Listing'][0]['Listing'];
        $item = $listing['Sku']['Item'];
        $brand = $item['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        echo $sale['Sale']['idFormatted'] . ' - ' .
            $listing['Sku']['idFormatted'] . ' - ' .
            $manufacturer['name'] . ' - ' .
            $brand['name'] . ' - ' .
            $item['fullName'];
        ?>
        </h4>
        <?php
        echo $this->Form->input(
            'sale_id',
            array(
                'type' => 'hidden',
                'value' => $sale['Sale']['id']
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-12'>
        <h4>
            <?php
            echo $sale['Customer']['listName'];
            ?>
        </h4>
    </div>
</div>
<div class="row">
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'shipment_dt',
            array(
                'type' => 'text',
                'label' => 'Shipment Date',
                'class' => 'form-control sellble-date',
                'default' => date('Y-m-d'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class='col-md-1'>
        <?php
        echo $this->Form->input(
            'quantity',
            array(
                'type' => 'text',
                'label' => 'Quantity',
                'class' => 'form-control text-right sellble-quantity',
                'default' => $sale['Sale']['quantity'],
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            "carrier_id",
            array(
                'type' => 'select',
                'label' => 'Shipping Service',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            "shipping_amt",
            array(
                'type' => 'text',
                'default' => '0.00',
                'label' => 'Shipping Charge',
                'class' => 'form-control text-right sellble-amount',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                'after' => "</div>"
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            "tracking_no",
            array(
                'type' => 'text',
                'label' => 'Tracking#',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        echo $this->Form->input(
            'notes',
            array(
                'type' => 'textarea',
                'label' => 'Notes',
                'class' => 'form-control'
            )
        );
        ?>
    </div>
</div>
<?php
$this->end();
?>