<?php
$this->extend('/Common/index');
$this->assign('title', 'Manufacturer');
$this->assign('heading', 'Manufacturers');
$this->assign('table_width', '6');
$this->start('table');
?>
    <thead>
    <tr>
        <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($manufacturers as $manufacturer){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $manufacturer['Manufacturer']['name'],
                array('action' => 'edit', $manufacturer['Manufacturer']['id'] )
            );
            ?>
        </td>
        <td>
            <?php
            if($manufacturer['Manufacturer']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $manufacturer['Manufacturer']['logoFull'],
                        'args' => array(
                            'url'  => array('action' => 'upload', $manufacturer['Manufacturer']['id']),
                            'alt'  => $manufacturer['Manufacturer']['name'],
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
                        'args' => array('action' => 'upload', $manufacturer['Manufacturer']['id'])
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($manufacturer);
?>