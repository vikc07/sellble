<?php
$this->extend('/Common/index');
$this->assign('title', 'Shipment Tracking');
$this->assign('heading', 'Shipment Tracking');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('table');
?>

    <thead>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>Item</th>
        <th>Tracking#</th>
        <th>ETA</th>
        <th>Last Checked</th>
        <th></th>
    </tr>
    </thead>
    <?php
    foreach($trackings as $tracking){
        $idFormatted = '';
        $id = '';
        $controller = '';
        $item = '';
        $market = '';
        $type = '';
        if($tracking['Tracking']['shipment_id']){
            $shipment = $tracking['Shipment'][0]['Shipment'];
            $sale = $shipment['Sale'][0]['Sale'];
            $listing = $sale['Listing'][0]['Listing'];
            $marketplace = $listing['Marketplace'][0]['Marketplace'];

            $idFormatted = $shipment['idFormatted'];
            $id = $shipment['id'];
            $controller = 'shipment';

            $item = $listing['Sku']['item_id'];
            $type = 'Sale';

            if($marketplace['logoFull']){
                $market = $this->myHtml->imageLogo(
                        $marketplace['logoFull']
                );
            }
            else{
                $market = $marketplace['name'];
            }
        }
        else if($tracking['Tracking']['sale_return_id']){
            $return = $tracking['SaleReturn'][0]['SaleReturn'];
            $sale = $return['Sale'][0]['Sale'];
            $listing = $sale['Listing'][0]['Listing'];
            $marketplace = $listing['Marketplace'][0]['Marketplace'];

            $idFormatted = $return['idFormatted'];
            $id = $return['id'];
            $controller = 'shipment';

            $item = $listing['Sku']['item_id'];
            $type = 'Return';

            if($marketplace['logoFull']){
                $market = $this->myHtml->imageLogo(
                    $marketplace['logoFull']
                );
            }
            else{
                $market = $marketplace['nameFull'];
            }
        }
        else if($tracking['Tracking']['purchase_line_id']){
            $purchase = $tracking['PurchaseLine'][0]['PurchaseLine']['Purchase'][0]['Purchase'];
            $idFormatted = $purchase['idFormatted'];
            $id = $purchase['id'];
            $controller = 'purchase';
            $item = $tracking['PurchaseLine'][0]['PurchaseLine']['item_id'];
            $type = 'Purchase';
            if($purchase['Marketplace'][0]['Marketplace']['logoFull']){
                $market = $this->myHtml->imageLogo(
                    $purchase['Marketplace'][0]['Marketplace']['logoFull']
                );
            }
            else{
                $market = $purchase['Marketplace'][0]['Marketplace']['nameFull'];
            }
        }
        ?>
        <tr>
            <td>
                <?php
                $classes = array(
                    'Sale' => "label-danger",
                    'Purchase' => "label-warning",
                    'Return' => "label-info"
                );
                ?>
                <h5>
                                <span class="label <?php echo $classes[$type]; ?>">
                                <?php echo $type; ?>
                                </span>
                </h5>
            </td>
            <td><?php echo $market; ?></td>
            <td>
                <?php
                $carrier = $tracking['Carrier'][0]['Carrier'];
                if(isset($carrier['logo'])){
                    echo $this->myHtml->imageLogo(
                        $carrier['logoFull']
                    );
                }
                else if(isset($carrier['nameFull'])){
                    echo $carrier['nameFull'];
                }
                ?>
            </td>
            <td>
                <?php
                echo $this->Html->link(
                    $idFormatted,
                    array('controller' => $controller, 'action' => 'edit', $id),
                    array(
                        'target' => '_blank'
                    )
                );
                ?>
            </td>
            <td>
                <?php
                echo $items[$item];
                ?>
            </td>
            <td>
                <?php
                echo $this->Html->link(
                    $tracking['Tracking']['tracking_no'],
                    $carrier['tracking_url'] . $tracking['Tracking']['tracking_no'],
                    array(
                        'target' => '_blank'
                    )
                );
                ?>
            </td>
            <td>
                <?php
                $text = '';
                if($tracking['Tracking']['delivered_flg']){
                    if($this->Time->isToday($tracking['Tracking']['delivered_on_dt'])){
                        $text = '<span class="label label-success label-tracking-eta">Delivered Today</span>';
                    }
                    else if($this->Time->wasYesterday($tracking['Tracking']['delivered_on_dt'])){
                        $text = '<span class="label label-warning label-tracking-eta">Delivered Yesterday</span>';
                    }
                    else{
                        $text = 'Delivered on ';
                        $year = $this->Time->format($tracking['Tracking']['delivered_on_dt'], '%Y');
                        $text .= $this->Time->format($tracking['Tracking']['delivered_on_dt'], '%e %b');
                        if($year <> date('Y')){
                            $text .= ' ' . $year;
                        }
                    }
                }
                else if($tracking['Tracking']['eta'] > 0){
                    if($this->Time->isToday($tracking['Tracking']['eta'])){
                        $text = '<span class="label label-info label-tracking-eta">Today</span>';
                    }
                    else if($this->Time->isTomorrow($tracking['Tracking']['eta'])){
                        $text = '<span class="label label-danger label-tracking-eta">Tomorrow</span>';
                    }
                    else{
                        $year = $this->Time->format($tracking['Tracking']['eta'], '%Y');
                        $text .= $this->Time->format($tracking['Tracking']['eta'], '%a %b %e');
                        if($year <> date('Y')){
                            $text .= ' ' . $year;
                        }
                    }
                }
                echo $text;
                ?>
            </td>
            <td>
                <?php
                echo (!$tracking['Tracking']['delivered_flg'])?$this->Time->timeAgoInWords($tracking['Tracking']['modified']):'';
                ?>
            </td>
            <td>
                <?php
                    if(!$tracking['Tracking']['delivered_flg']){
                        echo $this->myHtml->button(
                            array(
                                'icon' => array(
                                    'type' => 'edit'
                                ),
                                'title' => 'Mark Delivered',
                                'size' => 'xs',
                                'args' => array('action' => 'mark_delivered', $tracking['Tracking']['id'])
                            )
                        );
                    }
                ?>
            </td>
        </tr>
    <?php
    }
$this->end();
unset($tracking);
?>