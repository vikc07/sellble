<?php
$this->extend('/Common/del');
$this->assign('title', 'Spec Subgroup');
$this->start('form');
?>
<?php echo $this->Form->create('SpecSubgroup'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>