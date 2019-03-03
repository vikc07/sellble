<?php
$this->extend('/Common/index');
$this->assign('title', 'Sale');
$this->assign('heading', 'Sales Detail');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->assign('hidePagination', true);
$this->start('search');
?>
<?php echo $this->Form->create('Sale', array('novalidate' => true)); ?>
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'sale_year',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'options' => $sale_years,
                'default' => date('Y'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => array(
                    '0' => 'Select Year'
                )
            )
        );
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'sale_month',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'options' => $sale_months,
                'default' => date('n'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                ),
                'empty' => array(
                    '0' => 'Select Month'
                )
            )
        );
        ?>
    </div>
    <div class="col-md-2">
        <?php
        echo $this->myHtml->button(
            array(
                'icon' => array('type' => 'download'),
                'class' => 'sellble-download-csv',
                'type' => 'primary',
                'title' => 'Download CSV',
                'args' => '#'
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
        <th class="text-right">Date</th>
        <th>ID</th>
        <th>Category</th>
        <th>SKU</th>
        <th>Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Collected</th>
        <th class="text-right">Cost</th>
        <th class="text-right">Value Adds</th>
        <th class="text-right">Listing Fee</th>
        <th class="text-right">FV Fee</th>
        <th class="text-right">PayPal Fee</th>
        <th class="text-right">Other Fee</th>
        <th class="text-right">Shipping</th>
        <th class="text-right">Return</th>
        <th class="text-right">Net</th>
        <th class="text-right">Net%</th>
        <th class="text-right">Tax Collected</th>
    </tr>
    </thead>
<?php
foreach($sales as $sale){
    $net_percent = '';
    if($sale['collected_amt']){
        $net_percent = $sale['net_amt'] / $sale['collected_amt'];
    }
    ?>
    <tr>
        <td class="text-right" >
            <?php
            $year = $this->Time->format($sale['sale_dt'], '%Y');
            echo $this->Time->format($sale['sale_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale['idFormatted'],
                array('action' => 'edit', $sale['id'] )
            );
            ?>
        </td>
        <td>
            <?php echo $sale['category']; ?>
        </td>
        <td>
            <?php echo $sale['sku']; ?>
        </td>
        <td>
            <?php echo $sale['item']; ?>
        </td>
        <td class="text-right" >
            <?php echo $sale['quantity']; ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['collected_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['cost_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['valueadd_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['list_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['fv_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['paypal_fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['other_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['shipping_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['return_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right <?php echo ($sale['net_amt']<0)?'red-text':''; ?>" >
            <?php echo $this->Number->currency($sale['net_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right <?php echo ($net_percent<0)?'red-text':''; ?>" >
            <?php echo $this->Number->toPercentage($net_percent*100, 2); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($sale['tax_collected_amt'], 'USD', array('after' => false)); ?>
        </td>
    </tr>
<?php
}
$this->end();
$this->start('belowMainTable');
?>
<div class="row">
    <div class="col-md-12">
        *Fees are only estimates
    </div>
</div>
<?php
$this->end();
unset($sale);
?>