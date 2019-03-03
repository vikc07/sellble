<?php
$this->extend('/Common/index');
$this->assign('title', 'Brand');
$this->assign('heading', 'Brands');
$this->assign('table_width', '6');
$this->start('table');
?>
<thead>
<tr>
    <th><?php echo $this->Paginator->sort('name', 'Brand'); ?></th>
    <th><?php echo $this->Paginator->sort('Manufacturer.name', 'Manufacturer'); ?></th>
    <th>Logo</th>
</tr>
</thead>
<?php
foreach($brands as $brand){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $brand[ 'Brand' ][ 'name' ],
                array('action' => 'edit', $brand['Brand']['id'] )
            );
            ?>
        </td>
        <td><?php echo $brand['Manufacturer']['name']; ?></td>
        <td>
            <?php
            if(($brand['Brand']['logo'])){
                echo $this->myHtml->image(
                    array(
                        'image' => $brand['Brand'][ 'logoFull' ],
                        'args' => array(
                            'url'  => array('action' => 'upload', $brand['Brand']['id']),
                            'alt'  => $brand['Brand']['name'],
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
                        'args' => array('action' => 'upload', $brand['Brand']['id'])
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($brand);
?>
