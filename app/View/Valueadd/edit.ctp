<?php
$this->extend('/Common/save');
$this->assign('title', 'Value Add');
$this->assign('operation', $operation);
if(isset($valueadd['Valueadd']['id'])){
    $this->assign('id', $valueadd['Valueadd']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Valueadd', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('Valueadd.id', array('type' => 'hidden'));
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
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'enhancement_id',
            array(
                'type' => 'select',
                'label' => 'Enhancement',
                'class' => 'form-control',
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
    <div class='col-md-8'>
        <?php
        echo $this->Form->input(
            'component_id',
            array(
                'type' => 'select',
                'label' => 'Component',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        ); ?>
    </div>
</div>
<div class="row">
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'is_complete',
            array(
                'type' => 'checkbox',
                'label' => 'Mark this as complete',
                'class' => '',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
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