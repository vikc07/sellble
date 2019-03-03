<?php
$this->extend('/Common/index');
$this->assign('title', 'Shipment');
$this->assign('heading', 'Shipments');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>
<?php echo $this->Form->create('Shipment', array('novalidate' => true)); ?>
    <div class="col-md-5">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for Shipment ID, Sale ID or Item',
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
        <th class="text-right"><?php echo $this->Paginator->sort('Shipment.shipment_dt', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('Shipment.id', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('Sale.id', 'Sale ID'); ?></th>
        <th>SKU</th>
        <th>Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Shipping Amt</th>
        <th>City</th>
        <th>State</th>
        <th>Country</th>
        <th>Zip</th>
        <th></th>
        <th>Tracking#</th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($shipments as $shipment){
    $sale = $shipment['Sale'][0]['Sale'];
    $listing = $sale['Listing'][0]['Listing'];
    $sku = $listing['Sku'];
    $item = $sku['Item'];
    $brand = $item['Brand'][0]['Brand'];
    $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
    ?>
    <tr>
        <td class="text-right" >
            <?php
            $year = $this->Time->format($shipment['Shipment']['shipment_dt'], '%Y');
            echo $this->Time->format($shipment['Shipment']['shipment_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $shipment['Shipment']['idFormatted'],
                array('action' => 'edit', $shipment['Shipment']['id'] )
            );
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale['idFormatted'],
                array('controller' => 'sale','action' => 'edit', $sale['id'] )
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
            <?php echo $shipment['Shipment']['quantity']; ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($shipment['Shipment']['shipping_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td>
            <?php
            if(!empty($sale['Customer']['ship_city'])){
                echo $sale['Customer']['ship_city'];
            }
            else{
                echo $sale['Customer']['bill_city'];
            }
            ?>
        </td>
        <td>
            <?php
            if(!empty($sale['Customer']['ShipUsState']['name'])){
                echo $sale['Customer']['ShipUsState']['name'] . ' - ' .
                    $sale['Customer']['ShipUsState']['full_nm'];
            }
            else{
                echo $sale['Customer']['BillingUsState']['name'] . ' - ' .
                    $sale['Customer']['BillingUsState']['full_nm'];
            }
            ?>
        </td>
        <td>
            <?php
            if(!empty($sale['Customer']['ship_country'])){
                echo $sale['Customer']['ship_country'];
            }
            else{
                echo $sale['Customer']['bill_country'];
            }
            ?>
        </td>
        <td>
            <?php
            if(!empty($sale['Customer']['ship_zip'])){
                echo $sale['Customer']['ship_zip'];
            }
            else{
                echo $sale['Customer']['bill_zip'];
            }
            ?>
        </td>
        <td>
            <?php
            if($shipment['Shipment']['notes']){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'comment',
                        'toolTip' => $shipment['Shipment']['notes']
                    )
                );
            }
            ?>
        </td>
        <td><?php echo $shipment['Shipment']['tracking_no']; ?></td>
        <td>
            <?php
            $args =array(
                'type' => 'upload',
                'toolTip' => 'Upload Label',
                'args' => array(
                    'action' => 'uploadLabel',
                    $shipment['Shipment']['id']
                )
            );
            echo $this->myHtml->icon($args);

            if($shipment['Shipment']['file_label']){
                $args['toolTip'] = 'Print Label';
                $args['type'] = 'printer';
                $args['linkTarget'] = '_blank';
                $args['args'] = $shipment['Shipment']['file_label'];
                echo '&nbsp;' . $this->myHtml->icon($args);
            }
            ?>
        </td>
    </tr>
<?php
}
$this->end();
unset($shipment);
?>