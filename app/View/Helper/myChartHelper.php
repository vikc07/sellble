<?php
class myChartHelper extends AppHelper{
    public function draw($args, $data, $options){
        $chart_types = array(
            'column'	=>	"ColumnChart",
            'line'		=>	"LineChart",
            'combo'		=>	"ComboChart",
            'bar'		=>	"BarChart",
            'pie'		=>	"PieChart",
            'table'		=>	"Table"
        );

        $width = "";
        if(isset($args['width']) and $args['width']){
            $width = "width: " . $args['width'];
        }

        $height = "";
        if(isset($args['height']) and $args['height']){
            $width = "height: " . $args['height'];
        }

        $id = $args['id'];
        $out = "";
        $out .= "
            <div id='$id' style='" . $width.$height . "'></div>

            <script type='text/javascript'>

              // Load the Visualization API and the piechart package.
              google.load('visualization', '1.0', {'packages':['corechart','table']});

              // Set a callback to run when the Google Visualization API is loaded.
              google.setOnLoadCallback(drawChart);

              // Callback that creates and populates a data table,
              // instantiates the pie chart, passes in the data and
              // draws it.
              function drawChart() {
                // Create the data table.
                var data = new google.visualization.arrayToDataTable(" . json_encode($data) . ");

                // Set chart options
                var options = " . json_encode($options) . "

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization." . $chart_types[$args['type']] . "(document.getElementById('$id'));
                chart.draw(data, options);
              }
            </script>";
        return $out;
    }
};

?>