<?php
$this->extend('/Common/save');
$this->assign('title', 'Return Status');
$this->assign('operation', $operation);
if(isset($return_status['ReturnStatus']['id'])){
    $this->assign('id', $return_status['ReturnStatus']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('ReturnStatus',array('novalidate'=>true)); ?>
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