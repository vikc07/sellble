<?php
$this->extend('/Common/save');
$this->assign('title', 'Spec Group');
$this->assign('operation', $operation);
if(isset($spec_group['SpecGroup']['id'])){
    $this->assign('id', $spec_group['SpecGroup']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('SpecGroup',array('novalidate'=>true)); ?>
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
<?php echo $this->end(); ?>