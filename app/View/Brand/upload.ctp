<?php
$this->extend('/Common/upload');
$this->assign('title', 'Brand');
$this->assign('heading', 'Upload logo');
$this->assign('id', $brand['Brand']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Brand', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            if($brand['Brand']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $brand['Brand']['logoFull']
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
            );
            ?>
        </div>
    </div>
<?php echo $this->end(); ?>