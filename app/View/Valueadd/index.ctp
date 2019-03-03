<?php
$this->extend('/Common/index');
$this->assign('title', 'Value Add');
$this->assign('heading', 'Value Adds');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>

<?php echo $this->Form->create('Valueadd', array('novalidate' => true)); ?>
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
        <th>SKU ID</th>
        <th>SKU</th>
        <th>Purchase Price</th>
        <th>Valueadds</th>
        <th></th>
        <th>Enhancement</th>
        <th>Component SKU ID</th>
        <th>Component</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
    </tr>
    </thead>
    <?php
    $previous_sku = '';
    $rowspan = '';
    $valueadd_counts = array();
    $valueadd_amounts = array();
    foreach($valueadds as $valueadd){
        if($valueadd['Valueadd']['sku_id'] == $previous_sku){
            $valueadd_counts[$valueadd['Valueadd']['sku_id']]++;
            if(isset($valueadd['ComponentSku']['per_unit_price_amt'])){
                $valueadd_amounts[$valueadd['Valueadd']['sku_id']] += $valueadd['ComponentSku']['per_unit_price_amt'];
            }
        }
        else{
            $valueadd_counts[$valueadd['Valueadd']['sku_id']] = 1;
            if(isset($valueadd['ComponentSku']['per_unit_price_amt'])){
                $valueadd_amounts[$valueadd['Valueadd']['sku_id']] = $valueadd['ComponentSku']['per_unit_price_amt'];
            }
            else{
                $valueadd_amounts[$valueadd['Valueadd']['sku_id']] = 0;
            }
        }
        $previous_sku = $valueadd['Valueadd']['sku_id'];
    }
    $total_amt = 0;
    $previous_sku = '';
    foreach($valueadds as $valueadd){
        $total_amt = $valueadd_amounts[$valueadd['Valueadd']['sku_id']];
        $total_valueadds = $valueadd_counts[$valueadd['Valueadd']['sku_id']];
        $rowspan="rowspan='$total_valueadds'";

        $brand = $valueadd['Sku']['Item']['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        $skuItem = $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $valueadd['Sku']['Item']['fullName'];
        ?>
        <tr>
            <?php
            if($valueadd['Valueadd']['sku_id'] <> $previous_sku){
                ?>
                <td <?php echo $rowspan; ?>><?php echo $valueadd['Valueadd']['idFormattedSku']; ?></td>
                <td <?php echo $rowspan; ?>><?php echo $skuItem; ?></td>
                <td <?php echo $rowspan; ?>><?php echo $this->Number->currency($valueadd['Sku']['per_unit_price_amt'], 'USD', array('after' => false)); ?></td>
                <td <?php echo $rowspan; ?>><?php echo $this->Number->currency($total_amt, 'USD', array('after' => false)); ?></td>
            <?php
            }
            ?>
            <td>
                <?php
                echo $this->myHtml->button(
                    array(
                        'icon' => array('type' => 'edit'),
                        'type' => 'primary',
                        'class' => 'btn-xs',
                        'title' => 'Edit',
                        'args' => array('action' => 'edit',$valueadd['Valueadd']['id'])
                    )
                );
                ?>
            </td>
            <td><?php echo $valueadd['Enhancement']['name']; ?></td>
            <td><?php echo $valueadd['Valueadd']['idFormattedComponentSku']; ?></td>
            <td>
                <?php
                if($valueadd['ComponentSku']['item_id']){
                    $brand = $valueadd['ComponentSku']['Item']['Brand'][0]['Brand'];
                    $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
                    $componentSkuItem = $manufacturer[ 'name' ] . ' - ' . $brand['name'] . ' - ' . $valueadd['ComponentSku']['Item']['fullName'];
                    echo $componentSkuItem;
                }
                ?>
            </td>
            <td>
                <?php
                if($valueadd['ComponentSku']['item_id']){
                    echo $this->Number->currency($valueadd['ComponentSku']['per_unit_price_amt'], 'USD', array('after' => false));
                }
                ?>
            </td>
            <td>
                <?php
                $class = 'success';
                $status = 'Complete';
                if(!$valueadd['Valueadd']['is_complete']){
                    $class = 'danger';
                    $status = 'Pending';
                }
                ?><h5>
                    <span class="label label-<?php echo $class; ?>"><?php print $status; ?></span></h5>
            </td>
            <td>
                <?php
                if($valueadd['Valueadd']['notes']){
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'comment',
                            'toolTip' => $valueadd['Valueadd']['notes']
                        )
                    );
                }
                ?>
            </td>
        </tr>
        <?php
        $previous_sku = $valueadd['Valueadd']['sku_id'];
    }
    $this->end();
    unset($sku);
    ?>
