<?php
$this->extend('/Common/index');
$this->assign('title', 'Spec Value');
$this->assign('heading', 'Spec Values');
$this->assign('table_width', '6');
$this->start('search');
?>
<?php echo $this->Form->create('SpecValue', array('novalidate' => true)); ?>
    <div class="col-md-4">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for name'
            )
        );
        ?>
    </div>
    <div class="col-md-4">
        <?php
        echo $this->Form->input(
            'spec_id',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => 'Select Spec'
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
        <th>Name</th>
        <th>Spec</th>
        <th>Spec Subgroup</th>
        <th>Spec Group</th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($spec_values as $spec_value){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $spec_value['SpecValue']['name'],
                array('action' => 'edit', $spec_value['SpecValue']['id'])
            );
            ?>
        </td>
        <td><?php echo $spec_value['Spec']['name'] ; ?></td>
        <td><?php echo $spec_value['Spec']['SpecSubgroup']['name'] ; ?></td>
        <td><?php echo $spec_value['Spec']['SpecSubgroup']['SpecGroup']['name'] ; ?></td>
        <td>
            <?php
            if($spec_value['SpecValue']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $spec_value['SpecValue']['logoFull'],
                        'args' => array(
                            'url'  => array('action' => 'upload', $spec_value['SpecValue']['id']),
                            'alt'  => $spec_value['SpecValue']['name'],
                            'title'=> 'Upload Logo',
                            'fullBase' => Configure::read('useFtpForStorage')
                        )
                    )
                );
                ?>

            <?php }
            else {
                echo $this->myHtml->icon(
                    array(
                        'type' => 'picture',
                        'title' => 'Upload Logo',	// ToolTip
                        'args' => array('action' => 'upload', $spec_value['SpecValue']['id'])
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($spec_value);
?>