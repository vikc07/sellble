<?php
$this->extend('/Common/save');
$this->assign('title', 'Enhancement');
$this->assign('operation', $operation);
if(isset($enhancement['Enhancement']['id'])){
    $this->assign('id', $enhancement['Enhancement']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Enhancement',array('novalidate'=>true)); ?>
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
                'Category',
                array(
                    'type' => 'select',
                    'multiple' => true,
                    'size' => 30,
                    'label' => 'Applies to',
                    'class' => 'form-control',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
    </div>
<?php echo $this->end(); ?>