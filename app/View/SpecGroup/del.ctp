<?php
$this->extend('/Common/del');
$this->assign('title', 'Spec Group');
$this->start('form');
?>
<?php echo $this->Form->create('SpecGroup'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>