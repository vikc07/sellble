<?php
$totalPhotos = count($item_photos);
$this->extend('/Common/upload');
$this->assign('title', 'Item');
$this->assign('heading', 'Upload Photos');
$this->assign('id', $item['Item']['id']);
$this->start('form');
?>
<?php echo $this->Form->create('Item', array('enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<?php echo $this->Form->input("Item.id", array('type' => 'hidden', 'value'=>$item['Item']['id'])); ?>
<?php
$i = 0;
foreach($item_photos as $photo){
    if($i % 4 == 0){
?>
<div class="row">
<?php
}
?>
    <div class="col-md-3">
    <?php
        $image = $photo['ItemPhoto']['photo'];
        echo $this->myHtml->image(
            array(
               'image' => $image,
               'args' => array(
                   'class' => 'thumbnail img-responsive',
                   'url' => '/img/' . $image,
                   'fullBase' => true
               )
            )
        );
        echo $this->myHtml->button(
            array(
                'icon' => array('type'=>'delete'),
                'title' => 'Delete',
                'type' => 'danger',
                'args' => array('action' => 'deletePhoto', $item['Item']['id'], 'photo' => $photo['ItemPhoto']['id'])
            )
        );
    ?>
    </div>
<?php if((($i+1) % 4 == 0) or ($i+1) == $totalPhotos){ ?>
</div>
<?php
    }
     $i++;
}
?>
<div class='row'>
    <div class='col-md-2'>
        <br>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
         <?php
            if($totalPhotos < Configure::read('maxItemPhotos')){
                echo $this->Form->input(
                    "ItemPhotos.file_name.",
                    array(
                        'label' => '',
                        'type' => 'file',
                        'class' => 'form-control file-upload',
                        'error' => array(
                            'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                        ),
                        'multiple'
                    )
                 );
            }
            ?>
    </div>
</div>
<?php echo $this->end(); ?>