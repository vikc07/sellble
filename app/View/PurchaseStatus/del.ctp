<?php
$this->extend('/Common/del');
$this->assign('title', 'Purchase Status');
$this->start('form');
?>
<?php echo $this->Form->create('PurchaseStatus'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->end(); ?>