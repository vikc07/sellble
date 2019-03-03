<?php
$siteDescription = __d( 'cake_dev', Configure::read('company') . ' | sellble' );
$controller = isset($this->params['controller'])?$this->params['controller']:false;
$action = isset($this->params['action'])?$this->params['action']:false;
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?> | <?php echo $siteDescription ?>
	</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?php
    echo $this->Html->meta(
        'favicon.png',
        '/favicon.png',
        array(
            'type' => 'icon'
        )
    );

    // Load jQuery
    echo $this->Html->script('http://code.jquery.com/jquery-2.0.3.min.js');

    // Load BootStrap
    echo $this->Html->css('//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css');
    echo $this->Html->script('//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js');

    // FontAwesome
    echo $this->Html->css('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

    // Load others
    echo $this->Html->script('ckeditor/ckeditor.js');
    echo $this->Html->script('ckeditor/adapters/jquery.js');
    echo $this->Html->script('http://ifightcrime.github.io/bootstrap-growl/jquery.bootstrap-growl.min.js');

    echo $this->Html->script('TableCSVExport.js');

    // Google Chart
    echo $this->Html->script('https://www.google.com/jsapi');

    // Google Map
    echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyA_KG3LHgcWVaASmw2EOReAooUqTHLfSlA&sensor=false');

    // Load custom js
    echo $this->Html->script('my.js');

    // Load custom css
    echo $this->Html->css('my');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-inverse container-fluid" role='navigation'>
        <div class='navbar-header'>
            <a class='navbar-left navbar-logo' href='<?php echo $this->webroot; ?>'>
                <?php
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('company_logo'),
                    )
                );
                ?>
            </a>
            <button type='button' class='navbar-toggle' data-toggle='collapse' data-target="#navbar-collapse-div">
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
        </div>
        <div class='collapse navbar-collapse' id="navbar-collapse-div">
            <ul class='nav navbar-nav'>
                <li class="<?php echo ($controller == 'purchase')?'active':''; ?>">
                    <?php echo $this->Html->link('Purchases', array('controller' => 'purchase','action'=>'index')); ?>
                </li>
                <li class="dropdown <?php echo ($controller == 'sku')?'active':''; ?>">
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Items&nbsp;<b class='caret'></b></a>
                    <ul class='dropdown-menu'>
                        <li class="<?php echo ($controller == 'item')?'active':''; ?>">
                            <?php echo $this->Html->link('Items', array('controller' => 'item','action'=>'index')); ?>
                        </li>
                        <li><?php echo $this->Html->link('SKUs', array('controller' => 'sku','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Exclusions', array('controller' => 'excluded_sku','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Inventory', array('controller' => 'inventory','action'=>'index')); ?></li>
                        <li class="<?php echo ($controller == 'valueadd')?'active':''; ?>">
                            <?php echo $this->Html->link('Value Adds', array('controller' => 'valueadd','action'=>'index')); ?>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo ($controller == 'listing')?'active':''; ?>">
                    <?php echo $this->Html->link('Listings', array('controller' => 'listing','action'=>'index')); ?>
                </li>
                <li class="<?php echo ($controller == 'sale' and $action == 'index')?'active':''; ?>">
                    <?php echo $this->Html->link('Sales', array('controller' => 'sale','action'=>'index')); ?>
                </li>
                <li class="<?php echo ($controller == 'shipment')?'active':''; ?>">
                    <?php echo $this->Html->link('Shipments', array('controller' => 'shipment','action'=>'index')); ?>
                </li>
                <li class="<?php echo ($controller == 'sale_return')?'active':''; ?>">
                    <?php echo $this->Html->link('Returns', array('controller' => 'sale_return','action'=>'index')); ?>
                </li>
                <li class="<?php echo ($controller == 'tracking')?'active':''; ?>">
                    <?php echo $this->Html->link('Tracking', array('controller' => 'tracking','action'=>'index')); ?>
                </li>
                <li class="dropdown">
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Reports&nbsp;<b class='caret'></b></a>
                    <ul class='dropdown-menu'>
                        <li><?php echo $this->Html->link('Sales Detail', array('controller' => 'sale','action'=>'detail')); ?></li>
                        <li><?php echo $this->Html->link('Sales Summary', array('controller' => 'sale','action'=>'summary')); ?></li>
                        <li><?php echo $this->Html->link('Where are my customers?', array('controller' => 'customer','action'=>'map')); ?></li>
                    </ul>
                </li>
                <li class='dropdown'>
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>More&nbsp;<b class='caret'></b></a>
                    <ul class='dropdown-menu'>
                        <li><?php echo $this->Html->link('Brands', array('controller' => 'brand','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Categories', array('controller' => 'category','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Customers', array('controller' => 'customer','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Enhancements', array('controller' => 'enhancement','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Exclude Reasons', array('controller' => 'exclude_reason','action'=>'index')); ?></li>
                        <li class="<?php echo ($controller == 'feedback')?'active':''; ?>">
                            <?php echo $this->Html->link('Feedbacks', array('controller' => 'feedback','action'=>'index')); ?>
                        </li>
                        <li><?php echo $this->Html->link('Manufacturers', array('controller' => 'manufacturer','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Marketplace', array('controller' => 'marketplace','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Purchase Status', array('controller' => 'purchase_status','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Return Status', array('controller' => 'return_status','action'=>'index')); ?></li>
                        <li><?php echo $this->Html->link('Shipping Services', array('controller' => 'carrier','action'=>'index')); ?></li>
                        <li class="dropdown-submenu">
                            <a href='#'>Specs</a>
                            <ul class='dropdown-menu'>
                                <li><?php echo $this->Html->link('Groups', array('controller' => 'specGroup','action'=>'index')); ?></li>
                                <li><?php echo $this->Html->link('Sub Groups', array('controller' => 'specSubgroup','action'=>'index')); ?></li>
                                <li><?php echo $this->Html->link('Specs', array('controller' => 'spec','action'=>'index')); ?></li>
                                <li><?php echo $this->Html->link('Values', array('controller' => 'specValue','action'=>'index')); ?></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class='dropdown '>
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Quick Links&nbsp;<b class='caret'></b></a>
                    <ul class='dropdown-menu'>
                        <li class=''><a href='http://www.ebay.com'>eBay</a></li>
                        <li class=''><a href='http://www.fees.ebay.com/feeweb/feecalculator/'>eBay Fee Calculator</a></li>
                        <li class=''><a href='http://www.ebay.com/shp/Calculator?_trkparms=clkid%3D1448094347468076768'>Shipping Calculator</a></li>
                        <li class=''><a href='https://tools.usps.com/go/ScheduleAPickupAction!input.action'>Schedule USPS Pickup</a></li>
                        <li class=''><a href='http://www.newegg.com'>Newegg</a></li>
                        <li class=''><a href='http://www.amazon.com'>Amazon</a></li>
                        <li class=''><a href='http://www.shopgoodwill.com'>Shopgoodwill</a></li>
                    </ul>
                </li>
            </ul>
        </div>
</nav>
<div id="container" class="container">
    <div id="header">

    </div>
    <div id="content">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
<br/><br/>
<div id="footer" class="row hidden-print">
    <div class="col-md-2">
        <h2 id="footer-logo">
            <?php
            echo $this->myHtml->image(
                array(
                    'image' => 'sellble-full-small.png'
                )
            );
            ?></h2>v<?php echo Configure::read('sellbleVersion'); ?>
        &copy;&nbsp;<?php
        echo date('Y');
        ?>
    </div>
    <div class="col-md-2 col-md-offset-8 text-right">

    </div>
</div>
</body>
</html>
