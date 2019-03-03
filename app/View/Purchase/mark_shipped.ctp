<?php
$this->extend('/Common/save');
$this->assign('title', 'Purchase');
$this->assign('heading', 'Mark Shipped');
$this->assign('removeAction', 'removeShipping');
if(isset($purchase_line['PurchaseLine']['id'])){
    $this->assign('id', $purchase_line['PurchaseLine']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('PurchaseLine', array('novalidate' => true)); ?>
<?php echo $this->Form->input('PurchaseLine.id', array('type' => 'hidden')); ?>
<div class='row'>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'PurchaseLine.shipping_dt',
            array(
                'type' => 'text',
                'label' => 'Shipping Date',
                'class' => 'form-control sellble-date',
                'default' => date('Y-m-d'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        ); ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'PurchaseLine.carrier_id',
            array(
                'type' => 'select',
                'label' => 'Carrier',
                'class' => 'form-control',
                'default' => 0,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        ); ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'PurchaseLine.tracking_no',
            array(
                'type' => 'text',
                'label' => 'Tracking#',
                'class' => 'form-control',
                'default' => 0,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        ); ?>
    </div>
</div>
<?php
$this->end();
?>
