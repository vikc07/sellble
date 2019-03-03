<div class='row'>
    <div class='col-md-12'>
        <h1><?php echo $this->fetch('heading'); ?></h1>
    </div>
</div>
<?php
if(!$this->fetch('hideAddButton')){
?>
<div class='row'>
    <div class='col-md-12'>
        <?php
        echo $this->myHtml->button(
            array(
                'icon' => array('type' => 'plus'),
                'type' => 'primary',
                'title' => (($this->fetch('addButtonPreTitle'))?$this->fetch('addButtonPreTitle'):'Add') . ' ' . $this->fetch('title'),
                'args' => array('action' => $this->fetch('addButtonAction')?$this->fetch('addButtonAction'):'edit')
            )
        );
        ?>
    </div>
</div>
<?php
}

if(!$this->fetch('hidePagination')){
?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Paginator->numbers();
        ?>
    </div>
</div>
<?php
}
if($this->fetch('search')){
    ?>
    <div class="row">
        <?php echo $this->fetch('search'); ?>
        <div class='col-md-3'>
            <?php
            echo $this->Form->button(
                $this->myHtml->icon(array('type' => 'search')) . ' Search',
                array('type' => 'submit', 'class' => 'btn btn-success btn-upload', 'div' => false, 'escape' => false)
            );
            ?>
            <?php
            echo $this->myHtml->button(
                array(
                    'icon' => array('type'=>'undo'),
                    'title' => 'Reset',
                    'type' => 'danger',
                    'args' => array(
                        'action' => $this->fetch('resetAction')?$this->fetch('resetAction'):'index',
                        'search_reset' => true
                    )
                )
            );
            ?>
            <span id='spinner'></span>
        </div>
    </div>
<?php
}
if($this->fetch('belowSearch')){
    echo $this->fetch('belowSearch');
}
?>
<div class='row'>
    <div class='col-md-12'>
        <div class='row'>
            <div class='col-md-<?php echo $this->fetch('table_width'); ?>'>
                <table class='table table-hover table-condensed table-small-font' id="sellble-table">
                    <?php echo $this->fetch('table'); ?>
                </table>
            </div>
            <?php echo $this->fetch('other'); ?>
        </div>
    </div>
</div>
<?php
if($this->fetch('belowMainTable')){
    echo $this->fetch('belowMainTable');
}
if(!$this->fetch('hidePagination')){
?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Paginator->numbers();
        ?>
    </div>
</div>
<?php
}
?>