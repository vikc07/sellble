<?php
$this->extend('/Common/index');
$this->assign('title', 'Inventory Snapshot');
$this->assign('heading', 'Inventory in last ' . $x . ' days');
$this->assign('table_width', '2');
$this->assign('hidePagination', true);
$this->assign('hideAddButton', true);
$this->start('table');
?>
    <thead>
    <tr>
        <th class="text-right">Date</th>
        <th class="text-right">Value</th>
        <th class="text-right">Units</th>
    </tr>
    </thead>
<?php
$data = array(array('Date', 'Value ($)', 'Units'));
foreach($totalLastXDays as $row){
    $data[] = array(
        $this->Time->format($row['Inventory']['snapshot_dt'] , '%e %b %Y'),
        floatval($row[0]['totalInvValue']),
        intval($row[0]['totalInvUnits'])
    );
    $today = '';
    if($row['Inventory']['snapshot_dt'] == date('Y-m-d')){
        $today = 'background:yellow';
    }
    ?>
    <tr style="<?php echo $today; ?>">
        <td class="text-right"><?php echo $this->Time->format($row['Inventory']['snapshot_dt'] , '%e %b %Y');; ?></td>
        <td class="text-right"><?php echo $this->Number->currency($row[0]['totalInvValue'], 'USD', array('after' => false)); ?></td>
        <td class="text-right"><?php echo $row[0]['totalInvUnits']; ?></td>
    </tr>
<?php
}
$this->end();
unset($row);
$this->start('other')
?>
    <div class="col-md-6">
        <?php
        $args = array(
            'id'	=>	"inventory_chart",
            'width' => '100%',
            'type'	=>	"line"
        );
        $options = array(
            'title' 	=>	"",
            'fontSize'	=>	12,
            'fontName'	=>  "Open Sans",
            'colors' => array('#ffcc00','#11cc00'),
            'pointSize' => 5
        );

        echo $this->myChart->draw($args, $data, $options);
        ?>
    </div>
<?php
$this->end();
$this->start('belowMainTable');
?>
    <div class='row'>
        <div class='col-md-12'>
            <h1>Inventory By Month</h1>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <div class='row'>
                <div class='col-md-<?php echo $this->fetch('table_width'); ?>'>
                    <table class='table table-hover table-condensed table-small-font' id="sellble-table">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th class="text-right">Value</th>
                            <th class="text-right">Units</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $data = array(array('Month', 'Value ($)', 'Units'));
                        foreach($totalMonthly as $row){
                            $data[] = array(
                                $this->Time->format($row['Inventory']['snapshot_dt'] , '%Y %b'),
                                floatval($row[0]['totalInvValue']),
                                intval($row[0]['totalInvUnits'])
                            );
                            ?>
                            <tr>
                                <td><?php echo $this->Time->format($row['Inventory']['snapshot_dt'] , '%Y');; ?></td>
                                <td><?php echo $this->Time->format($row['Inventory']['snapshot_dt'] , '%b');; ?></td>
                                <td class="text-right"><?php echo $this->Number->currency($row[0]['totalInvValue'], 'USD', array('after' => false)); ?></td>
                                <td class="text-right"><?php echo $row[0]['totalInvUnits']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <?php
                    $args = array(
                        'id'	=>	"inventory_chart2",
                        'width' => '100%',
                        'type'	=>	"bar"
                    );
                    $options = array(
                        'title' 	=>	"",
                        'fontSize'	=>	12,
                        'fontName'	=>  "Open Sans",
                        'colors' => array('#ffcc00','#11cc00'),
                        'pointSize' => 5
                    );

                    echo $this->myChart->draw($args, $data, $options);
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->end();
?>