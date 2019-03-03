<?php
$this->extend('/Common/index');
$this->assign('title', 'Customer');
$this->assign('heading', 'Where are my customers?');
$this->assign('table_width', '2');
$this->assign('hidePagination', true);
$this->assign('hideAddButton', true);
$this->start('table');
?>
    <thead>
    <tr>
        <th></th>
        <th>State</th>
        <th class="text-right">Count</th>
        <th class="text-right">%</th>
    </tr>
    </thead>
<?php
$total = 0;
foreach($states as $state){
    $total += $state[0]['customerCount'];
}
$i = 1;
foreach($states as $state){
    $percent = $state[0]['customerCount']/$total;
    ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $state['BillingUsState']['full_nm']; ?></td>
        <td class="text-right"><?php echo $state[0]['customerCount']; ?></td>
        <td class="text-right"><?php echo $this->Number->toPercentage($percent*100, 2); ?></td>
    </tr>
<?php
}
?>
    <tfoot>
    <tr>
    <th>Total</th>
    <th></th>
    <th class="text-right"><?php echo $total; ?></th>
    <th class="text-right">100%</th>
<?php
$this->end();
unset($state);
$this->start('other')
?>
    <div class="col-md-10">
        <script type="text/javascript">
            var map;
            var marker;
            function initialize(){
                var mapOptions = {
                    center: new google.maps.LatLng(40,-95),
                    zoom: 4,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDoubleClickZoom: false,
                    keyboardShortcuts: false,
                    overviewMapControl: false,
                    panControl: false,
                    scaleControl: false,
                    zoomControl: false,
                    rotateControl: false,
                    streetViewControl: false,
                    mapTypeControl: false
                };
                map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
                <?php
                foreach($geocodes as $geocode){
                ?>
                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(<?php echo $geocode['Geocode']['latlng']; ?>),
                    animation: google.maps.Animation.DROP,
                    //icon: 'img/map-marker.png',
                    clickable: true,
                    flat: true,
                    title: '<?php print $geocode['Geocode']['zip']; ?>'
                });
                <?php
                }
                ?>
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <div id='map-canvas'>
        </div>
    </div>
<?php
$this->end();
?>