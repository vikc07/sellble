<?php
$this->extend('/Common/index');
$this->assign('title', 'Item');
$this->assign('heading', 'Items');
$this->assign('table_width', '12');
$this->start('search');
?>
<?php echo $this->Form->create('Item', array('novalidate' => true)); ?>
    <div class="col-md-3">
        <?php
        echo $this->Form->input(
            'category_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(0 => 'Select Category'),
                'value' => $this->Session->read('ItemSearch.category_id')
            )
        );
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for Item ID, brand, manufacturer, model, description or UPC',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'value' => $this->Session->read('ItemSearch.q')
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
        <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('Brand.name', 'Brand'); ?></th>
        <th><?php echo $this->Paginator->sort('model', 'Model'); ?></th>
        <th><?php echo $this->Paginator->sort('description', 'Description'); ?></th>
        <th>UPC</th>
        <th><?php echo $this->Paginator->sort('Category.name', 'Category'); ?></th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($items as $item){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $item[ 'Item' ][ 'idFormatted' ],
                array('action' => 'edit', $item['Item']['id'] )
            );
            ?>
        </td>
        <td><?php echo $item['Brand'][0]['Brand']['Manufacturer'][0]['Manufacturer']['name'] . ' - ' . $item['Brand'][0]['Brand']['name']; ?></td>
        <td><?php echo $item['Item']['model']; ?></td>
        <td><?php echo $item['Item']['description']; ?></td>
        <td><?php echo $item['Item']['upc']; ?></td>
        <td><?php echo $item['Category']['name']; ?></td>
        <td>
            <?php
            if(empty($item['ItemPhoto'])){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'upload',
                        'toolTip' => 'Upload photos',
                        'args' => array('action' => 'upload', $item['Item']['id'])
                    )
                );
            }
            else{
                echo $this->myHtml->icon(
                    array(
                        'type' => 'picture',
                        'toolTip' => 'View photos',
                        'args' => array('action' => 'upload', $item['Item']['id'])
                    )
                );
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->myHtml->icon(
                array(
                    'type' => 'settings',
                    'toolTip' => 'Specs',
                    'args' => array('action' => 'specs', $item['Item']['id'])
                )
            );
            ?>
        </td>
    </tr>
<?php
}
$this->end();
unset($item);
?>