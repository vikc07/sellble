<?php
$this->extend('/Common/del');
$this->assign('title', 'Manufacturer');
$this->start('form');
?>
<?php echo $this->Form->create('Manufacturer'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>