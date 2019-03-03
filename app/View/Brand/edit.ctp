<?php
$this->extend('/Common/save');
$this->assign('title', 'Brand');
$this->assign('operation', $operation);
if(isset($brand['Brand']['id'])){
    $this->assign('id', $brand['Brand']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Brand', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'name',
            array(
                'type' => 'text',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'manufacturer_id',
            array(
                'type' => 'select',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => ''
            )
        );
        ?>
    </div>
    <div class="col-md-4">
        <br>
        <?php
        echo $this->myHtml->button(
            array(
                'icon' => array('type'=>'plus'),
                'title' => 'Add Manufacturer',
                'type' => 'primary',
                'args' => array('controller' => 'manufacturer', 'action' => 'edit'),
                'linkTarget' => '_blank'
            )
        );
        ?>
    </div>
</div>
<?php echo $this->end(); ?>