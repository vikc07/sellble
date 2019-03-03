<?php
$this->extend('/Common/del');
$this->assign('title', 'Listing');
$this->assign('doNotDelete', $doNotDelete);
$this->start('form');
?>
<?php echo $this->Form->create('Listing'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>