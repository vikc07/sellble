$(document).ready(function(){
	// Initialize CKEditor
	$('textarea').ckeditor();
	
	// Hide Spinner
    $('#spinner').html();
	$('#spinner').hide();

    $("[rel='tooltip']").tooltip();
	
    $("form").submit(function() {
        $(":submit", this).attr("disabled", "disabled");
        $('#spinner').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#spinner').show();
    });

    if($('.file-upload').length>0){
        $('button[type="submit"]').attr('disabled','disabled');
        $(document).on('change','.file-upload',function(){
            $('button[type="submit"]').removeAttr('disabled');
        });
    }

    if($('.sellble-download-csv').length>0){
        $(document).on('click','.sellble-download-csv',function(){
            $('#sellble-table').TableCSVExport({
                'delivery': 'download'
            });
        });
    }

    if($('.sellble-brands').length > 0){
        $(document).on('change', '.sellble-brands', function(){
            $('#sellble-item-brand-name').val($('.sellble-brands option:selected').text());
        });
    }

    if($('.sellble-item-typeahead').length > 0){
        $(document).on('change', '.sellble-item-typeahead', function(){
            var item_arr = $(this).val().split(' - ');
            var item_id = item_arr.pop(); // This will be in IXXXXXXXX
            item_id = sellble_get_number_id(item_id,'I');

            // Set hidden item id element
            line_id = $(this).attr('id').replace('ItemTypeaheadLine', '');

            // Which item line?
            if(item_id){
                // Increase total items only if the hidden element does not have anything
                if(!$('#ItemLine' + line_id).val()){
                    $('.sellble-total-items').val(parseInt($('.sellble-total-items').val()) + 1);
                    $('#QtyForTotalLine' + line_id).val(1);
                }

                // Set the hidden element
                $('#ItemLine' + line_id).val(item_id);
            }
            else{
                $('#ItemLine' + line_id).val('');
                $('.sellble-total-items').val(parseInt($('.sellble-total-items').val())-1);
                $('#QtyForTotalLine' + line_id).val(0);
            }
            $('.sellble-quantity').trigger($.Event('change'));
        });
    }

    if($('.sellble-amount').length > 0){
        $(document).on('change', '.sellble-amount', function(){
            if($(this).val() == ''){
                $(this).val('0.00');
            }
            else{
                $(this).val(parseFloat($(this).val()).toFixed(2));
            }
        });
    }

    if($('.sellble-quantity').length > 0){
        $(document).on('change', '.sellble-quantity', function(){
            var line_id = $(this).attr('id').replace('QtyForTotalLine', '');
            var existsItemLine = false;
            if($('#ItemLine' + line_id).length > 0){
                if($('#ItemLine' + line_id).val()){
                    existsItemLine = true;
                }
            }
            else{
                existsItemLine = true;
            }

            if($(this).val() <= 0 && existsItemLine){
                $(this).val('1');
            }
            else if(existsItemLine){
                var new_val = parseInt($(this).val()).toFixed(0);
                $(this).val(new_val);
            }
            else{
                $(this).val('0');
            }

            var total = 0;

            if($('.sellble-total-quantity').length > 0){
                $('.sellble-quantity').each(function(){
                    total += parseInt($(this).val());
                });

                $('.sellble-total-quantity').val(total);
            }
        });
    }

    if($('.sellble-for-grand-total').length > 0){
        $(document).on('change', '.sellble-for-grand-total', function(){
            var total = 0;
            $('.sellble-for-grand-total').each(function(){
                total += parseFloat($(this).val());
            });
            $('.sellble-grand-total').val(total.toFixed(2));
        });
    }

    if($('.sellble-qty-for-total').length > 0){
        $(document).on('change', '.sellble-qty-for-total', function(){
            var line_id = $(this).attr('id');
            line_id = line_id.replace('QtyForTotalLine','');
            var total = parseInt($(this).val()) * parseFloat($('#PerUnitForTotalLine'+line_id).val());
            $('#TotalLine'+line_id).val(total.toFixed(2));

            $('.sellble-for-grand-total').trigger($.Event('change'));
        });
    }

    if($('.sellble-per-unit-for-total').length > 0){
        $(document).on('change', '.sellble-per-unit-for-total', function(){
            var line_id = $(this).attr('id');
            line_id = line_id.replace('PerUnitForTotalLine','');
            var total = parseFloat($(this).val()) * parseInt($('#QtyForTotalLine'+line_id).val());
            $('#TotalLine'+line_id).val(total.toFixed(2));

            $('.sellble-for-grand-total').trigger($.Event('change'));
        });
    }

    if($('.sellble-for-sales-tax').length > 0){
        $(document).on('change', '.sellble-for-sales-tax', function(){
            var sales_tax_rate = parseFloat($('#sellble-sales-tax-rate').text());
            var total = 0;
            $('.sellble-for-sales-tax').each(function(){
                total += parseFloat($(this).val());
            });

            var tax = total * sales_tax_rate;

            $('.sellble-sales-tax').val(tax.toFixed(2));
        });
    }

    if($('.sellble-date').length > 0){
        $('.sellble-date').attr('type','date');
    }

    if($('.sellble-customer-address-flag').length > 0){
        $(document).on('change', '.sellble-customer-address-flag', function(){
            if($('.sellble-customer-address-flag').is(':checked')){
                $('.sellble-customer-ship-address-name').val($('.sellble-customer-bill-address-name').val());
                $('.sellble-customer-ship-address-line-1').val($('.sellble-customer-bill-address-line-1').val());
                $('.sellble-customer-ship-address-line-2').val($('.sellble-customer-bill-address-line-2').val());
                $('.sellble-customer-ship-address-state').val($('.sellble-customer-bill-address-state').val());
                $('.sellble-customer-ship-address-city').val($('.sellble-customer-bill-address-city').val());
                $('.sellble-customer-ship-address-country').val($('.sellble-customer-bill-address-country').val());
                $('.sellble-customer-ship-address-zip').val($('.sellble-customer-bill-address-zip').val());
            }
        });

        $(document).on('change', '.sellble-customer-bill-address', function(){
            $('.sellble-customer-address-flag').trigger($.Event('change'));
        });
    }

    if($('.sellble-listing-date').length > 0){
        $(document).on('change','.sellble-listing-date',function(){
            var end_dt = new Date($(this).val());
            end_dt.setDate(end_dt.getDate()+Number($('.sellble-listing-duration').val())+1);
            year = end_dt.getFullYear();
            month = end_dt.getMonth()+1;
            if(month<10){
                month = "0"+month;
            }
            day = end_dt.getDate();
            if(day<10){
                day = "0"+day;
            }

            $('.sellble-listing-end-date').val(year+"-"+month+"-"+day);
        });

        $(document).on('change','.sellble-listing-duration',function(){
            $('.sellble-listing-date').trigger($.Event('change'));
        });
    }

    var dont_touch_charge = false;

    if($('.sellble-listing-amount').length > 0){
        $(document).on('change','.sellble-listing-price',function(){
            var list_price = parseFloat($('.sellble-listing-price').val());
            if(!dont_touch_charge){
                $('.sellble-listing-ebay-fee').val((list_price*parseFloat($('#sellble-ebay-fee-percent').text())).toFixed(2));
                if(list_price){
                    $('.sellble-listing-paypal-fee').val((list_price*0.029+0.3).toFixed(2));
                }
                $('.sellble-listing-tax').val((list_price*parseFloat($('#sellble-sales-tax-rate').text())).toFixed(2));
            }

            var total = 0;
            $('.sellble-listing-charge').each(function(){
                total += parseFloat($(this).val());
            });

            total = list_price - total;
            $('.sellble-listing-net').val(total.toFixed(2));
            $('.sellble-listing-grand-net').val((total*parseInt($('.sellble-quantity').val())-$('.sellble-listing-fee').val()).toFixed(2));
        });

        $(document).on('change','.sellble-listing-charge',function(){
            dont_touch_charge = true;
            $('.sellble-listing-price').trigger($.Event('change'));
        });

        $(document).on('change','.sellble-listing-fee',function(){
            $('.sellble-listing-price').trigger($.Event('change'));
        });

        $(document).on('change','.sellble-listing-quantity',function(){
            $('.sellble-listing-price').trigger($.Event('change'));
        });
    }

    if($('.sellble-sale-amount').length > 0){
        $(document).on('change','.sellble-sale-collected',function(){
            var sale_collected = 0;
            $('.sellble-sale-collected').each(function(){
                sale_collected += parseFloat($(this).val());
            });

            // Adjust the charges
            if(!dont_touch_charge){
                $('.sellble-sale-ebay-fee').val((sale_collected*parseFloat($('#sellble-ebay-fee-percent').text())).toFixed(2));
                $('.sellble-sale-paypal-fee').val((sale_collected*0.029+0.3).toFixed(2));
            }

            var sale_charges = 0;
            $('.sellble-sale-charge').each(function(){
                sale_charges += parseFloat($(this).val());
            });

            $('.sellble-sale-net').val((sale_collected-sale_charges).toFixed(2));
        });

        $(document).on('change','.sellble-sale-charge',function(){
            dont_touch_charge = true;
            $('.sellble-sale-collected').trigger($.Event('change'));
        });

        $(document).on('click', '.btn-sale-add-new-customer', function(){
            if($('.sellble-sale-customer-typeahead').is(':disabled')){
                $('.sellble-sale-customer-typeahead').attr('disabled', false);
                $('.sellble-sale-customer-id').attr('disabled',false);
                $('.sellble-sale-new-customer-info').attr('disabled', true);
            }
            else{
                $('.sellble-sale-customer-typeahead').attr('disabled', true);
                $('.sellble-sale-customer-id').attr('disabled',true);
                $('.sellble-sale-new-customer-info').attr('disabled', false);
            }

            $('#sellble-sale-new-customer').toggle();

            $('html, body').animate({
                scrollTop: $("#scrollTo").offset().top
            }, 1000);
        });
    }
});

function sellble_get_number_id(id_full, str_identifier){
    id_full = id_full.replace(str_identifier,'');
    if(id_full.length == 8){
        while(id_full.charAt(0)==='0'){
            id_full=id_full.substr(1);
        }
    }

    return id_full;
}