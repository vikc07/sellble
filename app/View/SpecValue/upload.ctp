<?php
$this->extend('/Common/upload');
$this->assign('title', 'Spec Value');
$this->assign('heading', 'Upload logo');
$this->assign('id', $spec_value['SpecValue']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('SpecValue', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($spec_value['SpecValue']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $spec_value['SpecValue']['logoFull']
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