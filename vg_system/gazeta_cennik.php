<?php

use Automattic\WooCommerce\Client;

function create_pages($values,$newHash,$current_cennik_id) {
	
	global $redaction_id;
	
	/* CREATE */
	$debug['CO_ROBIE'] = 'create gazeta';
	
	$d = new DateTime();
	$debug[] = $d->format('Y-m-d\TH:i:s');
	
	/* ATRYBUTY DLA STRONY PIERWSZEJ */
	$first_page_attributes[] = [	
		'name' 		=> 'Ilość Modułów',
		'variation' => true,
		'visible'   => true,
		'options'   => ['10 modułów','20 modułów','30 modułów']
	];
	
	/* ATRYBUTY DLA STRONY REDAKCYJNEJ */
	$redakcyjna_page_attributes[] = [
		'name' 		=> 'Ilość Modułów',
		'variation' => true,
		'visible'   => true,
		'options'   => [
			'1 moduł',
			'2 moduły',
			'3 moduły',
			'4 moduły',
			'5 modułów',
			'10 modułów',
			'15 modułów',
			'20 modułów',
			'25 modułów',
			'30 modułów',
			'35 modułów',
			'40 modułów'
		]
	];
	
	/* ATRYBUTY DLA ROZKŁADÓWKI */
	$rozkladowka_page_attributes[] = [
		'name' 		=> 'Ilość Modułów',
		'variation' => true,
		'visible'   => true,
		'options'   => [
			'80 modułów'
		]
	];
	
	/* ATRYBUTY DLA STRONY OSTATNIEJ */
	$last_page_attributes[] = [	
		'name' 		=> 'Ilość Modułów',
		'variation' => true,
		'visible'   => true,
		'options'   => ['10 modułów','20 modułów','30 modułów']
	];
	
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
	$lokalizacja = $values['miejsce_wydawania'];
	foreach ($lokalizacja['markers'] as $labels) {
		$locations[] = $labels['label'];
		$provinces[] = $prov_replace[get_string_between($labels['label'], 'województwo ', ' Polska')];
	};
		
	/* META dla DANYCH GAZETY - STAŁE */
	$meta_data = [
		["key"=>"lokalizacja_medium","value"=>wp_slash(json_encode($locations))],
		["key"=>"czestotliwosc_wydawania","value"=>$values['czestotliwosc_wydawania']],
		["key"=>"dzien_wydania","value"=>$values['dzien_wydania']],
		["key"=>"numer_redakcji","value"=>$redaction_id],
		["key"=>"naklad_odwiedziny","value"=>$values['naklad']],
		["key"=>"nazwa","value"=>$values['nazwa']],
		["key"=>"short_description","value"=>$values['opis']],
		["key"=>"wojewodztwo","value"=>wp_slash(json_encode($provinces))],
		["key"=>"wysokosc_rabatu","value"=>$values['rabat_dla_reklamy_w_gazecie_w_procentach']],
		["key"=>"data_przeslania","value"=>$d->format('Y-m-d\TH:i:s')]
	];
	
	/* DANE */
	/* STRONA PIERWSZA */
	$first_page_data = [
		'name' => 'Strona Pierwsza (Okładka)',
		'type' => 'variable',
		'categories' => [
			['id'=>35],
			['id'=>17],
			['id'=>967] /* ID LOCAL WOKULSKI ONLINE GAZETA PAPIEROWA */
		],
		'meta_data' => $meta_data,
		'attributes'  => $first_page_attributes,
		'status' => 'pending'
	];
	
	$first_page_data['meta_data'][] = [
		"key"=>"image",
		"value"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/pierwsza.png"
	];
	
	/* STRONA REDAKCYJNA */
	$redakcyjna_page_data = [
		'name' => 'Strony Redakcyjne',
		'type' => 'variable',
		'categories' => [
			['id'=>32],
			['id'=>17],
			['id'=>967] /* ID LOCAL WOKULSKI ONLINE GAZETA PAPIEROWA */
		],
		'meta_data' => $meta_data,
		'attributes'  => $redakcyjna_page_attributes,
		'status' => 'pending'
	];
	
	$redakcyjna_page_data['meta_data'][] = [
		"key"=>"image",
		"value"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/redakcyjne.png"
	];
	
	/* STRONA ROZKŁADÓWKA */
	$rozkladowka_page_data = [
		'name' => 'Rozkładówka',
		'type' => 'variable',
		'categories' => [
			['id'=>33],
			['id'=>17],
			['id'=>967] /* ID LOCAL WOKULSKI ONLINE GAZETA PAPIEROWA */
		],
		'meta_data' => $meta_data,
		'attributes'  => $rozkladowka_page_attributes,
		'status' => 'pending'
	];
	
	$rozkladowka_page_data['meta_data'][] = [
		"key"=>"image",
		"value"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/rozkladowka.png"
	];
	
	/* STRONA OSTATNIA */
	$last_page_data = [
		'name' => 'Strona Ostatnia',
		'type' => 'variable',
		'categories' => [
			['id'=>34],
			['id'=>17],
			['id'=>967] /* ID LOCAL WOKULSKI ONLINE GAZETA PAPIEROWA */
		],
		'meta_data' => $meta_data,
		'attributes'  => $last_page_attributes,
		'status' => 'pending'
	];
	
	$last_page_data['meta_data'][] = [
		"key"=>"image",
		"value"=>"https://gazeta.wokulski.online/wp-content/uploads/2020/12/ostatnie.png"
	];
	
	$data = [
		'create' => [
			$first_page_data,
			$redakcyjna_page_data,
			$rozkladowka_page_data,
			$last_page_data
		]
	];
	
	/* OBLICZENIE CEN MODUŁÓW STRONY REDAKCYJNEJ */
	$redakcyjna_modules = [
		'1'=>1,
		'2'=>2,
		'3'=>3,
		'4'=>4,
		'5'=>5,
		'10'=>10,
		'15'=>15,
		'20'=>20,
		'25'=>25,
		'30'=>30,
		'35'=>35,
		'40'=>40
	];
	
	foreach ($redakcyjna_modules as $key => $rmodules) {
		$redakcyjna_prices[$key] = $rmodules * $values['cena_1_modulu_na_stronie_redakcyjnej_uwzgledniony_rabat'];
	}

	/* WARIACJE DLA STRONY PIERWSZEJ */					
	$first_page_variations = ['create' => 
	
		[
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*5,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'5 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*5,
					]
				]
			],
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*10,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'10 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*10,
					]
				]
			],
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*15,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'15 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*15,
					]
				]
			]
		],
		[
			'regular_price' => $values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*20,
			'attributes' => [
				[
				'name'=>'Ilość Modułów',
				'option'=>'20 modułów'
				]
			],
			'meta_data' => [
				[
					"key"=>"redaction_price",
					"value"=>$values['cena_1_modulu_na_stronie_pierwszej_uwzgledniony_rabat']*20,
				]
			]
		]
	
	];
	
	/* WARIACJE DLA STRONY REDAKCYJNEJ */
	$redakcyjna_page_variations = ['create' => 
	
		[
			[
				'regular_price' => $redakcyjna_prices['1'],
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'1 moduł'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$redakcyjna_prices['1'],
					]
				]
			],
			[
				'regular_price' => $redakcyjna_prices['2'],
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'2 moduły'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$redakcyjna_prices['2'],
					]
				]
			],
			[
				'regular_price' => $redakcyjna_prices['3'],
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'3 moduły'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$redakcyjna_prices['3'],
					]
				]
			]
		]
	
	];
	
	/* WARIACJE DLA ROZKŁADÓWKI */
	$rozkladowka_page_variations = ['create' => 
	
		[
			[
				'regular_price' => $values['cena_1_modulu_na_rozkladowce_uwzgledniony_rabat']*80,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'80 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_rozkladowce_uwzgledniony_rabat']*80,
					]
				]
			]
		]
	
	];
	
	/* WARIACJE DLA STRONY OSTATNIEJ */					
	$last_page_variations = ['create' => 
	
		[
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*10,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'10 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*10,
					]
				]
			],
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*20,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'20 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*20,
					]
				]
			],
			[
				'regular_price' => $values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*30,
				'attributes' => [
					[
					'name'=>'Ilość Modułów',
					'option'=>'30 modułów'
					]
				],
				'meta_data' => [
					[
						"key"=>"redaction_price",
						"value"=>$values['cena_1_modulu_na_stronie_ostatniej_uwzgledniony_rabat']*30,
					]
				]
			]
		]
	
	];
	
	$variations_to_create = [
		$first_page_variations,
		$redakcyjna_page_variations,
		$rozkladowka_page_variations,
		$last_page_variations
	];
	
	/* UTWÓRZ STRONY (PIERWSZA, OSTATNIA, REDAKCYJNA, ROZKŁADÓWKA) */
	
	$product_ids = send_variable_products_to_local_and_vg($data,$current_cennik_id);
	
	/* UTWÓRZ WARIACJE */
	
	$variations_mapping = send_variations_to_local_and_vg($product_ids,$variations_to_create);
		
	/* SPRAWDZA CZY TAKI NUMER CENNIKA JUŻ JEST */
	/* JEŚLI TAK TO KASUJE GO I WSTAWIA NOWE DANE POD TEN KLUCZ (NUMER CENNIKA) */
	/* JEŚLI NIE TO POPROSTU DOPISUJE NOWE DANE */
	/* ZAPISUJE IDKI VARIACJI W FORMACIE LOCAL:VG, ABY MÓC WYSYŁAĆ ZWROTKI */
			
	$saved_cenniki = get_field('field_5fbd3e90dee16',8725);
	$saved_cenniki_array = json_decode($saved_cenniki,true);
			
			
			
	/* JEŚLI TAKI CENNIK ISTNIEJE TO BĘDZIE SKASOWANY */ 
	unset($saved_cenniki_array[$current_cennik_id]);
	/* DODAJE NOWY CENNIK */
	$saved_cenniki_array[$current_cennik_id] = $variations_mapping;



	/* FIELD PODPIĘTY POD JEDEN POST */
	update_field('field_5fbd3e90dee16',json_encode($saved_cenniki_array),8725);
			
	/* UZUPEŁNIA NOWY HASH */
	update_field('field_5fa97320e5a46',$newHash,$current_cennik_id);



	$path = $_SERVER["DOCUMENT_ROOT"]."/CENNIK.json";
	$myfile = fopen($path, "w") or die("Unable to open file!");
	$string = print_r($debug, true);
	fwrite($myfile, $string);
	
	fclose($myfile);
	
	
	
		
}

/* ZAPIS CENNIKA GAZETY */

add_action('acf/save_post', 'my_acf_save_post_gazeta');
function my_acf_save_post_gazeta( $post_id ) {

		global $woocommerce_vg;
		global $woocommerce_local;

		$debug["CENNIK_ID"] = $post_id;

		$values = get_fields( $post_id );
		/* Sprawdza czy to gazeta czy portal */
		if ($values['czestotliwosc_wydawania']) {

			/* ZAPISZ NOWY HASH Z PODANYCH DANYCH */
			unset($values['hash']);
			unset($values['vg_ids']);
			unset($values['local_ids']);

			$newHash = md5(json_encode($values));
		
			$debug[] = $values;
			
			$oldHash = get_field('field_5fa97320e5a46',$post_id);
			
			$debug['oldHash'] = $oldHash;
			$debug['newHash'] = $newHash;
			
			if ($oldHash == '') {
				create_pages($values,$newHash,$post_id);
			}
			
			if ($oldHash !== '') {
				
				if ($oldHash !== $newHash) {
					
					$debug['CO_ROBIE'] = 'RÓŻNE HASHE - DELETE AND UPDATE';
					
					/* POBIERA ID PRODUKTÓW VG */
					/* DELETE VG */
					$ids_to_delete_vg = get_field('field_5faa818bd646c',$post_id);
					$ids_to_delete_array_vg = json_decode($ids_to_delete_vg,true);
					$delete_vg = ['delete'=> $ids_to_delete_array_vg];
					
					/* POBIERA ID PRODUKTÓW LOCAL */
					/* DELETE LOCAL */
					$ids_to_delete_local = get_field('field_5fabef4f77afd',$post_id);
					$ids_to_delete_array_local = json_decode($ids_to_delete_local,true);
					$delete_local = ['delete'=> $ids_to_delete_array_local];
					
					try {
						$del_vg = $woocommerce_vg->post('products/batch', $delete_vg);
						$del_local = $woocommerce_local->post('products/batch', $delete_local);
						$debug[] = 'USUNALEM WARIACJE';
					} catch (Exception $e) {
						$debug['ERROR_DELETE_VARS'] = $e->getMessage();
					}
					
					create_pages($values,$newHash,$post_id);
					
				} else {
					$debug['CO_ROBIE'] = 'HASH TEN SAM - NIC NIE ROBIĘ';
				}
			}
			
		} else {
			
			$debug[] = 'Brak nazwy gazety';
			
		}

/*
			$d = new DateTime();
			$debug[] = $d->format('Y-m-d\TH:i:s');
			
			$path = $_SERVER["DOCUMENT_ROOT"]."/GAZETA.json";
			$myfile = fopen($path, "w") or die("Unable to open file!");
			$string = print_r($debug, true);
			fwrite($myfile, $string);
			
			fclose($myfile);
*/

}

;?>