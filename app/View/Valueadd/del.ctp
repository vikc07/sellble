<?php
$this->extend('/Common/del');
$this->assign('title', 'Value Add');
$this->start('form');
?>
<?php echo $this->Form->create('Valueadd'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>