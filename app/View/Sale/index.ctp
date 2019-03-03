<?php
$this->extend('/Common/index');
$this->assign('title', 'Sale');
$this->assign('heading', 'Sales');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>
        <?php echo $this->Form->create('Sale', array('novalidate' => true)); ?>
            <div class="col-md-5">
                <?php
                echo $this->Form->input(
                    'q',
                    array(
                        'label' => false,
                        'type' => 'text',
                        'class' => 'form-control',
                        'placeholder' => 'Search for Sale ID or Item',
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
        <th class="text-right"><?php echo $this->Paginator->sort('Sale.sale_dt', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('Sale.id', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('Listing.id', 'Listing ID'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.id', 'Customer ID'); ?></th>
        <th>SKU</th>
        <th>Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Sale Amt</th>
        <th class="text-right">Shipping</th>
        <th class="text-right">Tax</th>
        <th class="text-right">FV Fee</th>
        <th class="text-right">PayPal Fee</th>
        <th class="text-right">Other</th>
        <th class="text-right">Net</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($sales as $sale){
    $listing = $sale['Listing'][0]['Listing'];
    $sku = $listing['Sku'];
    $item = $sku['Item'];
    $brand = $item['Brand'][0]['Brand'];
    $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
    ?>
    <tr>
        <td class="text-right" >
            <?php
            $year = $this->Time->format($sale['Sale']['sale_dt'], '%Y');
            echo $this->Time->format($sale['Sale']['sale_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale['Sale']['idFormatted'],
                array('action' => 'edit', $sale['Sale']['id'] )
            );
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $listing['idFormatted'],
                array('controller' => 'listing','action' => 'edit', $listing['id'] )
            );
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale['Customer']['idFormatted'],
                array('controller' => 'customer','action' => 'edit', $sale['Customer']['id'] )
            );
            ?>
        </td>
        <td>
            <?php echo $sku['idFormatted']; ?>
        </td>
        <td>
            <?php
            echo $manufacturer['name'] . ' - ' . $brand['name'] . ' - ' . $item['fullName'];
            ?>
        </td>
        <td class="text-right" >
            <?php echo $sale['Sale']['quantity']; ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['sale_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['shipping_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['sales_tax_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['fv_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['paypal_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['other_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['Sale']['net_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td>
            <?php
            if($sale['Sale']['notes']){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'comment',
                        'toolTip' => $sale['Sale']['notes']
                    )
                );
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->myHtml->button(
                array(
                    'icon' => array('type' => 'plus'),
                    'type' => 'primary',
                    'class' => 'btn-xs',
                    'title' => 'Ship this',
                    'args' => array('controller' => 'shipment', 'action' => 'edit', 'sale' => $sale['Sale']['id'])
                )
            );
            ?>
        </td>
        <td>
            <?php
            echo $this->myHtml->button(
                array(
                    'icon' => array('type' => 'plus'),
                    'type' => 'primary',
                    'class' => 'btn-xs',
                    'title' => 'Add Return',
                    'args' => array('controller' => 'sale_return', 'action' => 'edit', 'sale' => $sale['Sale']['id'])
                )
            );
            ?>
        </td>
    </tr>
<?php
}
$this->end();
unset($sale);
?>