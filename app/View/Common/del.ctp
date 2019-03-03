<div class='row'>
    <div class='col-md-6 '>
        <?php
        echo $this->Html->Link(
            'Go back',
            array('action' => 'index')
        );
        ?>
    </div>
</div>
<?php
if($this->fetch('doNotDelete')){
?>
    <div class='row'>
        <div class='col-md-6 '>
            <h3><?php echo 'This ' . $this->fetch('title') . ' cannot be deleted because of dependencies.'; ?></h3>
        </div>
    </div>
    <?php
}
else{
?>
<div class='row'>
    <div class='col-md-6 '>
        <h3><?php echo 'Are you sure you want to delete this ' . $this->fetch('title') . '?'; ?></h3>
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
            $this->myHtml->icon(array('type' => 'check')) . ' Yes',
            array('type' => 'submit', 'class' => 'btn btn-danger', 'div' => false, 'escape' => false)
        );
        ?>
        <?php
        echo $this->myHtml->button(
            array(
                'icon' => array('type'=>'undo'),
                'title' => 'No',
                'type' => 'success',
                'args' => array( 'action' => 'index' )
            )
        );
        ?>
        <span id='spinner'></span>
    </div>
</div>
<?php
}
?>