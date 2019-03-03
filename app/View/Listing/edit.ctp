<?php
$this->extend('/Common/save');
$this->assign('title', 'Listing');
$this->assign('operation', $operation);
if(isset($listing['Listing']['id'])){
    $this->assign('id', $listing['Listing']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Listing',array('novalidate'=>true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
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
        <?php
        echo $this->Form->input(
            'sku_id',
            array(
                'type' => 'hidden',
                'value' => $sku['Sku']['id']
            )
        );
        ?>
        <span id="sellble-ebay-fee-percent" style="display:none;"><?php echo $ebay_fee_percent; ?></span>
        <span id="sellble-sales-tax-rate" style="display:none;"><?php echo Configure::read('salesTaxRate'); ?></span>
    </div>
</div>
<div class='row'>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'marketplace_id',
            array(
                'type' => 'select',
                'label' => 'Marketplace',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => '',
				'default' => 1
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'listing_dt',
            array(
                'type' => 'text',
                'label' => 'Listing Date',
                'class' => 'form-control sellble-date sellble-listing-date',
                'default' => date('Y-m-d'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'duration',
            array(
                'type' => 'text',
                'label' => 'Duration (days)',
                'class' => 'form-control sellble-listing-duration',
                'default' => 365,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'end_dt',
            array(
                'type' => 'text',
                'label' => 'End Date',
                'class' => 'form-control sellble-date sellble-listing-end-date',
                'default' => date('Y-m-d', strtotime("+365 days")),
                'readonly' => true,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
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
                'class' => 'form-control text-right sellble-quantity sellble-listing-quantity',
                'enable' => false,
                'default' => 1,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
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
                'enable' => false,
                'default' => 14,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            "list_fee_amt",
            array(
                'type' => 'text',
                'default' => '0.00',
                'label' => 'Listing Fee',
                'class' => 'form-control text-right sellble-amount sellble-listing-fee sellble-listing-amount',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                'after' => "</div>"
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
                'label' => 'Per Unit List Price',
                'class' => 'form-control text-right sellble-amount sellble-listing-price sellble-listing-amount',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                'after' => "</div>"
            )
        );
        ?>
    </div>
</div>
<fieldset>
    <legend>Estimates (Per Unit)</legend>
    <div class="row">
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_fv_fee_amt",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'FV Fee',
                    'class' => 'form-control text-right sellble-amount sellble-listing-charge sellble-listing-amount sellble-listing-ebay-fee',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_paypal_fee",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'PayPal Fee',
                    'class' => 'form-control text-right sellble-amount sellble-listing-charge sellble-listing-amount sellble-listing-paypal-fee',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_shipping_amt",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'Outbound Shipping',
                    'class' => 'form-control text-right sellble-amount sellble-listing-charge sellble-listing-amount',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_sales_tax_amt",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'Tax',
                    'class' => 'form-control text-right sellble-amount sellble-listing-charge sellble-listing-amount sellble-listing-tax',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_other_amt",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'Other',
                    'class' => 'form-control text-right sellble-amount sellble-listing-charge sellble-listing-amount',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "est_net_amt",
                array(
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'Net',
                    'class' => 'form-control text-right sellble-amount sellble-listing-net',
                    'disabled' => true,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
        </div>
    </div>
</fieldset>
<div class="row">
    <div class="col-md-12">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-md-2 col-md-offset-10">
        <?php
        echo $this->Form->input(
            "est_net_amt_grand",
            array(
                'type' => 'text',
                'default' => '0.00',
                'label' => 'Grand Net',
                'class' => 'form-control text-right sellble-amount sellble-listing-grand-net',
                'disabled' => true,
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                'after' => "</div>"
            )
        );
        ?>
    </div>
</div>
<?php
echo $this->end();
?>