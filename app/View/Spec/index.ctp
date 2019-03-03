<?php
$this->extend('/Common/index');
$this->assign('title', 'Spec');
$this->assign('heading', 'Specifications');
$this->assign('table_width', '6');
$this->start('table');
?>

    <thead>
    <tr>
        <th>Name</th>
        <th>Spec Subgroup</th>
        <th>Spec Group</th>
        <th>Multi Value</th>
        <th>Hide From Public</th>
    </tr>
    </thead>
<?php
foreach($specs as $spec){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $spec['Spec']['name'],
                array('action' => 'edit', $spec['Spec']['id'])
            );
            ?>
        </td>
        <td><?php echo $spec['SpecSubgroup']['name'] ; ?></td>
        <td><?php echo $spec['SpecSubgroup']['SpecGroup']['name'] ; ?></td>
        <td><?php echo ($spec['Spec']['multi_value'])?'Yes':'' ; ?></td>
        <td><?php echo ($spec['Spec']['hide_from_public'])?'Yes':'' ; ?></td>
    </tr>
<?php
}
echo $this->end();
unset($spec);
?>