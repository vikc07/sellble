<?php
$this->extend('/Common/del');
$this->assign('title', 'Shipment');
$this->start('form');
?>
<?php echo $this->Form->create('Sale'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>