<?php
$this->extend('/Common/index');
$this->assign('title', 'Spec Subgroup');
$this->assign('heading', 'Specification Subgroups');
$this->assign('table_width', '6');
$this->start('table');
?>

    <thead>
    <tr>
        <th>Name</th>
        <th>Spec Group</th>
        <th>Order</th>
    </tr>
    </thead>
<?php
foreach($spec_subgroups as $spec_subgroup){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $spec_subgroup['SpecSubgroup']['name'],
                array('action' => 'edit', $spec_subgroup['SpecSubgroup']['id'])
            );
            ?>
        </td>
        <td><?php echo $spec_subgroup['SpecGroup']['name'] ; ?></td>
        <td><?php echo $spec_subgroup['SpecSubgroup']['order'] ; ?></td>
    </tr>
<?php
}
echo $this->end();
unset($spec_subgroup);
?>