<?php
$this->extend('/Common/upload');
$this->assign('title', 'Manufacturer');
$this->assign('heading', 'Upload logo');
$this->assign('id', $manufacturer['Manufacturer']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Manufacturer', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($manufacturer['Manufacturer']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $manufacturer['Manufacturer']['logoFull']
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
            <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
        </div>
    </div>
<?php echo $this->end(); ?>