<?php
$removeAction = 'removeLogo';
if($this->fetch('removeAction')){
    $removeAction = $this->fetch('removeAction');
}
?>
<div class='row'>
    <div class='col-md-6 '>
        <?php
        $back = ($this->request->referer() == '/')?array('action' => 'index'):$this->request->referer();
        $name = ($this->request->referer() == '/')?'Go to Index':'Go back';
        echo $this->Html->Link(
            $name,
            $back
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-6 '>
        <h3><?php echo $this->fetch('heading'); ?></h3>
    </div>
</div>
<?php echo $this->fetch('form'); ?>
<div class='row'>
    <div class='col-md-2'>
        <hr/>
    </div>
</div>
<div class='row'>
    <div class='col-md-4'>
        <?php
        echo $this->Form->button(
            $this->myHtml->icon(array('type' => 'upload')) . ' Upload',
            array('type' => 'submit', 'class' => 'btn btn-success btn-upload', 'div' => false, 'escape' => false)
        );
        ?>
        <?php
        echo $this->myHtml->button(
            array(
                'icon' => array('type'=>'delete'),
                'title' => 'Remove',
                'type' => 'danger',
                'args' => array('action' => $removeAction, $this->fetch('id'))
            )
        );
        ?>
        <span id='spinner'></span>
    </div>
</div>