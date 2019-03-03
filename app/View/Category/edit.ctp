<?php
$this->extend('/Common/save');
$this->assign('title', 'Category');
$this->assign('operation', $operation);
if(isset($category['Category']['id'])){
    $this->assign('id', $category['Category']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Category',array('novalidate'=>true)); ?>
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
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'ebay_fee_percent',
                array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'default' => 9
                )
            );
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'spec_group_id',
                array(
                    'type' => 'select',
                    'class' => 'form-control',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'selected' => 4
                )
            );
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'ebay_template',
                array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'default' => 'template-ebay-default.php'
                )
            );
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'is_component',
                array(
                    'type' => 'checkbox',
                    'label' => 'This category will have SKUs acting as components',
                    'class' => '',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
    </div>
<?php echo $this->end(); ?>