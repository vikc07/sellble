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
<div id="sellble-html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    echo $this->Html->css('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
    ?>
    <style>@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
        #sellble-container-div{
            font-family: 'Open Sans', sans-serif !important;
            font-size: 14px !important;
        }
        #sellble-container-div a{
            text-decoration: none !important;
            color:#d52670 !important;
        }
        #sellble-container-div .sellble-main-div{
            color: #262626 !important;
            padding:5px !important;
            margin-left:auto !important;
            margin-right:auto !important;
            overflow:hidden !important;
        }
        #sellble-container-div .sellble-main-div .sellble-top-contact-div{
            float: left !important;
            width: 100% !important;
            text-align: center !important;
            display: table-cell !important;
            vertical-align:middle !important
        }
        #sellble-container-div .sellble-main-div .sellble-top-contact-div a{
            text-decoration: none !important;
            color:#d52670 !important;
        }
        #sellble-container-div .sellble-top-logos-div{
            width: 100% !important;
            text-align: center !important;
        }
        #sellble-container-div .sellble-top-logos-div a{
            text-decoration:none !important;
            border:none !important;
        }
        #sellble-container-div .sellble-top-logos-div img{
            text-decoration:none !important;
            border:none !important;
        }
        #sellble-container-div .sellble-top-after-logo-icons{
            float: left !important;
            width: 100% !important;
            text-align:center !important;
        }
        #sellble-container-div .sellble-p{
            margin-top: 10px !important;
            line-height: 150% !important
        }
        #sellble-container-div .sellble-seals-td{
            width: 10%;
            text-align: center;
        }
        #sellble-container-div .sellble-seals-td img{
            width: 95% !important;
            vertical-align: middle !important;
        }
        #sellble-container-div table{
            width: 100% !important;
        }
        #sellble-container-div .sellble-seals-text-td{
            padding-left: 10px !important;
            width: 40% !important;
            font-size: 11px !important;
            vertical-align: middle !important;
        }
        #sellble-container-div .sellble-spec-table{
            width: 100% !important;
        }
        #sellble-container-div .sellble-spec-subgroup-td{
            color:#262626!important;
            padding:5px!important;
            background:none!important;
            text-align:left!important;
            font-size:12px!important;
            border-bottom: 1px solid #e3e3e3!important;
        }
        #sellble-container-div .sellble-spec-name-td{
            color:#262626!important;
            padding:5px!important;
            background:none!important;
            font-size: 12px!important;
            width: 30%!important;
        }
        #sellble-container-div .sellble-spec-value-td{
            color:#262626!important;
            padding:5px!important;
            background:none!important;
            font-size: 12px!important;
        }
        #sellble-container-div .sellble-section-heading-1-div{
            font-size: 20px!important;
            font-weight: bold!important;
            color: white!important;
            padding: 5px!important;
            padding-left: 10px!important;
            background:#380d3d!important;
            width: 100%!important;
            margin-top: 25px!important;
            margin-bottom: 10px!important;
        }
        #sellble-container-div .sellble-condition-name-td{
            font-size: 24px!important;
            font-weight: bold!important;
            color: white!important;
            padding: 5px!important;
            text-align: center!important;
            vertical-align: middle!important;
            background:#d52670!important;
            width: 30% !important
        }
        #sellble-container-div .sellble-condition-desc-td{
            font-size:11px !important;
            padding: 5px !important;
            vertical-align: middle !important;
        }
        #sellble-container-div .sellble-condition-desc-td li{
            line-height: 150% !important;
        }
        #sellble-container-div .sellble-section-text-div{
            width: 100% !important;
        }
        #sellble-container-div .sellble-photos-main-div{
            width: 100% !important;
        }
        #sellble-container-div .sellble-photos-featured-div{
            float: left !important;
            width: 100% !important;
        }
        #sellble-container-div .sellble-photos-main-div img{
            border: 2px solid #e3e3e3 !important;
            max-width: 100% !important;
        }
        #sellble-container-div .sellble-photos-photo-div{
            float:left !important;
            width: 20% !important;
        }
        #sellble-container-div .sellble-filler-div{
            width: 100% !important;
            margin-top: 75px !important;
            clear: left !important;
        }
        #sellble-config-div{
            background: #e5e5e5 !important;
            font-family: 'Open Sans', sans-serif !important;
            font-size: 10px !important;
            width: 100% !important;
        }
        #sellble-config-div table{
            width: 100% !important;
        }
        #sellble-config-div .sellble-options-td{
            vertical-align: top !important;
            font-size: 11px !important;
            padding: 10px !important;
        }
        #sellble-config-div .sellble-options-td textarea{
            width: 100%;
            resize: None;
        }
        #sellble-container-div .sellble-bottom-notice-div{
            width: 100% !important;
            text-align: center !important;
            font-size: 10px !important;
        }
        #sellble-container-div .sellble-bottom-buttons-div{
            width: 100% !important;
            text-align: center !important;
            margin-bottom: 15px !important;
        }
        #sellble-container-div .sellble-bottom-buttons-span{
            font-size: 20px !important;
            font-weight: bold !important;
            padding: 5px 10px !important;
            background:#d52670 !important;
            width: 20% !important;
            text-align: center !important;
            margin-left: 10px !important;
        }
        #sellble-container-div .sellble-bottom-buttons-span a{
            color: white !important;
        }
        #sellble-container-div .sellble-top-icons td{
            text-align: center !important;
            vertical-align: middle !important;
            font-weight: bold !important;
            color: #d52670 !important;
            font-size:18px !important;
        }
        #sellble-container-div .sellble-logos-div{
            width: 100% !important;
            border: none !important;
            margin-top: 25px !important;
        }
        #sellble-container-div .sellble-logos-div img{
            margin-top: 10px !important;
            margin-right: 10px !important;
        }
        #sellble-container-div .sellble-logo-div{
            border: none !important;
            margin-top: 25px !important;
        }
        #sellble-container-div .sellble-logo-div img{
            max-width: 100%;
        }
    </style>
<div id="sellble-container-div">
<div class="sellble-main-div">
<div class="sellble-logo-div">
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH0AAAB9CAYAAACPgGwlAAAACXBIWXMAAC4jAAAuIwF4pT92AAAKlWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnRpZmY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vdGlmZi8xLjAvIiB4bWxuczpleGlmPSJodHRwOi8vbnMuYWRvYmUuY29tL2V4aWYvMS4wLyIgeG1wOkNyZWF0ZURhdGU9IjIwMTktMDMtMDJUMTE6NTQ6NDMtMDU6MDAiIHhtcDpNb2RpZnlEYXRlPSIyMDE5LTAzLTAyVDE0OjQ3OjMyLTA1OjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE5LTAzLTAyVDE0OjQ3OjMyLTA1OjAwIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChNYWNpbnRvc2gpIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgcGhvdG9zaG9wOkNvbG9yTW9kZT0iMyIgcGhvdG9zaG9wOklDQ1Byb2ZpbGU9InNSR0IgSUVDNjE5NjYtMi4xIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjcwMGUyMTkxLTIzZjItNDA0Yy1iNmQ0LWE3ZjgyN2U4N2MxMiIgeG1wTU06RG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOjRiYmNhYjY3LWVlM2UtYjM0Ni05MmViLWJjNGY2MDk5NTUxYiIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjY3ZDg4NDZhLTcyN2ItNGNhYS1hOWIyLWJiYmUzNTU0ZTI1YiIgdGlmZjpPcmllbnRhdGlvbj0iMSIgdGlmZjpYUmVzb2x1dGlvbj0iMzAwMDAwMC8xMDAwMCIgdGlmZjpZUmVzb2x1dGlvbj0iMzAwMDAwMC8xMDAwMCIgdGlmZjpSZXNvbHV0aW9uVW5pdD0iMiIgZXhpZjpDb2xvclNwYWNlPSIxIiBleGlmOlBpeGVsWERpbWVuc2lvbj0iMzExOCIgZXhpZjpQaXhlbFlEaW1lbnNpb249IjMxMTgiPiA8cGhvdG9zaG9wOlRleHRMYXllcnM+IDxyZGY6QmFnPiA8cmRmOmxpIHBob3Rvc2hvcDpMYXllck5hbWU9IlJhbmRvbSBQdXJwbGUgSGlwcG8iIHBob3Rvc2hvcDpMYXllclRleHQ9IlJhbmRvbSBQdXJwbGUgSGlwcG8iLz4gPC9yZGY6QmFnPiA8L3Bob3Rvc2hvcDpUZXh0TGF5ZXJzPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo2N2Q4ODQ2YS03MjdiLTRjYWEtYTliMi1iYmJlMzU1NGUyNWIiIHN0RXZ0OndoZW49IjIwMTktMDMtMDJUMTE6NTU6MTctMDU6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChNYWNpbnRvc2gpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDplZWRhNDg4Yi0zY2NiLTQ1NzYtYWY2Mi04YjQ5NmNmYWEwYzIiIHN0RXZ0OndoZW49IjIwMTktMDMtMDJUMTQ6NDc6MzItMDU6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChNYWNpbnRvc2gpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjb252ZXJ0ZWQiIHN0RXZ0OnBhcmFtZXRlcnM9ImZyb20gYXBwbGljYXRpb24vdm5kLmFkb2JlLnBob3Rvc2hvcCB0byBpbWFnZS9wbmciLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImRlcml2ZWQiIHN0RXZ0OnBhcmFtZXRlcnM9ImNvbnZlcnRlZCBmcm9tIGFwcGxpY2F0aW9uL3ZuZC5hZG9iZS5waG90b3Nob3AgdG8gaW1hZ2UvcG5nIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo3MDBlMjE5MS0yM2YyLTQwNGMtYjZkNC1hN2Y4MjdlODdjMTIiIHN0RXZ0OndoZW49IjIwMTktMDMtMDJUMTQ6NDc6MzItMDU6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChNYWNpbnRvc2gpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDplZWRhNDg4Yi0zY2NiLTQ1NzYtYWY2Mi04YjQ5NmNmYWEwYzIiIHN0UmVmOmRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDplOWY5NTMyYi0yN2ZkLTQ0NDctYmQxNC04YTk3ODY1M2NkMjAiIHN0UmVmOm9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo2N2Q4ODQ2YS03MjdiLTRjYWEtYTliMi1iYmJlMzU1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">
</div>
<?php
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
    We're available to answer your questions! Simply send us a message on eBay. Please also include the item name in your message.
</div>
</div>
</div>
</div>
