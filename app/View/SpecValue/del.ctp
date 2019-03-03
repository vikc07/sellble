<?php
$this->extend('/Common/del');
$this->assign('title', 'Spec Value');
$this->start('form');
?>
<?php echo $this->Form->create('SpecValue'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>