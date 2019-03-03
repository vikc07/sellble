<?php
$this->extend('/Common/del');
$this->assign('title', 'Marketplace');
$this->start('form');
?>
<?php echo $this->Form->create('Marketplace'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>