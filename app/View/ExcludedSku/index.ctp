<?php
$this->extend('/Common/index');
$this->assign('title', 'Exclusion');
$this->assign('heading', 'Excluded SKUs');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>

<?php echo $this->Form->create('ExcludedSku', array('novalidate' => true)); ?>
<div class="col-md-2">
    <?php
    echo $this->Form->input(
        'exclusion_year',
        array(
            'label' => false,
            'type' => 'select',
            'class' => 'form-control',
            'options' => $exclusion_years,
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
<div class="col-md-5">
    <?php
    echo $this->Form->input(
        'q',
        array(
            'label' => false,
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'Search for SKU ID or item',
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
    <th></th>
    <th class="text-right">Date</th>
    <th>SKU ID</th>
    <th>SKU</th>
    <th class="text-right">Quantity</th>
    <th class="text-right">Value</th>
    <th>Reason</th>
</tr>
</thead>
<tbody>
<?php
$previous_sku = '';
$totalValue = 0;
$totalUnits = 0;
foreach($excluded_skus as $excluded_sku){
    $brand = $excluded_sku['Sku']['Item']['Brand'][0]['Brand'];
    $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
    $skuItem = $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $excluded_sku['Sku']['Item']['fullName'];
    ?>
    <tr>
        <td>
            <?php
            echo $this->myHtml->button(
                array(
                    'icon' => array('type' => 'edit'),
                    'type' => 'primary',
                    'class' => 'btn-xs',
                    'title' => 'Edit',
                    'args' => array('action' => 'edit',$excluded_sku['ExcludedSku']['id'])
                )
            );
            ?>
        </td>
        <td class="text-right">
            <?php
            $year = $this->Time->format($excluded_sku['ExcludedSku']['created'], '%Y');
            echo $this->Time->format($excluded_sku['ExcludedSku']['created'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td><?php echo $excluded_sku['ExcludedSku']['idFormattedSku']; ?></td>
        <td><?php echo $skuItem; ?></td>
        <td class="text-right"><?php echo $excluded_sku['ExcludedSku']['quantity'] ?></td>
        <td class="text-right">
            <?php
            $totalValue += $excluded_sku['ExcludedSku']['quantity']*$excluded_sku['Sku']['per_unit_price_amt'];
            $totalUnits += $excluded_sku['ExcludedSku']['quantity'];
            echo $this->Number->currency(
                $excluded_sku['ExcludedSku']['quantity']*$excluded_sku['Sku']['per_unit_price_amt'],
                'USD',
                array('after' => false)
            );
            ?>
        </td>
        <td><?php echo $excluded_sku['ExcludeReason']['name']; ?></td>
        <td>
            <?php
            if($excluded_sku['ExcludedSku']['notes']){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'comment',
                        'toolTip' => $excluded_sku['ExcludedSku']['notes']
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
?>
</tbody>
<tfoot>
<tr>
    <th></th>
    <th></th>
    <th></th>
    <th>Net</th>
    <th class="text-right"><?php echo $totalUnits; ?></th>
    <th class="text-right">
        <?php
        echo $this->Number->currency(
            $totalValue,
            'USD',
            array('after' => false)
        );
        ?>
    </th>
    <th></th>
</tr>
</tfoot>
<?php
$this->end();
unset($sku);
?>
