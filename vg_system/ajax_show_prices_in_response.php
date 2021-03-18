<?php 

/***************************************************************************************************/
/***************************************************************************************************/
/*************************** POKAŻ CENY MIEJSC REKLAMOWYCH W ZWROTCE *******************************/
/***************************************************************************************************/
/***************************************************************************************************/

function get_prices() {
	
	$rabat_procent = get_post_meta( $_POST['parent_id'] , 'wysokosc_rabatu' , true );
	$rabat_decimal = $rabat_procent/100;
	
	$variable_product = new WC_Product_Variation( $_POST['variation_id'] );
	/* CENA PO RABACIE JEST WPISANA W PRODUKT */
	$after = $variable_product ->regular_price;
	
	/* CENA PRZED RABATEM MUSI ZOSTAĆ OBLICZONA */
	$x = 1 - $rabat_decimal;
	$before = $after / $x;
	
	$prices = [
		"before"=>$before,
		"after"=>$after
	];
	
	/*
			$debug['parent_id'] = $_POST['parent_id'];
			$debug['variation_id'] = $_POST['variation_id'];
			$debug['rabat_procent'] = $rabat_procent;
			$debug['bez_rabatu'] = $before;
			$debug['z_rabatem'] = $after;
	
			$path = $_SERVER["DOCUMENT_ROOT"]."/RABATY.json";
			$myfile = fopen($path, "w") or die("Unable to open file!");
			$string = print_r($debug, true);
			fwrite($myfile, $string);
	*/
	
	wp_send_json($prices);
	wp_die();
	
}

add_action( 'wp_ajax_nopriv_get_prices', 'get_prices' );
add_action( 'wp_ajax_get_prices', 'get_prices' );

;?>