<?php
$this->extend('/Common/index');
$this->assign('title', 'Enhancement');
$this->assign('heading', 'Enhancements');
$this->assign('table_width', '6');
$this->start('search');
?>
<?php echo $this->Form->create('Enhancement', array('novalidate' => true)); ?>
<div class="col-md-6">
    <?php
    echo $this->Form->input(
        'q',
        array(
            'label' => false,
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'Search for name',
            'error' => array(
                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
            )
        )
    );
    ?>
</div>
<?php
$this->end();
$this->start('table');
?>
    <thead>
    <tr>
        <th><?php echo $this->Paginator->sort('Enhancement.name', 'Name'); ?></th>
    </tr>
    </thead>
<?php
foreach($enhancements as $enhancement){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $enhancement['Enhancement']['name'],
                array('action' => 'edit', $enhancement['Enhancement']['id'] )
            );
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($enhancements);
?>