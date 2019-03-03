<?php
$this->extend('/Common/index');
$this->assign('title', 'Purchase');
$this->assign('heading', 'Purchases Summary');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->assign('hidePagination', true);
$this->start('search');
?>
<?php echo $this->Form->create('Purchase', array('novalidate' => true)); ?>
    <div class="col-md-2">
        <?php
        echo $this->Form->input(
            'sale_year',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'options' => $purchase_years,
                'default' => date('Y'),
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
                'options' => $purchase_months,
                'default' => date('n'),
                'empty' => array(
                    '0' => 'Select Month'
                )
            )
        );
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo $this->Form->input(
            'category_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(
                    '0' => 'Select Category'
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
        <th>Year</th>
        <th>Month</th>
        <th>Category</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Collected</th>
        <th class="text-right">Cost</th>
        <th class="text-right">Value Adds</th>
        <th class="text-right">Fee*</th>
        <th class="text-right">Shipping</th>
        <th class="text-right">Return</th>
        <th class="text-right">Net</th>
        <th class="text-right">Net%</th>
        <th class="text-right">Tax Collected</th>
    </tr>
    </thead>
<?php
$grand_totals = array(
    'quantity' => 0,
    'collected_amt' => 0,
    'cost_amt' => 0,
    'valueadd_amt' => 0,
    //'list_fee_amt' => 0,
    'fee_amt' => 0,
    'shipping_amt' => 0,
    'return_amt' => 0,
    'net_amt' => 0,
    'net_percent' => '',
    'tax_collected_amt' => 0
);
$data = array(array('Category', 'Value ($)'));
$dataForChart = array();
foreach($purchases as $year => $purchase_months){
    foreach($purchase_months as $month => $purchase_categories){
        foreach($purchase_categories as $category => $purchase){
            if(!isset($dataForChart[$category])){
                $dataForChart[$category]['sale'] = 0;
            }
            $dataForChart[$category]['sale'] += $purchase['collected_amt'];

            $grand_totals['quantity'] += $purchase['quantity'];
            $grand_totals['collected_amt'] += $purchase['collected_amt'];
            $grand_totals['cost_amt'] += $purchase['cost_amt'];
            $grand_totals['valueadd_amt'] += $purchase['valueadd_amt'];
            //$grand_totals['list_fee_amt'] += $purchase['list_fee_amt'];
            $grand_totals['fee_amt'] += $purchase['fee_amt'];
            $grand_totals['shipping_amt'] += $purchase['shipping_amt'];
            $grand_totals['return_amt'] += $purchase['return_amt'];
            $grand_totals['net_amt'] += $purchase['net_amt'];
            $grand_totals['tax_collected_amt'] += $purchase['tax_collected_amt'];

            $net_percent = $purchase['net_amt']/$purchase['collected_amt'];
    ?>
    <tr>
        <td>
            <?php echo $year; ?>
        </td>
        <td>
            <?php echo $month; ?>
        </td>
        <td>
            <?php  echo $category;  ?>
        </td>
        <td class="text-right" >
            <?php echo $purchase['quantity']; ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['collected_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['cost_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['valueadd_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['fee_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['shipping_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['return_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right <?php echo ($purchase['net_amt']<0)?'red-text':''; ?>" >
            <?php echo $this->Number->currency($purchase['net_amt'], 'USD', array('after' => false)); ?>
        </td>
        <td class="text-right <?php echo ($net_percent<0)?'red-text':''; ?>" >
            <?php echo $this->Number->toPercentage($net_percent*100, 2); ?>
        </td>
        <td class="text-right" >
            <?php echo $this->Number->currency($purchase['tax_collected_amt'], 'USD', array('after' => false)); ?>
        </td>
    </tr>
<?php
        }
    }
}
?>
    <tfoot>
    <tr>
        <th>Net</th>
        <th></th>
        <th></th>
        <?php
        $grand_totals['net_percent'] = 0;
        if($grand_totals['collected_amt']){
            $grand_totals['net_percent'] = $grand_totals['net_amt']/$grand_totals['collected_amt'];
        }
        foreach($grand_totals as $key => $total){
            ?>
            <th class="text-right <?php echo ($total<0 and ($key == 'net_amt' or $key == 'net_percent'))?'red-text':''; ?>">
                <?php
                if($key == 'quantity'){
                    echo $total;
                }
                else if($key == 'net_percent'){
                    echo $this->Number->toPercentage($total*100, 2);
                }
                else{
                    echo $this->Number->currency($total, 'USD', array('after' => false));
                }
                ?>
            </th>
            <?php
        }
        ?>
    </tr>
    </tfoot>
<?php
$this->end();
$this->start('other');
?>
<script type="text/javascript">
    $('table').visualize();
</script>
<?php
$this->end();
$this->start('belowMainTable');
foreach($dataForChart as $category => $value){
    $data[] = array(
        $category,
        floatval($value['sale'])
    );
}
?>
    <div class="row">
        <div class="col-md-12">
            *Fees are only estimates
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <h1>Total Collected</h1>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-12">
            <?php
            $args = array(
                'id'	=>	"summary_chart",
                'width' => '100%',
                'height' => '500px',
                'type'	=>	"pie"
            );
            $options = array(
                'title' 	=>	"",
                'fontSize'	=>	12,
                'fontName'	=>  "Open Sans",
                'pointSize' => 5
            );

            echo $this->myChart->draw($args, $data, $options);
            ?>
        </div>
    </div>
<?php
$this->end();
unset($purchase);
?>