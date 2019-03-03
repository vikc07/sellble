<?php
$action = ($this->fetch('removeAction')) ? $this->fetch('removeAction') : 'del';
$heading = ($this->fetch('heading')) ? $this->fetch('heading') : $this->fetch('operation') . ' ' . $this->fetch('title');
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
        <h3><?php echo $heading; ?></h3>
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
            $this->myHtml->icon(array('type' => 'save')) . ' Save',
            array('type' => 'submit', 'class' => 'btn btn-success', 'div' => false, 'escape' => false)
        );
        ?>
        <?php
        if($this->fetch('id')){
            echo $this->myHtml->button(
                array(
                    'icon' => array('type'=>'delete'),
                    'title' => 'Delete',
                    'type' => 'danger',
                    'args' => array('action' => $action, $this->fetch('id'))
                )
            );
        }
        ?>
        <?php
        echo $this->fetch('otherButtons');
        ?>
        <span id='spinner'></span>
    </div>
</div>