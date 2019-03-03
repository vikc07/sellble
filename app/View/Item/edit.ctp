<?php
$this->extend('/Common/save');
$this->assign('title', 'Item');
$this->assign('operation', $operation);
if(isset($items['Item']['id'])){
    $this->assign('id', $items['Item']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Item',array('novalidate'=>true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'category_id',
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
                    'title' => 'Add Category',
                    'type' => 'primary',
                    'args' => array('controller' => 'category', 'action' => 'edit'),
                    'linkTarget' => '_blank',
                )
            );
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?php
            echo $this->Form->input(
                'brand_id',
                array(
                    'type' => 'select',
                    'class' => 'form-control sellble-brands',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'empty' => ''
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'brand_name',
                array(
                    'type' => 'hidden',
                    'id' => 'sellble-item-brand-name',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
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
                    'title' => 'Add Brand',
                    'type' => 'primary',
                    'args' => array('controller' => 'brand', 'action' => 'edit'),
                    'linkTarget' => '_blank'
                )
            );
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'model',
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
                'description',
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
                'upc',
                array(
                    'label' => 'UPC',
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
<?php
$this->end();
?>