<?php
$this->extend('/Common/save');
$this->assign('title', 'SpecValue');
$this->assign('operation', $operation);
if(isset($spec_value['SpecValue']['id'])){
    $this->assign('id', $spec_value['SpecValue']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('SpecValue',array('novalidate'=>true)); ?>
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
                'spec_id',
                array(
                    'type' => 'select',
                    'class' => 'form-control',
                    'empty' => '',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
    </div>
<?php echo $this->end(); ?>