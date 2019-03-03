<?php
$this->extend('/Common/index');
$this->assign('title', 'Purchase');
$this->assign('heading', 'Purchases');
$this->assign('table_width', '12');
$this->start('search');
?>
<?php echo $this->Form->create('Purchase', array('novalidate' => true)); ?>
    <div class="col-md-5">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for Purchase ID or Item',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'purchase_status_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(0 => 'Select Status')
            )
        );
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'marketplace_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(0 => 'Select Marketplace')
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
        <th></th>
        <th>Marketplace</th>
        <th class="text-right"><?php echo $this->Paginator->sort('purchase_dt', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
        <th class="text-right">Total Items</th>
        <th>Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Total</th>
        <th>Tracking#</th>
        <th class="text-right">Tax</th>
        <th class="text-right">Shipping</th>
        <th class="text-right">Other</th>
        <th class="text-right">Grand Total</th>
        <th class="text-right">Invoice</th>
        <th class="text-right"></th>
    </tr>
    </thead>
<?php
foreach($purchases as $purchase){
    $marketplace = $purchase['Marketplace'][0]['Marketplace'];
    ?>
    <tr>
    <td rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
        <?php
        $classes = array(
            "",
            "label-danger",
            "label-warning",
            "label-info",
            "label-success",
            "label-primary",
            "label-default"
        );
        ?>
        <h5>
                                <span class="label <?php echo $classes[$purchase['PurchaseStatus']['id']]; ?>">
                                <?php echo $purchase['PurchaseStatus']['name']; ?>
                                </span>
        </h5>
    </td>
    <td rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
        <?php
        if($marketplace['logo']){
            echo $this->myHtml->image(
                array(
                    'image' => $marketplace['logoFull']
                )
            );
        }
        else{
            echo $marketplace['name'];
        }
        ?>
    </td>
    <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
        <?php
        $year = $this->Time->format($purchase['Purchase']['purchase_dt'], '%Y');
        echo $this->Time->format($purchase['Purchase']['purchase_dt'], '%e %b');
        if($year <> date('Y')){
            echo ' ' . $year;
        }
        ?>
    </td>
    <td rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
        <?php
        echo $this->Html->link(
            $purchase[ 'Purchase' ][ 'idFormatted' ],
            array('action' => 'edit', $purchase['Purchase']['id'] )
        );
        ?>
    </td>
    <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
        <?php echo $purchase['Purchase']['total_items']; ?>
    </td>
    <?php
    $count = 0;
    foreach($purchase['PurchaseLine'] as $line){
        $brand = $line['Item']['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        if($count){
            ?>
            <tr>
        <?php
        }
        ?>
        <td>
            <?php
            $item = $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $line['Item']['fullName'];
            if($line['product_url']){
                echo $this->Html->link(
                    $item,
                    $line['product_url'],
                    array('target' => '_blank')
                );
            }
            else{
                echo $item;
            }
            ?>
        </td>
        <td class="text-right">
            <?php echo $line['quantity']; ?>
        </td>
        <td class="text-right">
            <?php echo $this->Number->currency($line['total_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td>
            <?php
            $showTrackingNo = false;
            if($purchase['Purchase']['purchase_status_id'] == 3){ //Payment done
                if($line['shipping_dt']){
                    if(!$line['tracking_no']){
                        $line['tracking_no'] = 'Not available';
                    }

                    $showTrackingNo = true;
                }
                else{
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'ship',
                            'title' => 'Mark as Shipped',
                            'args' => array('action' => 'markShipped',$line['id'])
                        )
                    );
                }
            }
            else{
                $showTrackingNo = true;
            }

            if($showTrackingNo){
                echo $this->Html->link(
                    $line['tracking_no'],
                    array('action' => 'markShipped',$line['id'])
                );
            }
            ?>
        </td>
        <?php
        if($count==0){
            ?>
            <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php echo $this->Number->currency($purchase['Purchase']['sales_tax_amt'], 'USD', array('after' => false)); ?>
            </td>
            <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php echo $this->Number->currency($purchase['Purchase']['shipping_amt'], 'USD', array('after' => false)); ?>
            </td>
            <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php echo $this->Number->currency($purchase['Purchase']['other_amt'], 'USD', array('after' => false)); ?>
            </td>
            <td class="text-right" rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php echo $this->Number->currency($purchase['Purchase']['grand_total_amt'], 'USD', array('after' => false)); ?>
            </td>
            <td rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php
                $args =array(
                    'type' => 'upload',
                    'toolTip' => 'Upload Invoice',
                    'args' => array(
                        'action' => 'uploadInvoice',
                        $purchase['Purchase']['id']
                    )
                );
                echo $this->myHtml->icon($args);

                if($purchase['Purchase']['file_invoice']){
                    $args['toolTip'] = 'View Invoice';
                    $args['type'] = 'file-text';
                    $args['linkTarget'] = '_blank';
                    $args['args'] = $purchase['Purchase']['file_invoice'];
                    echo '&nbsp' . $this->myHtml->icon($args);
                }
                ?>
            </td>
            <td rowspan="<?php echo $purchase['Purchase']['total_items']; ?>">
                <?php
                if($purchase['Purchase']['notes']){
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'comment',
                            'toolTip' => $purchase['Purchase']['notes']
                        )
                    );
                }
                ?>
            </td>
        <?php
        }
        $count++;
        ?>
        </tr>
    <?php
    }
    ?>
<?php
}
$this->end();
unset($purchase);
?>