<?php
$this->extend('/Common/del');
$this->assign('title', 'Purchase');
$this->assign('doNotDelete', $doNotDelete);
$this->start('form');
?>
<?php echo $this->Form->create('Purchase'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>