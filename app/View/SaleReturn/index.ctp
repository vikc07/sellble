<?php
$this->extend('/Common/index');
$this->assign('title', 'Return');
$this->assign('heading', 'Returns');
$this->assign('table_width', '12');
$this->assign('hideAddButton', true);
$this->start('search');
?>
<?php echo $this->Form->create('SaleReturn', array('novalidate' => true)); ?>
    <div class="col-md-5">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for Return ID, Sale ID or Item',
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
            'return_status_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(0 => 'Select Status')
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
        <th class="text-right"><?php echo $this->Paginator->sort('return_dt', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('sale_id', 'Sale ID'); ?></th>
        <th>Item</th>
        <th class="text-right">Refund</th>
        <th class="text-right">Credits</th>
        <th>Tracking#</th>
        <th class="text-right"></th>
    </tr>
    </thead>
<?php
foreach($sale_returns as $sale_return){
    $sale = $sale_return['Sale'][0]['Sale'];
    ?>
    <tr>
        <td>
            <?php
            $classes = array(
                "",
                "label-danger",
                "label-warning",
                "label-info",
                "label-success"
            );
            ?>
            <h5>
                                <span class="label <?php echo $classes[$sale_return['ReturnStatus']['id']]; ?>">
                                <?php echo $sale_return['ReturnStatus']['name']; ?>
                                </span>
            </h5>
        </td>
        <td class="text-right">
            <?php
            $year = $this->Time->format($sale_return['SaleReturn']['return_dt'], '%Y');
            echo $this->Time->format($sale_return['SaleReturn']['return_dt'], '%e %b');
            if($year <> date('Y')){
                echo ' ' . $year;
            }
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale_return[ 'SaleReturn' ][ 'idFormatted' ],
                array('action' => 'edit', $sale_return['SaleReturn']['id'] )
            );
            ?>
        </td>
        <td>
            <?php
            echo $this->Html->link(
                $sale['idFormatted'],
                array('action' => 'edit', $sale_return['SaleReturn']['sale_id'] )
            );
            ?>
        </td>
        <td>
            <?php
            $listing = $sale['Listing'][0]['Listing'];
            $sku = $listing['Sku'];
            $item = $sku['Item'];
            $brand = $item['Brand'][0]['Brand'];
            $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];

            echo $manufacturer['name'] . ' - ' . $brand['name'] . ' - ' . $item['fullName'];
            ?>
        </td>
        <td class="text-right">
            <?php echo $this->Number->currency(
                $sale_return['SaleReturn']['refund_amt'],
                'USD',
                array('after' => false)
            );
            ?>
        </td>
        <td class="text-right">
            <?php echo $this->Number->currency(
                $sale_return['SaleReturn']['refund_credit_amt'],
                'USD',
                array('after' => false)
            );
            ?>
        </td>
        <td>
            <?php
            echo $sale_return['SaleReturn']['tracking_no'];
            ?>
        </td>
        <td>
            <?php
            if($sale_return['SaleReturn']['notes']){
                echo $this->myHtml->icon(
                    array(
                        'type' => 'comment',
                        'toolTip' => $sale_return['SaleReturn']['notes']
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
$this->end();
unset($sale_return);
?>