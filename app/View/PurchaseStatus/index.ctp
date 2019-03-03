<?php
$this->extend('/Common/index');
$this->assign('title', 'Purchase Status');
$this->assign('heading', 'Purchase Statuses');
$this->assign('table_width', '6');
$this->start('table');
?>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>
<?php
foreach($purchase_statuses as $purchase_status){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $purchase_status[ 'PurchaseStatus' ][ 'idFormatted' ],
                array('action' => 'edit', $purchase_status['PurchaseStatus']['id'])
            );
            ?></td>
        <td><?php echo $purchase_status['PurchaseStatus']['name']; ?></td>
    </tr>
<?php
}
$this->end();
unset($purchase_status);
?>