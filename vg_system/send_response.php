<?php

use Automattic\WooCommerce\Client;

/***********************************************************************************************/
/***********************************************************************************************/
/******************************* ZAPISZ DANE ZWROTKI I WYŚLIJ JĄ *******************************/
/***********************************************************************************************/
/***********************************************************************************************/

function wpdocs_save_meta_box( $post_id ) {
	
	global $woocommerce_vg;
	
	/* POST ID TO ID ZAMÓWIENIA */
	
	if (get_post_type( $post_id ) == 'shop_order') {
		
			global $post;
	 
			/* NUMER ZWROTKI */
			$order = wc_get_order( $post_id );
			$items = $order->get_items();
			
			/* Pobierz numer zwrotki z meta zamówienia */
			$meta_zwrotka = $order->get_meta( 'response_order_id', true );
						
			/* WYJMUJE TABELE KAŻDEGO CENNIKA DO JEDNEJ TABELI */
			/* I WTEDY MAPUJE LOCAL NA VG */

			/* CENNIK GAZETOWY */
			$vg_vari_ids = json_decode(get_field('field_5fbd3e90dee16',8725),true);
			foreach ($vg_vari_ids as $cennik_id => $cennik_data) {
				foreach ($cennik_data as $local_id => $vg_id) {
					$mapping_array[$local_id] = $vg_id;
				}
			}
			
			/* CENNIK PORTALOWY */
			$vg_vari_ids = json_decode(get_field('field_5fc147440faf3',8762),true);
			foreach ($vg_vari_ids as $cennik_id => $cennik_data) {
				foreach ($cennik_data as $local_id => $vg_id) {
					$mapping_array[$local_id] = $vg_id;
				}
			}
			
			for ($i=0; $i<100; $i++) {
				if(	   ($_POST['product_name_'.$i] != '') && ($_POST['data_publikacji_'.$i] != '') 	) {
					
					$variation = wc_get_product($_POST['product_name_'.$i]);
					$parent_id = $variation->get_parent_id();
										
					$new_offer[$i] = [
				
						'variation_id'=>$mapping_array[$_POST['product_name_'.$i]],
						'meta_data'=>[
										[
											'key'=>'Proponowana data publikacji',
											'value'=>$_POST['data_publikacji_'.$i]
										],
										[
											'key'=>'Nazwa Gazety/Portalu',
											'value'=>get_post_meta($parent_id,'nazwa',true)
										],
										[
											'key'=>'Adres WWW',
											'value'=>get_post_meta($parent_id,'adres_url',true)
										],
										[
											'key'=>'Numer Redakcji',
											'value'=>get_post_meta($parent_id,'numer_redakcji',true)
										],
										[
											'key'=>'Obraz',
											'value'=>$_POST['zwrotka_image_drop_down_'.$i]
										]
						]
					];
					
					$new_offer_custom_field[$i] = [
						'variation_id'=>$_POST['product_name_'.$i],
						'meta_data_publikacji'=>$_POST['data_publikacji_'.$i],
						'obraz'=>$_POST['zwrotka_image_drop_down_'.$i],
						'before_rabat'=>$_POST['price_before_rabat_'.$i],
						'after_rabat'=>$_POST['price_after_rabat_'.$i]
					];
					
				}
			}
			
			if(	   isset($_POST['product_name_1']) && isset($_POST['data_publikacji_1']) 	) {
			
				$new_offer_custom_field['totals'] = [
					"total_before"=>$_POST['total_from_response_before'],
					"total_after"=>$_POST['total_from_response_after'],
					"diff"=>$_POST['diff']
				];
			
			}
			
			$debug['new_offer'] = $new_offer;
			
			$order_to_custom_field = json_encode($new_offer_custom_field);
			update_post_meta($post_id, 'oferta_zwrotna', $order_to_custom_field);
		
			$update_data = [
				 'line_items' => $new_offer,
				 'meta_data' => [
					[
						'key' => 'is_this_rest_api_call',
						'value' => 'yes'
					]
				 ]
			];

			try {
				$update_order = $woocommerce_vg->put('orders/'.$meta_zwrotka, $update_data);
			} catch (Exception $e) {
				$debug['ERROR_ORDER_UPDATE'] = $e->getMessage();
			}
		
	/*
			$debug['order'] = $order;
			$path = $_SERVER["DOCUMENT_ROOT"]."/SEND.json";
			$myfile = fopen($path, "w") or die("Unable to open file!");
			$string = print_r($debug, true);
			fwrite($myfile, $string);
	*/
		
	}
	
}

add_action( 'save_post', 'wpdocs_save_meta_box' );

;?>