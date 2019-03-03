<?php
$this->extend('/Common/save');
$this->assign('title', 'eBay Listing');
$this->start('form');
?>
<?php echo $this->Form->create('EbayListing',array('novalidate'=>true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php
echo $this->Form->input(
    'listing_id',
    array(
        'type' => 'hidden',
        'value' => $listing['Listing']['id']
    )
);
?>
<div class='row'>
    <div class='col-md-12'>
        <h4>
            <?php
            $brand = $sku['Item']['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
            echo $sku['Sku']['idFormatted'] . ' - ' .
                $manufacturer['name'] . ' - ' .
                $brand['name'] . ' - ' .
                $sku['Item']['fullName'];
            ?>
        </h4>
    </div>
</div>
<div class="row">
    <div class='col-md-1'>
        <?php
        echo $this->Form->input(
            'quantity',
            array(
                'type' => 'text',
                'label' => 'Quantity',
                'class' => 'form-control text-right',
                'disabled' => true,
                'default' => 1,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'value' => $listing['Listing']['quantity']
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'return_period',
            array(
                'type' => 'text',
                'label' => 'Return Period (days)',
                'class' => 'form-control text-right',
                'disabled' => true,
                'default' => 14,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'value' => $listing['Listing']['return_period']
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            "list_price_amt",
            array(
                'type' => 'text',
                'default' => '0.00',
                'disabled' => true,
                'label' => 'Per Unit List Price',
                'class' => 'form-control text-right',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                'after' => "</div>",
                'value' => $listing['Listing']['list_price_amt']
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->input(
            'description',
            array(
                'type' => 'textarea',
                'label' => 'Description',
                'class' => 'form-control',
                'rows' => 20
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class='col-md-4'>
        <?php
        echo $this->Form->input(
            'ebay_item_no',
            array(
                'type' => 'text',
                'label' => 'eBay Item#',
                'class' => 'form-control',
                'default' => '',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class='col-md-8'>
        <?php
        // Generate default keywords
        $brand = $listing['Sku']['Item']['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        $category = $listing['Sku']['Item']['Category'];
        $keywords = strtolower(implode(',',explode(' ', $brand['name'])) . ',' .
            implode(',',explode(' ', $manufacturer['name'])) . ',' .
            implode(',',explode(' ', $category['name'])));
        echo $this->Form->input(
            'keywords',
            array(
                'type' => 'text',
                'label' => 'Keywords',
                'class' => 'form-control',
                'default' => $keywords,
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
$this->start('otherButtons');
echo $this->myHtml->button(
    array(
        'icon' => array('type'=>'list'),
        'title' => 'Get HTML for eBay Listing',
        'type' => 'danger',
        'linkTarget' => '_blank',
        'args' => array('action' => 'ebay_generate', $listing['Listing']['id'])
    )
);
$this->end();
?>
