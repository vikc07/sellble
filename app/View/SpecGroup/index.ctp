<?php
$this->extend('/Common/index');
$this->assign('title', 'Spec Group');
$this->assign('heading', 'Specification Groups');
$this->assign('table_width', '6');
$this->start('table');
?>

    <thead>
    <tr>
        <th>Name</th>
    </tr>
    </thead>
<?php
foreach($spec_groups as $spec_group){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $spec_group['SpecGroup']['name'],
                array('action' => 'edit', $spec_group['SpecGroup']['id'])
            );
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($spec_group);
?>