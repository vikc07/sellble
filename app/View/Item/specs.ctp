<?php
$this->extend('/Common/save');
$this->assign('title', 'Item Specs');
if(isset($item['Item']['id'])){
    $this->assign('id', $item['Item']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('ItemSpec',array('novalidate'=>true)); ?>
<?php
$previous = 0;
foreach($allSpecs as $key => $spec){
    $existing = array();
    if(isset($usedSpecs[$spec['Spec']['id']])){
        $existing = $usedSpecs[$spec['Spec']['id']];
    }
    if($spec['SpecSubgroup']['id'] <> $previous){
?>
        <fieldset>
            <legend><?php echo $spec['SpecSubgroup']['name']; ?></legend>
        </fieldset>
    <?php
    }
    if(isset($existing['id'])){
        echo $this->Form->input(
            "ItemSpec.$key.id",
            array(
                'type' => 'hidden',
                'value' => $existing['id']
            )
        );
    }
    echo $this->Form->input(
        "ItemSpec.$key.item_id",
        array(
            'type' => 'hidden',
            'value' => $item['Item']['id']
        )
    );
    echo $this->Form->input(
        "ItemSpec.$key.spec_id",
        array(
            'type' => 'hidden',
            'value' => $spec['Spec']['id']
        )
    );
    ?>
    <div class='row'>
        <?php
        if($spec['Spec']['multi_value']){
            ?>
            <div class='col-md-4'>
                <?php
                echo $this->Form->input(
                    "ItemSpec.$key.spec_value_id",
                    array(
                        'type' => 'select',
                        'label' => $spec['Spec']['name'],
                        'class' => 'form-control',
                        'error' => array(
                            'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                        ),
                        'empty' => '',
                        'options' => $spec['SpecValue'],
                        'selected' => (!empty($existing))?$existing['spec_value_id']:''
                    )
                );
                ?>
            </div>
        <?php
        }
        else{
            ?>
            <div class='col-md-4'>
                <?php
                echo $this->Form->input(
                    "ItemSpec.$key.value",
                    array(
                        'type' => 'text',
                        'label' => $spec['Spec']['name'],
                        'class' => 'form-control',
                        'value' => (!empty($existing))?$existing['value']:''
                    )
                );
                ?>
            </div>
        <?php
        }
        ?>
    </div>
<?php
    $previous = $spec['SpecSubgroup']['id'];
}
$this->end();
?>