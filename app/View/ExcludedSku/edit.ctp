<?php
$this->extend('/Common/save');
$this->assign('title', 'Exclusion');
$this->assign('operation', $operation);
if(isset($excluded_sku['ExcludedSku']['id'])){
    $this->assign('id', $excluded_sku['ExcludedSku']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('ExcludedSku', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
<?php
$disabled = false;
if($operation == 'Edit'){
    $disabled = true;
}
?>
<div class='row'>
    <div class='col-md-12'>
        <h4>
        <?php
        $brand = $sku['Item']['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        echo $sku['Sku']['idFormatted'] . ' - ' .
            $manufacturer['name'] . ' - ' .
            $brand['name'] . ' - ' .
            $sku['Item']['fullName'];
        ?>
        </h4>
        <?php
        echo $this->Form->input(
            'sku_id',
            array(
                'type' => 'hidden',
                'value' => $sku['Sku']['id']
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'exclude_reason_id',
            array(
                'type' => 'select',
                'label' => 'Reason',
                'class' => 'form-control',
                'default' => 5,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class='col-md-1'>
        <?php
        echo $this->Form->input(
            'quantity',
            array(
                'type' => 'text',
                'label' => 'Quantity',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'default' => $sku['Sku']['quantity_avail'],
                'disabled' => $disabled
            )
        ); ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-12'>
        <hr/>
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