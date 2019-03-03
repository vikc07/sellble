<?php
$this->extend('/Common/del');
$this->assign('title', 'Shipping Service');
$this->start('form');
?>
<?php echo $this->Form->create('Carrier'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>