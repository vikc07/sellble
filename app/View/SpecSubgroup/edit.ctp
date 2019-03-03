<?php
$this->extend('/Common/save');
$this->assign('title', 'Spec Subgroup');
$this->assign('operation', $operation);
if(isset($spec_subgroup['SpecSubgroup']['id'])){
    $this->assign('id', $spec_subgroup['SpecSubgroup']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('SpecSubgroup',array('novalidate'=>true)); ?>
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
        <div class='col-md-1'>
            <?php
            echo $this->Form->input(
                'order',
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
                'spec_group_id',
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