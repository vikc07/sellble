<?php
$this->extend('/Common/index');
$this->assign('title', 'Service');
$this->assign('heading', 'Shipping Services');
$this->assign('table_width', '6');
$this->start('table');
?>
    <thead>
    <tr>
        <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
        <th>Service</th>
        <th>Tracking URL</th>
        <th></th>
    </tr>
    </thead>
<?php
foreach($carriers as $carrier){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $carrier['Carrier']['name'],
                array('action' => 'edit', $carrier['Carrier']['id'])
            );
            ?>
        </td>
        <td><?php echo $carrier['Carrier']['service']; ?></td>
        <td>
            <?php
            $tracking_url = $carrier['Carrier']['tracking_url'];
            echo (strlen($tracking_url) > 50) ? substr($tracking_url, 0, 50) . ' ...' : $tracking_url;
            ?>
        </td>
        <td>
            <?php
            if($carrier['Carrier']['logo']){
                echo $this->myHtml->image(
                    array(
                        'image' => $carrier['Carrier'][ 'logoFull' ],
                        'args' => array(
                            'url'  => array('action' => 'upload', $carrier['Carrier']['id']),
                            'alt'  => $carrier['Carrier']['name'],
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
                        'args' => array('action' => 'upload', $carrier['Carrier']['id'])
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($carrier);
?>