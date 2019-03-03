<?php
$this->extend('/Common/index');
$this->assign('title', 'Return Status');
$this->assign('heading', 'Return Statuses');
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
foreach($return_statuses as $return_status){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $return_status[ 'ReturnStatus' ][ 'idFormatted' ],
                array('action' => 'edit', $return_status['ReturnStatus']['id'])
            );
            ?></td>
        <td><?php echo $return_status['ReturnStatus']['name']; ?></td>
    </tr>
<?php
}
$this->end();
unset($return_status);
?>