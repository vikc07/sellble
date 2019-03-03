<?php
$this->extend('/Common/upload');
$this->assign('title', 'Service');
$this->assign('heading', 'Upload logo');
$this->assign('id', $carrier['Carrier']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Carrier', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($carrier['Carrier']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $carrier['Carrier']['logoFull']
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
                'logo',
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
<?php echo $this->end(); ?>