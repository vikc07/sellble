<?php
$this->extend('/Common/save');
$this->assign('title', 'Spec');
$this->assign('operation', $operation);
if(isset($spec['Spec']['id'])){
    $this->assign('id', $spec['Spec']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Spec',array('novalidate'=>true)); ?>
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
                'spec_subgroup_id',
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
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'multi_value',
                array(
                    'type' => 'checkbox',
                    'label' => 'Multi Value',
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
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'hide_from_public',
                array(
                    'type' => 'checkbox',
                    'label' => 'Hide From Public',
                    'class' => '',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
    </div>
<?php echo $this->end(); ?>