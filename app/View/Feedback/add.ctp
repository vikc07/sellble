<div class='row'>
    <div class='col-md-6 '>
        <?php
        echo $this->Html->Link(
            'Go to Feedbacks',
            array('action' => 'index')
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-6 '>
        <h3><?php echo 'Add Month'; ?></h3>
    </div>
</div>
<?php echo $this->Form->create('Feedback', array('novalidate' => true)); ?>
<div class='row'>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'Feedback.year',
            array(
                'type' => 'text',
                'label' => 'Year',
                'class' => 'form-control',
                'default' => date('Y'),
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
<div class='row'>
    <div class='col-md-2'>
        <?php
        echo $this->Form->input(
            'Feedback.month',
            array(
                'type' => 'select',
                'label' => 'Month',
                'class' => 'form-control',
                'error' => array(
                    'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                )
            )
        );
        ?>
    </div>
</div>
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
            array('type' => 'submit', 'class' => 'btn btn-success btn-upload', 'div' => false, 'escape' => false)
        );
        ?>
        <span id='spinner'></span>
    </div>
</div>