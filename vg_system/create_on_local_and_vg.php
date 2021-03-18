<?php 

use Automattic\WooCommerce\Client;

/* Dla dat przesłania */
date_default_timezone_set('Europe/Warsaw');

/****************************************************************************************************/
/****************************************************************************************************/
/********************************** WYŚLIJ PRODUKTY DO LOCAL I VG ***********************************/
/****************************************************************************************************/
/****************************************************************************************************/

function send_variable_products_to_local_and_vg($data,$current_cennik_id) {
		
		global $woocommerce_local;
		global $woocommerce_vg;
		
		try {
		
			$create_all_local = $woocommerce_local->post('products/batch', $data);
			$response_local = json_decode(json_encode($create_all_local),true);
			
			/* ZCZYTAJ IDKI PRODUKTÓW LOCAL I DODAJ DO KREACJI PRZY PRODUKTACH WYSYŁANYCH NA VG */
			foreach ($response_local['create'] as $key => $product_data) {
				$data['create'][$key]['meta_data'][] = 
				[
					"key"=>"product_mapping",
					"value"=>$product_data['id']
				];	
				$ids_local[] = $product_data['id'];
			}

			$create_all_vg = $woocommerce_vg->post('products/batch', $data);
			$response_vg = json_decode(json_encode($create_all_vg),true);
			
			/* ZCZYTAJ IDKI PRODUKTÓW Z VG */
			foreach ($response_vg['create'] as $key => $product_data) {
				$ids_vg[] = $product_data['id'];
			}
					
			/* ZAPISUJE IDKI PRODUKTÓW NA VG, aby móc później usuwać i update'ować cennik */
			update_field('field_5faa818bd646c',json_encode($ids_vg),$current_cennik_id);
			
			/* ZAPISUJE IDKI PRODUKTÓW NA LOCAL, aby móc później usuwać i update'ować cennik */
			update_field('field_5fabef4f77afd',json_encode($ids_local),$current_cennik_id);
		
		} catch (Exception $e) {
			$debug['error_send_variable_products_to_local_and_vg'] = $e->getMessage();
		}
		
		$response = [
			"local"=>$response_local,
			"vg"=>$response_vg
		];
		
		return $response;
			
	}

/****************************************************************************************************/
/****************************************************************************************************/
/********************************** WYŚLIJ WARIACJE DO LOCAL I VG ***********************************/
/****************************************************************************************************/
/****************************************************************************************************/

	function send_variations_to_local_and_vg($product_ids,$variations_to_create) {	
			
		global $woocommerce_local;
		global $woocommerce_vg;
			
		foreach ($product_ids['local']['create'] as $key => $product_data) {
			
			try {
				$create_local = $woocommerce_local->post('products/'.$product_data['id'].'/variations/batch', $variations_to_create[$key]);
				$response_local = json_decode(json_encode($create_local),true);
				
				foreach ($response_local['create'] as $keyx => $variation_data) {
					$variations_to_create[$key]['create'][$keyx]['meta_data'][] = [
						"key"=>'variation_mapping',
						"value"=>$variation_data["id"]
					];
				}
				
				$create_vg = $woocommerce_vg->post('products/'.$product_ids['vg']['create'][$key]['id'].'/variations/batch', $variations_to_create[$key]);
				$response_vg = json_decode(json_encode($create_vg),true);
				
				foreach ($response_vg['create'] as $keyz => $variation_data_vg) {
					$local_to_vg_variations[$response_local['create'][$keyz]['id']] = $variation_data_vg['id'];
				}
				
			} catch (Exception $e) {
				$debug['error_send_variations_to_local_and_vg'] = $e->getMessage();
			}
			
		}

		return $local_to_vg_variations;
			
	}
	
;?>