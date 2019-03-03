<?php
$this->extend('/Common/save');
$this->assign('title', 'Customer');
$this->assign('operation', $operation);
if(isset($customer['Customer']['id'])){
    $this->assign('id', $customer['Customer']['id']);
}
$this->start('form');
?>
<?php echo $this->Form->create('Customer',array('novalidate'=>true)); ?>
<?php
if($operation == 'Edit'){
    echo $this->Form->input('id', array('type' => 'hidden'));
}
?>
    <div class="row">
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'email',
                array(
                    'type' => 'text',
                    'label' => 'Email',
                    'class' => 'form-control',
                    'enable' => false,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
        <div class='col-md-4'>
            <?php
            echo $this->Form->input(
                'ebay_id',
                array(
                    'type' => 'text',
                    'label' => 'or, enter eBay ID',
                    'class' => 'form-control',
                    'enable' => false,
                    'error' => array(
                        'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                    )
                )
            ); ?>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <fieldset>
            <legend>Billing Info</legend>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'bill_name',
                        array(
                            'type' => 'text',
                            'label' => 'Name',
                            'class' => 'form-control sellble-customer-bill-address-name sellble-customer-bill-address',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'bill_address_line_1',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 1',
                            'class' => 'form-control sellble-customer-bill-address-line-1 sellble-customer-bill-address',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'bill_address_line_2',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 2',
                            'class' => 'form-control sellble-customer-bill-address-line-2 sellble-customer-bill-address',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'bill_city',
                        array(
                            'type' => 'text',
                            'label' => 'City',
                            'class' => 'form-control sellble-customer-bill-address-city sellble-customer-bill-address',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'bill_us_state_id',
                        array(
                            'type' => 'select',
                            'label' => 'State',
                            'class' => 'form-control sellble-customer-bill-address-state sellble-customer-bill-address',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            ),
                            'empty' => ''
                        )
                    );
                    ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'bill_zip',
                        array(
                            'type' => 'text',
                            'label' => 'Zip',
                            'class' => 'form-control sellble-customer-bill-address-zip sellble-customer-bill-address',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'bill_country',
                        array(
                            'type' => 'text',
                            'label' => 'Country',
                            'class' => 'form-control sellble-customer-bill-address-country sellble-customer-bill-address',
                            'readonly' => true,
                            'value' => 'USA',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset>
            <legend>Shipping Info</legend>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'ship_name',
                        array(
                            'type' => 'text',
                            'label' => 'Name',
                            'class' => 'form-control  sellble-customer-ship-address-name',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'ship_address_line_1',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 1',
                            'class' => 'form-control  sellble-customer-ship-address-line-1',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'ship_address_line_2',
                        array(
                            'type' => 'text',
                            'label' => 'Address Line 2',
                            'class' => 'form-control sellble-customer-ship-address-line-2',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6'>
                    <?php
                    echo $this->Form->input(
                        'ship_city',
                        array(
                            'type' => 'text',
                            'label' => 'City',
                            'class' => 'form-control  sellble-customer-ship-address-city',
                            'enable' => false,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'ship_us_state_id',
                        array(
                            'type' => 'select',
                            'label' => 'State',
                            'class' => 'form-control  sellble-customer-ship-address-state',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            ),
                            'empty' => ''
                        )
                    );
                    ?>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'ship_zip',
                        array(
                            'type' => 'text',
                            'label' => 'Zip',
                            'class' => 'form-control sellble-customer-ship-address-zip',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'ship_country',
                        array(
                            'type' => 'text',
                            'label' => 'Country',
                            'class' => 'form-control sellble-customer-ship-address-country',
                            'readonly' => true,
                            'value' => 'USA',
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class='col-md-4'>
                    <?php
                    echo $this->Form->input(
                        'ship_address_same_as_bill',
                        array(
                            'type' => 'checkbox',
                            'label' => 'Same as billing address',
                            'class' => 'sellble-customer-address-flag',
                            'default' => 1,
                            'error' => array(
                                'attributes' => array('wrap' => 'div', 'class' => 'label label-danger')
                            )
                        )
                    );
                    ?>
                </div>
            </div>
        </fieldset>
    </div>
    </div>
<?php
$this->end();
?>