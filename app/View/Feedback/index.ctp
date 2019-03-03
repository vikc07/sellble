<?php
$this->extend('/Common/index');
$this->assign('title', 'Month');
$this->assign('heading', 'Feedbacks');
$this->assign('table_width', '3');
$this->assign('hidePagination', true);
$this->assign('addButtonAction', 'add');
$this->start('table');
?>
<table class='table table-hover table-condensed table-small-font'>
    <thead>
    <tr>
        <th>Year</th>
        <th>Month</th>
        <th class="text-right">Score</th>
        <th></th>
        <th class="text-right">Change</th>
        <th class="text-right">Change%</th>
    </tr>
    </thead>
    <?php
    $data1 = array(array('Month', 'Change'));
    $data2 = array(array('Month', 'Cum Score'));
    foreach($feedbacks as $feedback){
        $data1[] = array(
            $feedback['Feedback']['month'] . " " .
            $feedback['Feedback']['year'],
            $feedback['Feedback']['change']
        );

        $data2[] = array(
            $feedback['Feedback']['month'] . " " .
            $feedback['Feedback']['year'],
            (int)$feedback['Feedback']['score']
        );
        ?>
        <tr>
            <td><?php echo $feedback['Feedback']['year']; ?></td>
            <td><?php echo $feedback['Feedback']['month']; ?></td>
            <td class="text-right"><?php echo $feedback['Feedback']['score']; ?></td>
            <td>
                <?php
                if($feedback['Feedback']['year'] == date('Y') and $feedback['Feedback']['month'] == date('M')){
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'edit',
                            'args' => array(
                                'action' => 'edit',
                                $feedback['Feedback']['id']
                            )
                        )
                    );
                }
                ?>
            </td>
            <td class="text-right"><?php echo $feedback['Feedback']['change']; ?></td>
            <td class="text-right"><?php echo $this->Number->toPercentage($feedback['Feedback']['change_percent'], 2); ?></td>
        </tr>
    <?php
    }
    $this->end();
    unset($feedback);
    $this->start('other');
    ?>
    <div class="col-md-9">
        <?php
        $args = array(
            'id'	=>	"feedback_chart1",
            'width' => '100%',
            'type'	=>	"column"
        );
        $options = array(
            'title' 	=>	"Change",
            'fontSize'	=>	12,
            'fontName'	=>  "Open Sans",
            'colors'    => array('#ffcc00')
        );

        echo $this->myChart->draw($args, $data1, $options);

        $args = array(
            'id'	=>	"feedback_chart2",
            'width' => '100%',
            'type'	=>	"line"
        );
        $options = array(
            'title' 	=>	"Growth",
            'fontSize'	=>	12,
            'fontName'	=>  "Open Sans",
            'colors'    => array('#ffcc00'),
            'pointSize' => 5
        );
        echo $this->myChart->draw($args, $data2, $options);
        ?>
    </div>
<?php
$this->end();
?>