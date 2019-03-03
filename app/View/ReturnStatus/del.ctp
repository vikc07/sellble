<?php
$this->extend('/Common/del');
$this->assign('title', 'Return Status');
$this->start('form');
?>
<?php echo $this->Form->create('ReturnStatus'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>