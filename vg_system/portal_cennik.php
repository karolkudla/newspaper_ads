<?php

use Automattic\WooCommerce\Client;

/****************************************************************************************************/
/****************************************************************************************************/
/******************************** CONVERT PORTAL CENNIK TO PRODUCTS *********************************/
/****************************************************************************************************/
/****************************************************************************************************/

function create_banners($values,$newHash,$current_cennik_id) {
	
	global $redaction_id;
	
	/* CREATE */
	$debug['CO_ROBIE'] = 'create_banners';
	
	$d = new DateTime();
	$debug[] = $d->format('Y-m-d\TH:i:s');
	
	/* ZAMIANA STRINGA NA NUMER WOJEWÓDZTWA */
	$prov_replace = [
		'dolnośląskie' => '1',
		'kujawsko-pomorskie' => '2',
		'lubelskie' => '3',
		'lubuskie' => '4',
		'łódzkie' => '5',
		'małopolskie' => '6',
		'mazowieckie' => '7',
		'opolskie' => '8',
		'podkarpackie' => '9',
		'podlaskie' => '10',
		'pomorskie' => '11',
		'śląskie' => '12',
		'świętokrzyskie' => '13',
		'warmińsko-mazurskie' => '14',
		'wielkopolskie' => '15',
		'zachodniopomorskie' => '16'
	];
	
	/* OBRÓBKA TEKSTU WOJEWODZTWO */
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	/* KONWERSJA LOKALIZACJI */
	$lokalizacja = $values['zasieg_geograficzny'];
	foreach ($lokalizacja['markers'] as $labels) {
		$locations[] = $labels['label'];
		$provinces[] = $prov_replace[get_string_between($labels['label'], 'województwo ', ' Polska')];
	};
		
	/* ATRYBUTY DLA WSZYSTKICH BANNERÓW */
	$banner_attributes[] = [	
		'name' 		=> 'Długość Emisji',
		'variation' => true,
		'visible'   => true,
		'options'   =>  [
							'7 dni',
							'14 dni',
							'21 dni',
							'30 dni',
							'45 dni',
							'60 dni',
							'90 dni'
						]
	];	
				
	/* META dla DANYCH PORTALU - STAŁE */
	$meta_data = [
		["key"=>"lokalizacja_medium","value"=>wp_slash(json_encode($locations))],
		["key"=>"numer_redakcji","value"=>$redaction_id],
		["key"=>"naklad_odwiedziny","value"=>$values['ilosc_odwiedzin__miesiecznie']],
		["key"=>"nazwa","value"=>$values['nazwa']],
		["key"=>"adres_url","value"=>$values['adres_www']],
		["key"=>"short_description","value"=>$values['krotki_opis']],
		["key"=>"wojewodztwo","value"=>wp_slash(json_encode($provinces))],
		["key"=>"wysokosc_rabatu","value"=>$values['podaj_wysokosc_rabatu']],
		["key"=>"data_przeslania","value"=>$d->format('Y-m-d\TH:i:s')]
	];
	
	/* PRODUKTY I KATEGORIA NA VG */
	
	$spaces = [
	
		[
			"slug"=>"banner_top",
			"category"=>"31",
			"name"=>"Banner Top",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_top.jpg"
		],
		[
			"slug"=>"banner_poziomy_1",
			"category"=>"30",
			"name"=>"Banner Poziomy 1",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_poziomy_1.jpg"
		],
		[
			"slug"=>"banner_poziomy_2",
			"category"=>"30",
			"name"=>"Banner Poziomy 2",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_poziomy_2.jpg"
		],
		[
			"slug"=>"banner_poziomy_3",
			"category"=>"30",
			"name"=>"Banner Poziomy 3",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_poziomy_3.jpg"
		],
		[
			"slug"=>"banner_pionowy_1",
			"category"=>"29",
			"name"=>"Banner Pionowy 1",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_pionowy_1.jpg"
		],
		[
			"slug"=>"banner_pionowy_2",
			"category"=>"29",
			"name"=>"Banner Pionowy 2",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_pionowy_2.jpg"
		],
		[
			"slug"=>"banner_pionowy_3",
			"category"=>"29",
			"name"=>"Banner Pionowy 3",
			"image"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/banner_pionowy_3.jpg"
		]

	];
	
	foreach ($spaces as $key => $space) {
		$all_spaces_create[] = [
			'name' => $space["name"],
			'type' => 'variable',
			'categories' => [
				['id'=>$space["category"]],
				['id'=>16],
				['id'=>971] /* ID LOCAL WOKULSKI ONLINE GAZETA PAPIEROWA */
			],
			'meta_data' => $meta_data,
			'attributes'  => $banner_attributes,
			'status' => 'pending'
		];
		
		foreach ($banner_attributes[0]['options'] as $number_of_days) {
		
				/* Wysyła wszystkie wariacje, ale pokazuje tylko te ze statusem instock */
				
				if ($values['ceny_po_uwzglednieniu_rabatu_'.$key][str_replace(" ","_",$number_of_days)] == '0.00') {
					$price = '';
					$stock_status = 'outofstock';
				} else {
					$price = $values['ceny_po_uwzglednieniu_rabatu_'.$key][str_replace(" ","_",$number_of_days)];
					$stock_status = 'instock';
				}
				
				$variations_data[$space['slug']][] = 
					[
						'regular_price' => $price,
						'attributes' => [
							[
							'name'=>'Długość Emisji',
							'option'=>$number_of_days
							]
						],
						'meta_data' => [
							[
								"key"=>"redaction_price",
								"value"=>$values['ceny_po_uwzglednieniu_rabatu_'.$key][str_replace(" ","_",$number_of_days)]
							]
						],
						'stock_status'=>$stock_status
					];
					
		}
	}
	
	/* DODANIE OBRAZÓW DO KAŻDEGO Z TYPÓW BANNERÓW */
	foreach ($spaces as $key => $space) {
		$all_spaces_create[$key]['meta_data'][] = [
			"key" => "image",
			"value" => $space['image']
		];
	};
		
	$data = [
		'create' => $all_spaces_create
	];



	/* WARIACJE DLA BANNER TOP */

	foreach ($variations_data as $slug => $values) {
		$variations_to_create[] = ['create'=>$values];
	}
	
	
	/* UTWÓRZ PRODUKTY NA LOCAL I VG */
	$product_ids = send_variable_products_to_local_and_vg($data,$current_cennik_id);
	
	/* UTWÓRZ WARIACJE DLA PRODUKTÓW */
	$variations_mapping = send_variations_to_local_and_vg($product_ids,$variations_to_create);
	
	
	/* Pobierz IDs zmapowanych local->vg wariacji */
	$saved_cenniki = get_field('field_5fc147440faf3',8762);
	$saved_cenniki_array = json_decode($saved_cenniki,true);
	

	$debug['Mapping_Pobrany'] = $saved_cenniki_araray;
	
	/* JEŚLI TAKI CENNIK ISTNIEJE TO BĘDZIE SKASOWANY */ 
	unset($saved_cenniki_array[$current_cennik_id]);
	$debug['Unset'] = $saved_cenniki_array;
	
	/* DODAJE NOWY CENNIK Z TYM ID */
	$saved_cenniki_array[$current_cennik_id] = $variations_mapping;
	$debug['Nowy_Cennik'] = $saved_cenniki_array;


	/* FIELD PODPIĘTY POD JEDEN POST */
	update_field('field_5fc147440faf3',json_encode($saved_cenniki_array),8762);
			
	/* UZUPEŁNIA NOWY HASH */
	update_field('field_5fbfbab72e54d',$newHash,$current_cennik_id);
	

	$path = $_SERVER["DOCUMENT_ROOT"]."/UPPORTAL.json";
	$myfile = fopen($path, "w") or die("Unable to open file!");
	$string = print_r($debug, true);
	fwrite($myfile, $string);
	
	fclose($myfile);

}


/* ZAPIS CENNIKA PORTAL */

add_action('acf/save_post', 'my_acf_save_post_portal');
function my_acf_save_post_portal( $post_id ) {

		global $woocommerce_local;
		global $woocommerce_vg;

		$values = get_fields( $post_id );
		/* Sprawdza czy to gazeta czy portal */
		if ($values['adres_www']) {

			$debug['CO_ZAPISUJEMY'] = 'CENNIK PORTAL';

			/* ZAPISZ NOWY HASH Z PODANYCH DANYCH */
			unset($values['hash']);
			unset($values['vg_ids']);
			unset($values['local_ids']);

			$newHash = md5(json_encode($values));
		
			$oldHash = get_field('field_5fbfbab72e54d',$post_id);
			
			$debug['oldHash'] = $oldHash;
			$debug['newHash'] = $newHash;
			
		
			if ($oldHash == '') {
				create_banners($values,$newHash,$post_id);
				$debug['CO_ROBIE'] = 'NOWY PRODUKT';
			}
		
			
			if ($oldHash !== '') {
				
				if ($oldHash !== $newHash) {
					
					$debug['CO_ROBIE'] = 'UPDATE PRODUKTU - DELETE AND CREATE';
					
					/* POBIERA ID PRODUKTÓW VG */
					/* DELETE VG */
					$ids_to_delete_vg = get_field('field_5fbfbac52e54e',$post_id);
					$ids_to_delete_array_vg = json_decode($ids_to_delete_vg,true);
					$delete_vg = ['delete'=> $ids_to_delete_array_vg];
					
					/* POBIERA ID PRODUKTÓW LOCAL */
					/* DELETE LOCAL */
					$ids_to_delete_local = get_field('field_5fbfbace2e54f',$post_id);
					$ids_to_delete_array_local = json_decode($ids_to_delete_local,true);
					$delete_local = ['delete'=> $ids_to_delete_array_local];
					
					
					try {
						$del_vg = $woocommerce_vg->post('products/batch', $delete_vg);
						$del_local = $woocommerce_local->post('products/batch', $delete_local);
						$debug[] = 'USUNALEM WARIACJE';
					} catch (Exception $e) {
						$debug['ERROR_DELETE_VARS'] = $e->getMessage();
					}
					
					/* TWORZY PRODUKTY Z WARIACJAMI OD NOWA */
					create_banners($values,$newHash,$post_id);
					
				} else {
					$debug['CO_ROBIE'] = 'HASH TEN SAM - NIC NIE ROBIĘ';
				}
			}
			
		} else {
			
			$debug[] = 'Brak adresu www - to nie portal';
			
		}

/*
		$path = $_SERVER["DOCUMENT_ROOT"]."/PORTAL.json";
		$myfile = fopen($path, "w") or die("Unable to open file!");
		$string = print_r($debug, true);
		fwrite($myfile, $string);
		
		fclose($myfile);
*/

}

;?>