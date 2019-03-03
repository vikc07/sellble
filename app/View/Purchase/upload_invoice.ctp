<?php
$this->extend('/Common/upload');
$this->assign('title', 'Purchase');
$this->assign('heading', 'Upload Invoice');
$this->assign('removeAction', 'removeInvoice');
$this->assign('id', $purchase['Purchase']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Purchase', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($purchase['Purchase']['file_invoice']){
                echo $this->Html->link(
                    basename($purchase['Purchase']['file_invoice']),
                    $purchase['Purchase']['file_invoice'],
                    array(
                        'target' => '_blank'
                    )
                );
            }
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'file_invoice',
                array(
                    'label' => '',
                    'type' => 'file',
                    'class' =>
                        'form-control file-upload',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
    </div>
<?php
$this->end();
?>