<?php
$this->extend('/Common/del');
$this->assign('title', 'Spec');
$this->start('form');
?>
<?php echo $this->Form->create('Spec'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>