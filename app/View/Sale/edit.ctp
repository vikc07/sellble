<?php
$this->extend('/Common/save');
$this->assign('title', 'Sale');
$this->assign('operation', $operation);
$readonly = false;
if(isset($sale['Sale']['id'])){
    $this->assign('id', $sale['Sale']['id']);
    $readonly = true;
}
$this->start('form');
?>
<?php echo $this->Form->create('Sale', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('Sale.id', array('type' => 'hidden'));
}
?>
    <div class='row'>
        <div class='col-md-12'>
            <h4>
                <?php
                $item = $listing['Sku']['Item'];
                $brand = $item['Brand'][0]['Brand'];
                $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
                echo $listing['Listing']['idFormatted'] . ' - ' .
                    $listing['Sku']['idFormatted'] . ' - ' .
                    $manufacturer['name'] . ' - ' .
                    $brand['name'] . ' - ' .
                    $item['fullName'];
                ?>
            </h4>
            <?php
            echo $this->Form->input(
                'listing_id',
                array(
                    'type' => 'hidden',
                    'value' => $listing['Listing']['id']
                )
            );
            ?>
            <span id="sellble-ebay-fee-percent" style="display:none;"><?php echo $ebay_fee_percent; ?></span>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Sale.sale_dt',
                array(
                    'type' => 'text',
                    'label' => 'Sale Date',
                    'class' => 'form-control sellble-date sellble-sale-date',
                    'default' => date('Y-m-d'),
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
        <div class='col-md-1'>
            <?php
            echo $this->Form->input(
                'Sale.quantity',
                array(
                    'type' => 'text',
                    'label' => 'Quantity',
                    'class' => 'form-control text-right sellble-quantity',
                    'disabled' => $readonly,
                    'default' => 1,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
    </div>
    <fieldset>
        <legend>Amounts collected from customer</legend>
        <div class="row">
            <div class='col-md-2'>
                <?php
                echo $this->Form->input(
                    "Sale.sale_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'Sale Amt',
                        'class' => 'form-control text-right sellble-amount sellble-sale-collected sellble-sale-amount',
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
                    "Sale.shipping_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'Shipping',
                        'class' => 'form-control text-right sellble-amount sellble-sale-collected sellble-sale-amount',
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
                    "Sale.sales_tax_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'Tax',
                        'class' => 'form-control text-right sellble-amount sellble-sale-collected sellble-sale-amount',
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
    <fieldset>
        <legend>Charges</legend>
        <div class="row">
            <div class='col-md-2'>
                <?php
                echo $this->Form->input(
                    "Sale.fv_fee_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'FV Fee',
                        'class' => 'form-control text-right sellble-amount sellble-sale-charge sellble-sale-amount sellble-sale-ebay-fee',
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
                    "Sale.paypal_fee_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'PayPal Fee',
                        'class' => 'form-control text-right sellble-amount sellble-sale-charge sellble-sale-amount sellble-sale-paypal-fee',
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
                    "Sale.other_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'Other',
                        'class' => 'form-control text-right sellble-amount sellble-sale-charge sellble-sale-amount',
                        'error' => array(
                            'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                        ),
                        'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                        'after' => "</div>"
                    )
                );
                ?>
            </div>
            <div class='col-md-2 col-md-offset-4'>
                <?php
                echo $this->Form->input(
                    "net_amt",
                    array(
                        'type' => 'text',
                        'default' => '0.00',
                        'label' => 'Net',
                        'class' => 'form-control text-right sellble-amount sellble-sale-net',
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
    <span id="scrollTo"></span>
    <fieldset>
    <legend>Customer</legend>
    <div class="row">
        <div class='col-md-10'>
            <?php
            echo $this->myForm->datalist(
                'sellble-customers',
                $customers
            );
            echo $this->Form->input(
                "Sale.customer_typeahead",
                array(
                    'type' => 'text',
                    'label' => 'Customer',
                    'class' => 'form-control sellble-sale-customer-typeahead',
                    'list' => 'sellble-customers',
                    'disabled' => $new_customer or $readonly,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            );
            ?>
        </div>
        <div class="col-md-1">
            <br/>
            <?php
            if(!$readonly){
                echo $this->myHtml->button(
                    array(
                        'icon' => array('type' => 'plus'),
                        'type' => 'primary',
                        'class' => 'btn-sm btn-sale-add-new-customer',
                        'title' => 'Add New Customer',
                        'args' => '#'
                    )
                );
            }
            ?>
        </div>
    </div>
    <?php
    if(!$readonly){
    ?>
    <div class="row" id="sellble-sale-new-customer" style="<?php echo ($new_customer)?'':'display:none'; ?>">
    <div class="col-md-12">
    <div class="row">
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'Customer.email',
                array(
                    'type' => 'text',
                    'label' => 'Email',
                    'class' => 'form-control sellble-sale-new-customer-info',
                    'disabled' => !$new_customer,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'Customer.ebay_id',
                array(
                    'type' => 'text',
                    'label' => 'or, enter eBay ID',
                    'class' => 'form-control sellble-sale-new-customer-info',
                    'disabled' => !$new_customer,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>Billing Info</legend>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_name',
                        array(
                            'type' => 'text',
                            'label' => 'Name',
                            'class' => 'form-control sellble-customer-bill-address-name sellble-customer-bill-address sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_address_line_1',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 1',
                            'class' => 'form-control sellble-customer-bill-address-line-1 sellble-customer-bill-address sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_address_line_2',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 2',
                            'class' => 'form-control sellble-customer-bill-address-line-2 sellble-customer-bill-address sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_city',
                        array(
                            'type' => 'text',
                            'label' => 'City',
                            'class' => 'form-control sellble-customer-bill-address-city sellble-customer-bill-address sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_us_state_id',
                        array(
                            'type' => 'select',
                            'label' => 'State',
                            'class' => 'form-control sellble-customer-bill-address-state sellble-customer-bill-address sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            ),
                            'empty' => '',
                            'disabled' => !$new_customer

                        )
                    );
                    ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_zip',
                        array(
                            'type' => 'text',
                            'label' => 'Zip',
                            'disabled' => !$new_customer,
                            'class' => 'form-control sellble-customer-bill-address-zip sellble-customer-bill-address sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.bill_country',
                        array(
                            'type' => 'text',
                            'label' => 'Country',
                            'disabled' => !$new_customer,
                            'class' => 'form-control sellble-customer-bill-address-country sellble-customer-bill-address sellble-sale-new-customer-info',
                            'readonly' => true,
                            'value' => 'USA',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset>
            <legend>Shipping Info</legend>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_name',
                        array(
                            'type' => 'text',
                            'label' => 'Name',
                            'disabled' => !$new_customer,
                            'class' => 'form-control  sellble-customer-ship-address-name sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_address_line_1',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 1',
                            'disabled' => !$new_customer,
                            'class' => 'form-control  sellble-customer-ship-address-line-1 sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_address_line_2',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 2',
                            'class' => 'form-control sellble-customer-ship-address-line-2 sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_city',
                        array(
                            'type' => 'text',
                            'label' => 'City',
                            'class' => 'form-control  sellble-customer-ship-address-city sellble-sale-new-customer-info',
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_us_state_id',
                        array(
                            'type' => 'select',
                            'label' => 'State',
                            'class' => 'form-control  sellble-customer-ship-address-state sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            ),
                            'empty' => '',
                            'disabled' => !$new_customer
                        )
                    );
                    ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_zip',
                        array(
                            'type' => 'text',
                            'label' => 'Zip',
                            'disabled' => !$new_customer,
                            'class' => 'form-control sellble-customer-ship-address-zip sellble-sale-new-customer-info',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_country',
                        array(
                            'type' => 'text',
                            'label' => 'Country',
                            'class' => 'form-control sellble-customer-ship-address-country sellble-sale-new-customer-info',
                            'readonly' => true,
                            'disabled' => !$new_customer,
                            'value' => 'USA',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'Customer.ship_address_same_as_bill',
                        array(
                            'type' => 'checkbox',
                            'label' => 'Same as billing address',
                            'class' => 'sellble-customer-address-flag sellble-sale-new-customer-info',
                            'default' => 1,
                            'disabled' => !$new_customer,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
        </fieldset>
    </div>
    </div>
    </div>
    </div>
    <?php
    }
    ?>
    </fieldset>
    <div class="row">
        <div class="col-md-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $this->Form->input(
                'Sale.notes',
                array(
                    'type' => 'textarea',
                    'label' => 'Notes',
                    'class' => 'form-control'
                )
            );
            ?>
        </div>
    </div>
<?php
$this->end();
?>