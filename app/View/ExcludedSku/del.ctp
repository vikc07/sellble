<?php
$this->extend('/Common/del');
$this->assign('title', 'Exclusion');
$this->start('form');
?>
<?php echo $this->Form->create('ExcludedSku'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>