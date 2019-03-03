<?php
$this->extend('/Common/del');
$this->assign('title', 'Category');
$this->start('form');
?>
<?php echo $this->Form->create('Category'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>