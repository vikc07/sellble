<?php
$this->extend('/Common/index');
$this->assign('title', 'SKU');
$this->assign('heading', 'SKU');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>
<?php echo $this->Form->create('Sku', array('novalidate' => true)); ?>
    <div class="col-md-3">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for SKU ID or item',
                'value' => $this->Session->read('SkuSearch.q')
            )
        );
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo $this->Form->input(
            'category_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(
                    '0' => 'Select Category'
                ),
                'value' => $this->Session->read('SkuSearch.category_id')
            )
        );
        ?>
    </div>
    <div class="col-md-1">
        <?php
        echo $this->Form->input(
            'show_only_avail',
            array(
                'type' => 'checkbox',
                'label' => 'Only In Stock',
                'class' => '',
                'value' => ($this->Session->read('SkuSearch.show_only_avail'))?$this->Session->read('SkuSearch.show_only_avail'):1,
                'default' => 1
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
        <th>SKU ID</th>
        <th>Purchase ID</th>
        <th>Item</th>
        <th class="text-right">Avail%</th>
        <th class="text-right">Avail Qty</th>
        <th class="text-right">Purchased Qty</th>
        <th class="text-right">Per Unit Price</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($skus as $sku){
    if(isset($sku['PurchaseLine'][0]['PurchaseLine'])){
    $purchase = $sku['PurchaseLine'][0]['PurchaseLine']['Purchase'][0]['Purchase'];
    $purchaseLine = $sku['PurchaseLine'][0]['PurchaseLine'];
    ?>
    <tr>
        <td><?php echo $sku['Sku']['idFormatted']; ?></td>
        <td>
            <?php
            echo $this->Html->link(
                $purchase['idFormatted'],
                array('controller'=>'purchase','action' => 'edit', $purchase['id'])
            );
            ?>
        </td>
        <td>
            <?php
            $brand = $sku['Item']['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
            echo $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $sku['Item']['fullName'];
            ?>
        </td>
        <td class="text-right">
            <?php
            if($purchaseLine['quantity']){
                echo $this->Number->toPercentage($sku['Sku']['quantity_avail']*100/$purchaseLine['quantity'],2);
            }
            ?>
        </td>
        <td class="text-right"><?php echo $sku['Sku']['quantity_avail']; ?></td>
        <td class="text-right"><?php echo $purchaseLine['quantity']; ?></td>
        <td class="text-right"><?php echo $this->Number->currency($sku['Sku']['per_unit_price_amt'], 'USD'); ?></td>
        <td>
            <?php
            echo $this->myHtml->icon(
                array(
                    'type' => 'barcode',
                    'toolTip' => 'Generate Barcode',
                    'linkTarget' => '_blank',
                    'args' => array('action' => 'barcode', $sku['Sku']['id'])
                )
            );
            ?>
        </td>
        <td>
            <?php
            if(empty($sku['Item']['ItemPhoto'])){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'upload',
                        'toolTip' => 'Upload photos',
                        'args' => array('controller' => 'item', 'action' => 'upload', $sku['Item']['id'])
                    )
                );
            }
            else{
                echo $this->myHtml->icon(
                    array(
                        'type' => 'picture',
                        'toolTip' => 'View photos',
                        'args' => array('controller' => 'item', 'action' => 'upload', $sku['Item']['id'])
                    )
                );
            }
            ?>
        </td>
        <td>
            <?php
            if($sku['Sku']['quantity_avail']){
                echo $this->myHtml->button(
                    array(
                        'icon' => array('type' => 'plus'),
                        'type' => 'primary',
                        'class' => 'btn-xs',
                        'title' => 'Add Valueadd',
                        'args' => array('controller' => 'valueadd', 'action' => 'edit', 'sku' => $sku['Sku']['id'])
                    )
                );
            }
            ?>
        </td>
        <td>
            <?php
            if(!isset($sku['Listing'][0]) or (isset($sku['Listing'][0]) and $sku['Listing'][0]['has_ended'])){
                if($sku['Sku']['quantity_avail']){
                    echo $this->myHtml->button(
                        array(
                            'icon' => array('type' => 'plus'),
                            'type' => 'primary',
                            'class' => 'btn-xs',
                            'title' => 'Add Listing',
                            'args' => array('controller' => 'listing', 'action' => 'edit', 'sku' => $sku['Sku']['id'])
                        )
                    );
                }
                else if(isset($sku['Listing'][0])){
                    echo $this->Html->link(
                        $sku['Listing'][0]['idFormatted'],
                        array('controller'=>'listing','action' => 'edit', $sku['Listing'][0]['id'])
                    );
                }
            }
            else{
                echo $this->Html->link(
                    $sku['Listing'][0]['idFormatted'],
                    array('controller'=>'listing','action' => 'edit', $sku['Listing'][0]['id'])
                );
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->myHtml->button(
                array(
                    'icon' => array('type' => 'minus'),
                    'type' => 'primary',
                    'class' => 'btn-xs',
                    'title' => 'Add Exclusion',
                    'args' => array('controller' => 'excluded_sku', 'action' => 'edit', 'sku' => $sku['Sku']['id'])
                )
            );
            ?>
        </td>
    </tr>
<?php
    }
}
$this->end();
unset($sku);
?>