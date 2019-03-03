<?php
$this->extend('/Common/index');
$this->assign('title', 'Marketplace');
$this->assign('heading', 'Marketplaces');
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
foreach($marketplaces as $marketplace){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $marketplace[ 'Marketplace' ][ 'name' ],
                array('action' => 'edit', $marketplace['Marketplace']['id'])
            );
            ?>
        </td>
        <td>
            <?php
            if(($marketplace['Marketplace']['logo'])){
                echo $this->myHtml->image(
                    array(
                        'image' => $marketplace['Marketplace'][ 'logoFull' ],
                        'args' => array(
                            'url'  => array('action' => 'upload', $marketplace['Marketplace']['id']),
                            'alt'  => $marketplace[ 'Marketplace' ][ 'name' ],
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
                        'args' => array('action' => 'upload', $marketplace['Marketplace']['id'])
                    )
                );
            }
            ?>
        </td>
    </tr>
<?php
}
echo $this->end();
unset($marketplace);
?>