<?php
$this->extend('/Common/index');
$this->assign('title', 'Customer');
$this->assign('heading', 'Customers');
$this->assign('table_width', '12');
$this->start('search');
?>
<?php echo $this->Form->create('Customer', array('novalidate' => true)); ?>
    <div class="col-md-5">
        <?php
        echo $this->Form->input(
            'q',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Search for a customer'
            )
        );
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo $this->Form->input(
            'state',
            array(
                'label' => false,
                'type' => 'select',
                'class' => 'form-control',
                'empty' => array(0 => 'Select Billing or Shipping State')
            )
        );
        ?>
    </div>
<?php
$this->end();
$this->start('table');
?>
    <thead>
    <tr>
        <th><?php echo $this->Paginator->sort('Customer.idFormatted', 'ID'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.email', 'Email'); ?></th>
        <th>eBay ID</th>
        <th><?php echo $this->Paginator->sort('Customer.bill_name', 'Billing Name'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.bill_city', 'Billing City'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.bill_state', 'Billing State'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.bill_country', 'Billing Country'); ?></th>
        <th><?php echo $this->Paginator->sort('Customer.bill_zip', 'Billing Zip'); ?></th>
        <th>Shipping Address Same as Billing?</th>
    </tr>
    </thead>
<?php
foreach($customers as $customer){
    ?>
    <tr>
        <td>
            <?php
            echo $this->Html->link(
                $customer['Customer']['idFormatted'],
                array('action' => 'edit', $customer['Customer']['id'] )
            );
            ?>
        </td>
        <td><?php echo $customer['Customer']['email']; ?></td>
        <td><?php echo $customer['Customer']['ebay_id']; ?></td>
        <td><?php echo $customer['Customer']['bill_name']; ?></td>
        <td><?php echo $customer['Customer']['bill_city']; ?></td>
        <td>
            <?php echo ($customer['BillingUsState']['name'])?$customer['BillingUsState']['name'] . ' - ' . $customer['BillingUsState']['full_nm']:''; ?>
        </td>
        <td><?php echo $customer['Customer']['bill_country']; ?></td>
        <td><?php echo $customer['Customer']['bill_zip']; ?></td>
        <td><?php echo ($customer['Customer']['ship_address_same_as_bill'])?'Y':'N'; ?></td>
    </tr>
<?php
}
$this->end();
unset($customer);
?>