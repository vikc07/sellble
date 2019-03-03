<?php
$this->extend('/Common/index');
$this->assign('title', 'Listing');
$this->assign('heading', 'Listings');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>
<?php echo $this->Form->create('Listing', array('novalidate' => true)); ?>
    <div class="col-md-5">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for Listing ID or Item',
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
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'status',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(-1 => 'Select Status'),
                'default' => 0
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
        <th class="text-right"><?php echo $this->Paginator->sort('Listing.listing_dt', 'Starts'); ?></th>
        <th class="text-right"><?php echo $this->Paginator->sort('Listing.end_dt', 'Ends'); ?></th>
        <th><?php echo $this->Paginator->sort('Listing.id', 'ID'); ?></th>
        <th>SKU</th>
        <th>Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">List Price</th>
        <th class="text-right">Est. Fee</th>
        <th class="text-right">Est. Shipping</th>
        <th class="text-right">Est. Other</th>
        <th class="text-right">Est. Tax</th>
        <th class="text-right">Est. Net</th>
        <th class="text-right"></th>
        <th class="text-right"></th>
        <th class="text-right"></th>
    </tr>
    </thead>
<?php
foreach($listings as $listing){
    $marketplace = $listing['Marketplace'][0]['Marketplace'];
    ?>
    <tr>
        <td>
            <h5>
                                <span class="label label-<?php echo ($listing['Listing']['has_ended'])?'warning':'success'; ?>">
                                <?php echo ($listing['Listing']['has_ended'])?'Ended':'Active'; ?>
                                </span>
            </h5>
        </td>
        <td>
            <?php
            if($marketplace['logo']){
                echo $this->myHtml->imageLogo(
                    $marketplace['logoFull']
                );
            }
            else{
                echo $marketplace['name'];
            }
            ?>
        </td>
        <td class="text-right" >
            <?php
            $year = $this->Time->format($listing['Listing']['listing_dt'], '%Y');
            echo $this->Time->format($listing['Listing']['listing_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td class="text-right" >
            <?php
            $year = $this->Time->format($listing['Listing']['end_dt'], '%Y');
            echo $this->Time->format($listing['Listing']['end_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td >
            <?php
            echo $this->Html->link(
                $listing[ 'Listing' ][ 'idFormatted' ],
                array('action' => 'edit', $listing['Listing']['id'] )
            );
            ?>
        </td>
        <td>
            <?php echo $listing['Sku']['idFormatted']; ?>
        </td>
        <td >
            <?php
            $brand = $listing['Sku']['Item']['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];

            echo $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $listing['Sku']['Item']['fullName'];
            ?>
        </td>
        <td class="text-right" >
            <?php echo $listing['Listing']['quantity']; ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['list_price_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['est_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['est_shipping_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['est_other_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['est_sales_tax_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($listing['Listing']['est_net_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right">
            <?php
            if(!$listing['Listing']['has_ended'] and $listing['Sku']['quantity_avail'] > 0){
                echo $this->myHtml->button(
                    array(
                        'icon' => array('type' => 'plus'),
                        'type' => 'primary',
                        'class' => 'btn-xs',
                        'title' => 'Add Sale',
                        'args' => array('controller' => 'sale', 'action' => 'edit', 'listing' => $listing['Listing']['id'])
                    )
                );
            }
            ?>
        </td>
        <td>
            <?php
                if(!$listing['Listing']['has_ended']){
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'list',
                            'toolTip' => 'Generate eBay Listing',
                            'args' => array('action' => 'ebay', $listing['Listing']['id'])
                        )
                    );
                }
            ?>
        </td>
        <td>
            <?php
            if(isset($listing['EbayListing'][0]['EbayListing']) and !empty($listing['EbayListing'][0]['EbayListing'])){
                if($listing['EbayListing'][0]['EbayListing']['ebay_item_no']){
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'external',
                            'linkTarget' => '_blank',
                            'toolTip' => 'View eBay Listing',
                            'args' => 'http://www.ebay.com/itm/' . $listing['EbayListing'][0]['EbayListing']['ebay_item_no']
                        )
                    );
                }
            }
            ?>
        </td>
    </tr>
<?php
}
$this->end();
unset($listing);
?>