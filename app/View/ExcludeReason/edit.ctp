<?php
$this->extend('/Common/save');
$this->assign('title', 'Exclude Reason');
$this->assign('operation', $operation);
if(isset($exclude_reason['ExcludeReason']['id'])){
    $this->assign('id', $exclude_reason['ExcludeReason']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('ExcludeReason',array('novalidate'=>true)); ?>
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
<?php
$this->end();
?>