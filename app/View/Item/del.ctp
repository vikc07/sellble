<?php
$this->extend('/Common/del');
$this->assign('title', 'Item');
$this->start('form');
?>
<?php echo $this->Form->create('Item'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>