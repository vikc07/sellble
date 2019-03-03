<style>
    /* Set up for Brother QL700 */
    .barcode{
        width:2in;
        height:0.4in;
        padding:0px !important;
        margin:0px !important;
        font-family:sans-serif;
        font-size:8px;
        text-align: center;
    }
    .barcode-img{
        height:50%;
        width:100%;
    }

</style>
<div class="barcode">
    <?php
    echo $this->myHtml->image(
        array(
            'image' => $skuIdFormatted . '.png',
            'args' => array(
                'class' => 'barcode-img'
            )
        )
    );
    ?>
    <span><?php echo '<br/>' . Configure::read('company'); ?></span>
</div>