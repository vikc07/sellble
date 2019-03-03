<?php
$this->extend('/Common/index');
$this->assign('title', 'Sale');
$this->assign('heading', 'Sales Summary');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->assign('hidePagination', true);
$this->assign('resetAction','summary');
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
$dataForYearTrend = array();
foreach($sales as $year => $sale_months){
    foreach($sale_months as $month => $sale_categories){
        foreach($sale_categories as $category => $sale){
            if(!isset($dataForChart[$category])){
                $dataForChart[$category]['sale'] = 0;
            }

            if(!isset($dataForYearTrend[$year][$month])){
                $dataForYearTrend[$year][$month] = 0;
            }

            $dataForChart[$category]['sale'] += $sale['collected_amt'];
            $dataForYearTrend[$year][$month] += $sale['collected_amt'];

            $grand_totals['quantity'] += $sale['quantity'];
            $grand_totals['collected_amt'] += $sale['collected_amt'];
            $grand_totals['cost_amt'] += $sale['cost_amt'];
            $grand_totals['valueadd_amt'] += $sale['valueadd_amt'];
            //$grand_totals['list_fee_amt'] += $sale['list_fee_amt'];
            $grand_totals['fee_amt'] += $sale['fee_amt'];
            $grand_totals['shipping_amt'] += $sale['shipping_amt'];
            $grand_totals['return_amt'] += $sale['return_amt'];
            $grand_totals['net_amt'] += $sale['net_amt'];
            $grand_totals['tax_collected_amt'] += $sale['tax_collected_amt'];

            $net_percent = $sale['net_amt']/$sale['collected_amt'];
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
            <?php echo $this->Number->currency($sale['fee_amt'], 'USD', array('after' => false)); ?>
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
$data = array(array('Period', 'Value ($)'));
foreach($dataForYearTrend as $year => $months){
    foreach($months as $month => $value)
    $data[] = array(
        $year . ' ' . $month,
        floatval($value)
    );
}
?>
    <div class='row'>
        <div class='col-md-12'>
            <h1>Trend</h1>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-12">
            <?php
            $args = array(
                'id'	=>	"summary_chart2",
                'width' => '100%',
                'height' => '500px',
                'type'	=>	"column"
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
unset($sale);
?>