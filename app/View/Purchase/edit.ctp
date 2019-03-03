<?php
$this->extend('/Common/save');
$this->assign('title', 'Purchase');
$this->assign('operation', $operation);
$max = Configure::read('maxItemsInPurchase');
$readonly = false;
if(isset($purchase['Purchase']['id'])){
    $this->assign('id', $purchase['Purchase']['id']);
    $max = count($purchase['PurchaseLine']);
    $readonly = true;
}
$this->start('form');
?>
<?php echo $this->Form->create('Purchase', array('novalidate' => true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('Purchase.id', array('type' => 'hidden'));
}
?>
    <div class='row'>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.purchase_dt',
                array(
                    'type' => 'text',
                    'label' => 'Purchase Date',
                    'class' => 'form-control sellble-date',
                    'default' => date('Y-m-d'),
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.purchase_status_id',
                array(
                    'type' => 'select',
                    'label' => 'Status',
                    'class' => 'form-control',
                    'default' => 0,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.marketplace_id',
                array(
                    'type' => 'select',
                    'label' => 'Marketplace',
                    'class' => 'form-control',
                    'default' => 0,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'empty' => ''
                )
            ); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br>
        </div>
    </div>
<?php
echo $this->myForm->datalist(
    'sellble-items',
    $items
);
for($i = 0; $i < $max; $i++){
    if($operation == 'Edit'){
        echo $this->Form->input(
            "PurchaseLine.$i.id",
            array(
                'type' => 'hidden'
            )
        );
    }
    ?>
    <div class="row">
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                "PurchaseLine.$i.item_typeahead",
                array(
                    'type' => 'text',
                    'label' => 'Item',
                    'class' => 'form-control sellble-item-typeahead',
                    'list' => 'sellble-items',
                    'id' => "ItemTypeaheadLine$i",
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'empty' => '',
                    'readonly' => $readonly
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                "PurchaseLine.$i.item_id",
                array(
                    'type' => 'hidden',
                    'id' => "ItemLine$i",
                    'readonly' => $readonly
                )
            );
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo $this->Form->input(
                "PurchaseLine.$i.product_url",
                array(
                    'type' => 'text',
                    'label' => 'Product URL',
                    'class' => 'form-control',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                )
            );
            ?>
        </div>
        <div class='col-md-1'>
            <?php
            echo $this->Form->input(
                "PurchaseLine.$i.quantity",
                array(
                    'id' => "QtyForTotalLine$i",
                    'type' => 'number',
                    'label' => 'Quantity',
                    'default' => '0',
                    'readonly' => $readonly,
                    'class' => 'form-control text-right sellble-quantity sellble-qty-for-total',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                "PurchaseLine.$i.per_unit_price_amt",
                array(
                    'id' => "PerUnitForTotalLine$i",
                    'type' => 'text',
                    'default' => '0.00',
                    'label' => 'Per Unit Price',
                    'class' => 'form-control text-right sellble-amount sellble-per-unit-for-total',
                    'min' => 0,
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
                "PurchaseLine.$i.total_amt",
                array(
                    'id' => "TotalLine$i",
                    'type' => 'text',
                    'label' => 'Total',
                    'class' => 'form-control text-right sellble-amount sellble-for-grand-total sellble-total sellble-for-sales-tax',
                    'default' => '0.00',
                    'readonly' => true,
                    'min' => 0,
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
}
?>
    <div class="row">
        <div class="col-md-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class='col-md-1 col-md-offset-2'>
            <?php
            echo $this->Form->input(
                'Purchase.total_quantity',
                array(
                    'type' => 'text',
                    'label' => 'Total Qty',
                    'class' => 'form-control text-right sellble-total-quantity',
                    'min' => 0,
                    'default' => '0',
                    'readonly' => true,
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
                'Purchase.total_items',
                array(
                    'type' => 'text',
                    'label' => 'Total Items',
                    'class' => 'form-control text-right sellble-total-items',
                    'min' => 0,
                    'default' => '0',
                    'readonly' => true,
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
                'Purchase.other_amt',
                array(
                    'type' => 'text',
                    'label' => 'Other',
                    'class' => 'form-control text-right sellble-amount sellble-for-grand-total sellble-for-sales-tax',
                    'min' => 0,
                    'default' => '0.00',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            ); ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.sales_tax_amt',
                array(
                    'type' => 'text',
                    'label' => 'Tax',
                    'class' => 'form-control text-right sellble-amount sellble-for-grand-total sellble-sales-tax',
                    'min' => 0,
                    'default' => '0.00',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            );
            ?>
            <span id="sellble-sales-tax-rate" style="display:none;">
            <?php
            echo Configure::read('salesTaxRate');
            ?>
        </span>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.shipping_amt',
                array(
                    'type' => 'text',
                    'label' => 'Shipping',
                    'class' => 'form-control text-right sellble-amount sellble-for-grand-total',
                    'min' => 0,
                    'default' => '0.00',
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    ),
                    'between' => "<div class='input-group'><span class='input-group-addon'>$</span>",
                    'after' => "</div>"
                )
            ); ?>
        </div>
        <div class='col-md-2'>
            <?php
            echo $this->Form->input(
                'Purchase.grand_total_amt',
                array(
                    'type' => 'text',
                    'label' => 'Grand Total',
                    'class' => 'form-control text-right sellble-amount sellble-grand-total',
                    'default' => '0.00',
                    'min' => 0,
                    'readonly' => true,
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
    <div class='row'>
        <div class='col-md-12'>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
            echo $this->Form->input(
                'Purchase.notes',
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