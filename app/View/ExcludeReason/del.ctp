<?php
$this->extend('/Common/del');
$this->assign('title', 'Exclude Reason');
$this->start('form');
?>
<?php echo $this->Form->create('ExcludeReason'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>