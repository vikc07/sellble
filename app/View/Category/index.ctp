<?php
$this->extend('/Common/index');
$this->assign('title', 'Category');
$this->assign('heading', 'Categories');
$this->assign('table_width', '6');
$this->start('search');
?>
<?php echo $this->Form->create('Category', array('novalidate' => true)); ?>
    <div class="col-md-4">
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
        <th><?php echo $this->Paginator->sort('Category.name', 'Name'); ?></th>
        <th><?php echo $this->Paginator->sort('SpecGroup.name', 'Spec Group'); ?></th>
        <th>eBay Fee%</th>
        <th>eBay Template</th>
        <th>Components Category</th>
    </tr>
    </thead>
<?php
foreach($categories as $category){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $category['Category']['name'],
                array('action' => 'edit', $category['Category']['id'] )
            );
            ?>
        </td>
        <td><?php echo $category['SpecGroup']['name']; ?></td>
        <td><?php echo $category['Category']['ebay_fee_percent']; ?></td>
        <td><?php echo $category['Category']['ebay_template']; ?></td>
        <td><?php echo ($category['Category']['is_component'])?'Y':'N'; ?></td>
    </tr>
<?php
}
echo $this->end();
unset($category);
?>