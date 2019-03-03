<?php
$this->extend('/Common/del');
$this->assign('title', 'Enhancement');
$this->start('form');
?>
<?php echo $this->Form->create('Enhancement'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>