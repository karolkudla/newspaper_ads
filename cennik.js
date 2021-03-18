jQuery(document).ready(function() {

	/* CENNIK OBLICZANIE RABATÓW DLA GAZET */

	jQuery('body').on("input", ".acf-field[data-name='cena_1_modulu_na_stronie_pierwszej'] input", function() {
		var rabat = jQuery(this).closest('.cennik_form').find('#acf-field_5fa83592b0090').val();
		var input = jQuery(this).val();
		var calculate = input - input*(rabat/100);
		
		var rabated_field = jQuery(this).closest('.cennik_form').find('#acf-field_5fa877096a676');
		
		rabated_field.val(	 calculate.toFixed(2)  )
		rabated_field.attr('value',calculate.toFixed(2))
				
	})

	jQuery('body').on("input", ".acf-field[data-name='cena_1_modulu_na_stronie_ostatniej'] input", function() {
		var rabat = jQuery(this).closest('.cennik_form').find('#acf-field_5fa83592b0090').val();
		var input = jQuery(this).val();
		var calculate = input - input*(rabat/100);
		
		var rabated_field = jQuery(this).closest('.cennik_form').find('#acf-field_5fa879a7b0eb1');
		
		rabated_field.val(	 calculate.toFixed(2)  )
		rabated_field.attr('value',calculate.toFixed(2))
				
	})
	
	jQuery('body').on("input", ".acf-field[data-name='cena_1_modulu_na_stronie_redakcyjnej'] input", function() {
		var rabat = jQuery(this).closest('.cennik_form').find('#acf-field_5fa83592b0090').val();
		var input = jQuery(this).val();
		var calculate = input - input*(rabat/100);
		
		var rabated_field = jQuery(this).closest('.cennik_form').find('#acf-field_5fa879beb0eb2');
		
		rabated_field.val(	 calculate.toFixed(2)  )
		rabated_field.attr('value',calculate.toFixed(2))
				
	})

	jQuery('body').on("input", ".acf-field[data-name='cena_1_modulu_na_rozkladowce'] input", function() {
		var rabat = jQuery(this).closest('.cennik_form').find('#acf-field_5fa83592b0090').val();
		var input = jQuery(this).val();
		var calculate = input - input*(rabat/100);
		
		var rabated_field = jQuery(this).closest('.cennik_form').find('#acf-field_5fa879d7b0eb3');
		
		rabated_field.val(	 calculate.toFixed(2)  )
		rabated_field.attr('value',calculate.toFixed(2))
				
	})

	jQuery('body').on("input", ".acf-field[data-name='rabat_dla_reklamy_w_gazecie_w_procentach'] input", function() {
		
		var input_1 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa834f4b008c").val();
		var input_2 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa83565b008d").val();
		var input_3 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa83571b008e").val();
		var input_4 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa8357eb008f").val();
		
		var rabat = jQuery(this).val();

		var calc_1 = input_1 - input_1*(rabat/100)
		var calc_2 = input_2 - input_2*(rabat/100)
		var calc_3 = input_3 - input_3*(rabat/100)
		var calc_4 = input_4 - input_4*(rabat/100)

		var save_1 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa877096a676");
		var save_2 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa879a7b0eb1")
		var save_3 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa879beb0eb2")
		var save_4 = jQuery(this).closest(".cennik_form").find("#acf-field_5fa879d7b0eb3")
		
		save_1.val(	 calc_1.toFixed(2) 	)
		save_2.val(	 calc_2.toFixed(2)  )
		save_3.val(	 calc_3.toFixed(2)  )
		save_4.val(	 calc_4.toFixed(2)  )
		
	})


/* WYSYŁANIE ZAMÓWIENIA ZWROTNEGO */
	
	function calc_total() {
		
		var total_before = [];
		/* Zbierz wszystkie kwoty z kolumny i zsumuj */
		jQuery(".price_before_rabat").each(function(k,v) {
			var price_before = jQuery(v).val();
			if (price_before) {
				total_before.push(parseInt(price_before));
			}
		})
		
		var sum_before = total_before.reduce((a, b) => a + b, 0);
		jQuery(".total_from_response_before").val(sum_before.toFixed(2))
		
		var total_after = [];
		/* Zbierz wszystkie kwoty z kolumny i zsumuj */
		jQuery(".price_after_rabat").each(function(k,v) {
			var price_after = jQuery(v).val();
			if (price_after) {
				total_after.push(parseFloat(price_after));
			}
		})
		
		var sum_after = total_after.reduce((a, b) => a + b, 0);
		jQuery(".total_from_response_after").val(sum_after.toFixed(2))
		
		var client_order_total = parseFloat(jQuery(".client_order_total").val());
		var new_total = parseFloat(jQuery(".total_from_response_after").val());
		
		if (client_order_total < new_total) {
			var calc = new_total-client_order_total;
			jQuery(".client_total_info").html("Klient musi dopłacić: "+calc.toFixed(2)+" zł")
			jQuery(".client_order_diff").val(calc.toFixed(2))
		}
		
		if (client_order_total > new_total) {
			var calc = client_order_total-new_total;
			jQuery(".client_total_info").html("Klient dostaje zwrot: "+calc.toFixed(2)+" zł")
			jQuery(".client_order_diff").val(calc.toFixed(2))
		}
		
		if (client_order_total == new_total) {
			jQuery(".client_total_info").html("Zapytanie ofertowe klienta opiewa na taką samą kwotę")
			jQuery(".client_order_diff").val((new_total-client_order_total).toFixed(2))
		}

	}
	
	jQuery('body').on("change", ".response_order_select_ad_space", function() {
		
		/* Pobiera cenę miejsca na podstawie numeru id wariacji */
		/* I wyświetla w odpowiednim polu zwrotki */
		
		var parent_id = jQuery(this).find('option:selected').attr("parent_id");
		
		var variation_id = jQuery(this).val();
		var order = jQuery(this).attr("order");
	
		jQuery.ajax({
					type : "POST",
					url : cc_ajax_object.ajax_url,
					data : {
						action: "get_prices",
						parent_id:parent_id,
						variation_id:variation_id
					},
					success: function(prices) {
						
						/* Wyświetl cenę w polu obok przed rabatem i po rabacie */
						jQuery(".price_before_rabat[order="+order+"]").val(prices['before'].toFixed(2))
						jQuery(".price_after_rabat[order="+order+"]").val(prices['after'])
						
						calc_total();
						
					return false;					   
					}
			})
		
	})
	
	/* Zakładki */
	jQuery('.nav-tab').click(function() {
		
		var cennik_to_open = jQuery(this).attr('id');
		jQuery('.tab-content').fadeOut();
		jQuery('.tab-content[cennik="'+cennik_to_open+'"]').fadeIn()
		
		jQuery('.nav-tab').css("background","none")
		jQuery(this).css("background","white")
		
	})
	
	/* Obliczanie rabatów dla Portali */
	
	/* Po wpisaniu Rabatu */
	
	jQuery('body').on("input", "#acf-field_5fc166892c8de", function() {
		
		var rabat = jQuery(this).val();
		var rabat_decimal = rabat/100;
		
		/* Banner Top, Banner 1, Banner 2, Banner 3 ... */
		var banner_type_count = [0,1,2,3,4,5,6];
		var days = ['7_dni','14_dni','21_dni','30_dni','45_dni','60_dni','90_dni'];

		var closest = jQuery(this).closest(".cennik_form");

		jQuery.each(banner_type_count, function(k,v) {
			var podstawowe_wrapper = closest.find("div[data-name='ceny_podstawowe_"+k+"']");
			var po_rabacie_wrapper = closest.find("div[data-name='ceny_po_uwzglednieniu_rabatu_"+k+"']");
			
			jQuery.each(days, function(k,v) {
				var podstawowe_cena = podstawowe_wrapper.find("div[data-name='"+v+"'] input").val()
				var calculate = podstawowe_cena - (podstawowe_cena*rabat_decimal);
				po_rabacie_wrapper.find("div[data-name='"+v+"'] input").val(calculate.toFixed(2))
				po_rabacie_wrapper.find("div[data-name='"+v+"'] input").attr("value",calculate.toFixed(2))
			})
			
		})
		
	})
	
	/* Po wpisaniu ceny Podstawowej */
	
	jQuery('body').on("input", ".acf-field-group input", function() {
		
		var rabat = jQuery(this).closest(".cennik_form").find("#acf-field_5fc166892c8de").val();
		var rabat_decimal = rabat/100;
		var banner_type = jQuery(this).closest(".acf-field-group").attr("data-name");
		var banner_type_number = banner_type.match(/\d+/)[0];
		var po_rabacie_wrapper = jQuery(this).closest(".cennik_form").find("div[data-name='ceny_po_uwzglednieniu_rabatu_"+banner_type_number+"']")
		var data_name = jQuery(this).closest(".acf-field-number").attr("data-name");
		var calculate = jQuery(this).val() - (jQuery(this).val() * rabat_decimal);

		po_rabacie_wrapper.find(".acf-field-number[data-name='"+data_name+"'] input").val(calculate.toFixed(2))
		po_rabacie_wrapper.find(".acf-field-number[data-name='"+data_name+"'] input").attr("value",calculate.toFixed(2))

	})
	
	var banner_type_count = [0,1,2,3,4,5,6];
	jQuery.each(banner_type_count, function(k,v) {
		jQuery("div[data-name='ceny_po_uwzglednieniu_rabatu_"+k+"'] input").attr('readonly', true);
	})
	
	/* Wybór obrazów w zwrotce */
	
	function custom_template(obj){
        	var data = jQuery(obj.element).data();
        	var text = jQuery(obj.element).text();
        	if(data && data['img_src']){
	        	img_src = data['img_src'];
	        	template = jQuery("<div><img src=\"" + img_src + "\" style=\"max-width:60px; max-height: 87px;\"/></div>");
	        	return template;
	        }
        }
	var options = {
		'templateSelection': custom_template,
		'templateResult': custom_template,
	}
	
	
	
	jQuery('.zwrotka_image_drop_down').select2(options);
    jQuery('.select2-container--default .select2-selection--single').css({'height': '100px'});

	jQuery('.select2-search').css('display','none')
       
	
})




