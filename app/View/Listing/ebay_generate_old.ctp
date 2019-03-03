<div id="sellble-config-div">
    <script>
        function get_html_code(){
            function trim(a){return a.replace(/^\s+|\s+$/g,"")}
            function right(a,pos){var z=a.length; return a.substring(z-pos,z)}

            function compress(src, dest, removeLineFeeds) {
                var txt = src.value;
                txt = txt.replace(/\r\n/g, "\n");
                txt = txt.replace(/\r/g, "\n");
                txt = txt.replace(/\n/g, "\r\n");
                txt = txt.replace(/\t/g, " ");

                var q = [];
                var p = txt.split(/\r\n/);
                for(var k = 0 ; k < p.length ; k++) {
                    var tmp = p[k];
                    tmp = tmp.replace(/(<!-)(-)[\s\S]+(-)(->)/ig,"");
                    tmp = tmp.replace(/\s{2,}/g," ");
                    tmp = trim(tmp);
                    if (tmp) q.push(tmp);
                }

                var z = "";
                if (removeLineFeeds) {
                    var x = 0;
                    for (var k = 0 ; k < q.length ; k++) {
                        //line breaks every 1024 chars
                        if (x + q[k].length > 1024) {
                            z += "\r\n";
                            x = 0;
                        }
                        x += q[k].length + 1;
                        z += q[k];
                        if (right(z, 1) != ">") z += " ";
                    }
                }else{
                    z = q.join("\r\n");
                }

                z += "\r\n";

                dest.value = z;
            }

            document.getElementById('sellble-html-textarea').value = document.getElementById('sellble-html').innerHTML;
            compress(document.getElementById('sellble-html-textarea'), document.getElementById('sellble-html-textarea'), false);
        }
    </script>
    <?php
    echo $this->Form->create('Options',array('novalidate'=>true));
    ?>
    <table>
        <tr>
            <td class="sellble-options-td">
                <h4>Main</h4>
                <?php
                echo $this->Form->input(
                    'show_description',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Show Description',
                        'checked' => $options['Options']['show_description']
                    )
                );

                echo $this->Form->input(
                    'show_specs',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Show Specs',
                        'checked' => $options['Options']['show_specs']
                    )
                );

                echo $this->Form->input(
                    'show_photos',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Show Photos',
                        'checked' => $options['Options']['show_photos']
                    )
                );

                echo $this->Form->input(
                    'show_spec_logos',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Show Spec Logos',
                        'checked' => $options['Options']['show_spec_logos']
                    )
                );

                echo $this->Form->input(
                    'show_brand_logos',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Show Brand Logos',
                        'checked' => $options['Options']['show_brand_logos']
                    )
                );
                ?>
            </td>
        </tr>
        <tr>
            <td class="sellble-options-td">
                <h4>Seals</h4>
                <?php
                echo $this->Form->input(
                    'show_purple_shield',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Purple Shield',
                        'checked' => $options['Options']['show_purple_shield']
                    )
                );
                echo $this->Form->input(
                    'show_hippo_verified',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Hippo Verified',
                        'checked' => $options['Options']['show_hippo_verified']
                    )
                );
                echo $this->Form->input(
                    'show_wysiwyg',
                    array(
                        'type' => 'checkbox',
                        'label' => 'WYSIWYG',
                        'checked' => $options['Options']['show_wysiwyg']
                    )
                );
                echo $this->Form->input(
                    'show_tough_skin',
                    array(
                        'type' => 'checkbox',
                        'label' => 'Tough Skin',
                        'checked' => $options['Options']['show_tough_skin']
                    )
                );
                ?>
            </td>
        </tr>
        <tr>
            <td class="sellble-options-td">
                <h4>HTML Code</h4>
                <textarea id="sellble-html-textarea" rows="7" onclick="this.select();"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                echo $this->Form->button(
                    'Submit',
                    array('type' => 'submit', 'div' => false, 'escape' => false)
                );
                ?>
                <?php
                echo $this->Form->button(
                    'Get HTML Code',
                    array(
                        'id' => 'sellble-btn-get-html-code',
                        'type' => 'button',
                        'div' => false,
                        'escape' => false,
                        'onclick' => 'javascript:get_html_code()'
                    )
                );
                ?>
            </td>
        </tr>
    </table>
</div>
<?php
$icons_contact = '';
$icons_contact .= $this->myHtml->icon(
    array(
        'title' => 'Check our Store',
        'type' => 'globe',
        'args' => Configure::read('contact.web')
    )
);
$icons_contact .= '&nbsp;&nbsp;';
$icons_contact .= $this->myHtml->icon(
    array(
        'title' => 'Send us a message',
        'type' => 'email',
        'args' => Configure::read('contact.ebay')
    )
);
$icons_contact .= '&nbsp;&nbsp;';
$icons_contact .= $this->myHtml->icon(
    array(
        'title' => 'Follow us on Twitter',
        'type' => 'twitter',
        'args' => Configure::read('contact.twitter')
    )
);
$icons_contact .= '&nbsp;&nbsp;';
$icons_contact .= $this->myHtml->icon(
    array(
        'title' => 'Follow us on Pinterest',
        'type' => 'pinterest',
        'args' => Configure::read('contact.pinterest')
    )
);
?>
<div id="sellble-html">
<div id="sellble-container-div">
<?php
echo $this->Html->css('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
echo $this->Html->css('http://vkrm.ch/rph/myebay.css');
?>
<div class="sellble-main-div">
<div class="sellble-top-contact-div">
    <?php echo $icons_contact; ?>
</div>
<div class="sellble-top-logos-div">
    <br/>
    <?php
    echo $this->myHtml->image(
        array(
            'image' => Configure::read('ftp.urlImg') . '/rph/rph-banner-size-500.png',
            'args' => array(
                'fullBase' => true
            )
        )
    );
    ?>&nbsp;&nbsp;
    <?php
    echo $this->myHtml->image(
        array(
            'image' => Configure::read('ftp.urlImg') . '/ebay-top-rated-seller.png',
            'args' => array(
                'fullBase' => true
            )
        )
    );
    ?>&nbsp;&nbsp;
    <?php
    echo $this->myHtml->image(
        array(
            'image' => Configure::read('ftp.urlImg') . '/paypal-verified.png',
            'args' => array(
                'url' => Configure::read('contact.paypal_verify')
            )
        )
    );
    ?>
</div>
<div>
    <table class="sellble-top-icons">
        <tr>
            <td>
                <?php
                echo $this->myHtml->icon(
                    array(
                        'title' => 'QUALITY STUFF',
                        'type' => 'heart',
                        'size' => '2x'
                    )
                );
                ?>
            </td>
            <td>

                <?php
                echo $this->myHtml->icon(
                    array(
                        'title' => 'SUPER FAST SHIPPING',
                        'type' => 'ship',
                        'size' => '2x'
                    )
                );
                ?>
            </td>
            <td>
                <?php
                echo $this->myHtml->icon(
                    array(
                        'title' => 'HASSLE-FREE RETURNS',
                        'type' => 'refresh',
                        'size' => '2x'
                    )
                );
                ?>
        </tr>
    </table>
</div>
<div class="sellble-filler-div">&nbsp;</div>
<?php
if(isset($listing['Sku']['Item']['ItemPhoto'][0]) and $options['Options']['show_photos']){
    $image = Configure::read('ftp.urlImg') . $listing['Sku']['Item']['ItemPhoto'][0]['photo'];
    ?>
    <div class="sellble-photos-main-div">
        <div class="sellble-photos-featured-div">
            <script type='text/javascript'>
                function sellble_change_featured_img(new_img){
                    document.getElementById('sellble-featured-img').src=new_img;
                }

                function sellble_reset_featured_img(){
                    document.getElementById('sellble-featured-img').src='<?php echo $image; ?>';
                }
            </script>
            <?php
            echo $this->myHtml->image(
                array(
                    'image' => $image,
                    'args' => array(
                        'id' => 'sellble-featured-img',
                        'fullBase' => true,
                        'url' => $image
                    )
                )
            )
            ?>
        </div>
        <div class="sellble-filler-div">&nbsp;</div>
        <?php
        unset($listing['Sku']['Item']['ItemPhoto'][0]);
        $sellble_css_photos_photo_div = "float:left !important;width: 20% !important;";
        foreach($listing['Sku']['Item']['ItemPhoto'] as $photo){
            ?>
            <div style="<?php echo $sellble_css_photos_photo_div; ?>">
                <?php
                $image = Configure::read('ftp.urlImg') . $photo['photo'];
                echo $this->myHtml->image(
                    array(
                        'image' => $image,
                        'args' => array(
                            'fullBase' => true,
                            'url' => $image,
                            'onMouseOver' => "sellble_change_featured_img('$image')",
                            'onMouseOut' => 'sellble_reset_featured_img()'
                        )
                    )
                )
                ?>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
if($options['Options']['show_brand_logos'] or $options['Options']['show_spec_logos']){
    ?>
    <div class="sellble-filler-div">&nbsp;</div>
    <div class="sellble-logos-div">
        <?php
        $brand = $listing['Sku']['Item']['Brand'][0]['Brand'];
        $manufacturer = $brand['Manufacturer'][0]['Manufacturer'];
        if($options['Options']['show_spec_logos']){
            if($manufacturer['logoFull']){
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . $manufacturer['logoFull']
                    )
                );
            }

            if($brand['logoFull']){
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . $brand['logoFull']
                    )
                );
            }
        }
        if($options['Options']['show_spec_logos']){
            foreach($listing['Sku']['Item']['ItemSpec'] as $sub_group_name => $sub_group){
                foreach($sub_group as $spec => $info){
                    if($info['logoFull']){
                        echo $this->myHtml->image(
                            array(
                                'image' => Configure::read('ftp.urlImg') . $info['logoFull']
                            )
                        );
                    }
                }
            }
        }
        ?>
    </div>
<?php
}
?>
<div class="sellble-filler-div">&nbsp;</div>
<table>
    <tr>
        <?php
        if($options['Options']['show_purple_shield']){
            ?>
            <td class="sellble-seals-td">
                <?php
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . '/rph/icon-purple-shield.png'
                    )
                );
                ?>
            </td>
            <td class="sellble-seals-text-td">
                <h2>Stay worry free</h2>
                Full support given for any issues or questions you may have about this item. Send it back if there are any problems or the item does not match the description provided, and you will be issued a full refund including the shipping cost.
            </td>
        <?php
        }
        if($options['Options']['show_hippo_verified']){
            ?>
            <td class="sellble-seals-td">
                <?php
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . '/rph/icon-hippo-verified.png'
                    )
                );
                ?>
            </td>
            <td class="sellble-seals-text-td">
                <h2>Quality Checked</h2>
                Thoroughly checked for functionality and skill-fully serviced. Hard drives is wiped clean and installed with a fresh genuine copy of the OS devoid of any bloat-ware. All drivers are updated and OS service packs/updates, if any, are installed as well. A copy of the owner's manual is made available on the desktop for reference (if available).
            </td>
        <?php
        }
        ?>
    </tr>
    <tr>
        <?php
        if($options['Options']['show_wysiwyg']){
            ?>
            <td class="sellble-seals-td">
                <?php
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . '/rph/icon-wysiwyg.png'
                    )
                );
                ?>
            </td>
            <td class="sellble-seals-text-td">
                <h2>What You See Is What You Get</h2>
                Full specifications are listed and images are provided for this item. All the images are high resolution (1600x1067) actuals and not copied from the internet. You can also download those for a closer inspection.
            </td>
        <?php
        }
        if($options['Options']['show_tough_skin']){
            ?>
            <td class="sellble-seals-td">
                <?php
                echo $this->myHtml->image(
                    array(
                        'image' => Configure::read('ftp.urlImg') . '/rph/icon-tough-skin.png'
                    )
                );
                ?>
            </td>
            <td class="sellble-seals-text-td">
                <h2>Set for shipping</h2>
                Your item will be sent to you in a safe packaging. Item will be wrapped in a bubble wrap surrounded by the padding material to handle shocks during transit. Original box if available will be included.
            </td>
        <?php
        }
        ?>
    </tr>
</table>
<?php
if($listing['Sku']['Item']['Condition']){
    ?>
    <div class="sellble-filler-div">&nbsp;</div>
    <table>
        <tr>
            <td class="sellble-condition-name-td">
                This item is<br/><?php echo strtoupper($listing['Sku']['Item']['Condition']); ?>
            </td>
            <td class="sellble-condition-desc-td">
                <ul>
                    <li>Items in 'Like New' condition have very light to no scratches, or marks. Items show very light signs of use. Generally have flawless screen.</li>
                    <li>Items in 'Very Good' condition have light scratches or marks but no nicks, chips or cracks. There may be light signs of previous use. They generally have near flawless screen.</li>
                    <li>Items in 'Good' condition have few scratches, marks or nicks on the screen or outer cover. There may be visible signs of previous use. Item may have minor chips or cracks (excluding Screen) and other imperfections.</li>
                    <li>Items in 'Fair' condition have medium to heavy scratches or marks. There are greatly visible signs of previous use. Item may have chips or cracks (excluding Screen) and other imperfections</li>
                </ul>
            </td>
        </tr>
    </table>
<?php
}
if($listing['EbayListing'][0]['EbayListing']['description'] and $options['Options']['show_description']){
    ?>
    <div class="sellble-section-heading-1-div">
        <?php
        echo $this->myHtml->icon(
            array(
                'title' => 'DESCRIPTION',
                'type' => 'info'
            )
        );
        ?>
    </div>
    <div class="sellble-section-text-div">
        <p class="sellble-p"><?php echo $listing['EbayListing'][0]['EbayListing']['description']; ?></p>
    </div>
<?php
}

if($options['Options']['show_specs'] and !empty($listing['Sku']['Item']['ItemSpec'])){
    ?>
    <div class="sellble-section-heading-1-div">
        <?php
        echo $this->myHtml->icon(
            array(
                'title' => 'SPECS',
                'type' => 'list'
            )
        );
        ?>
    </div>
    <table class="sellble-spec-table">
        <?php
        foreach($listing['Sku']['Item']['ItemSpec'] as $sub_group_name => $sub_group){
            ?>
            <tr>
                <td class="sellble-spec-subgroup-td" colspan='2'><strong><?php echo $sub_group_name; ?></strong></td>
            </tr>
            <?php
            foreach($sub_group as $spec => $info){
                ?>
                <tr>
                    <td class="sellble-spec-name-td">
                        <?php echo $spec; ?>
                    </td>
                    <td class="sellble-spec-value-td">
                        <?php echo $info['name']; ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
    </table>
<?php
}
?>
<div class="sellble-section-heading-1-div">
    <?php
    echo $this->myHtml->icon(
        array(
            'title' => 'QUESTIONS',
            'type' => 'question'
        )
    );
    ?>
</div>
<div class="sellble-section-text-div">
    We're available to answer your questions! Simply <?php echo $this->Html->link('send us a message', Configure::read('contact.ebay')); ?> on eBay. Please also include the item name in your message.
</div>
<div class="sellble-section-heading-1-div">
    <?php
    echo $this->myHtml->icon(
        array(
            'title' => 'THERE\'S MORE, JUST FOR YOU!',
            'type' => 'tags'
        )
    );
    ?>
</div>
<div class="sellble-section-text-div">
    <object data='<?php echo Configure::read('ftp.url'); ?>/ebay-similar-items.php?qry=(<?php echo $listing['EbayListing'][0]['EbayListing']['keywords']; ?>)' width='100%' height='500px;'></object>
</div>
<div class="sellble-bottom-buttons-div">
                <span class="sellble-bottom-buttons-span">
                    <?php
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'money',
                            'title' => 'PAYMENT POLICY',
                            'args' => 'http://stores.ebay.com/randompurplehippo/pages/payment-policy'
                        )
                    );
                    ?>
                </span>
                <span class="sellble-bottom-buttons-span">
                    <?php
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'ship',
                            'title' => 'SHIPPING POLICY',
                            'args' => 'http://stores.ebay.com/randompurplehippo/pages/shipping-policy'
                        )
                    );
                    ?>
                </span>
                <span class="sellble-bottom-buttons-span">
                    <?php
                    echo $this->myHtml->icon(
                        array(
                            'type' => 'refresh',
                            'title' => 'RETURN POLICY',
                            'args' => 'http://stores.ebay.com/randompurplehippo/pages/return-policy'
                        )
                    );
                    ?>
                </span>
</div>
<div class="sellble-top-contact-div">
    <?php echo $icons_contact; ?>
</div>
<div class="sellble-bottom-notice-div">
    &copy;<?php echo date('Y') . ' '. Configure::read('company'); ?> All logos are copyright of their respective owners. No product image should be used without our permission.
</div>
</div>
</div>
</div>
