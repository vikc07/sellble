<?php
$this->extend('/Common/index');
$this->assign('title', 'Exclude Reason');
$this->assign('heading', 'Exclude Reasons');
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
foreach($exclude_reasons as $exclude_reason){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $exclude_reason['ExcludeReason']['idFormatted'],
                array('action' => 'edit', $exclude_reason['ExcludeReason']['id'])
            );
            ?></td>
        <td><?php echo $exclude_reason['ExcludeReason']['name']; ?></td>
    </tr>
<?php
}
$this->end();
unset($exclude_reason);
?>