<?php
$this->extend('/Common/upload');
$this->assign('title', 'Shipment');
$this->assign('heading', 'Upload Label');
$this->assign('removeAction', 'removeLabel');
$this->assign('id', $shipment['Shipment']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Shipment', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($shipment['Shipment']['file_label']){
                echo $this->Html->link(
                    basename($shipment['Shipment']['file_label']),
                    $shipment['Shipment']['file_label'],
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
                'file_label',
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