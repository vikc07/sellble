<?php
$this->extend('/Common/save');
$this->assign('title', 'Service');
$this->assign('operation', $operation);
if(isset($carrier['Carrier']['id'])){
    $this->assign('id', $carrier['Carrier']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Carrier', array('novalidate'=>true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'name',
            array(
                'type' => 'text',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'service',
            array(
                'type' => 'text',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'tracking_url',
            array(
                'type' => 'text',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<?php echo $this->end(); ?>