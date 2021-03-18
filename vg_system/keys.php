<?php 

	use Automattic\WooCommerce\Client;

	/********************************/
	/* DOPISAĆ Wyciąganie urla i id */
	/* Wyjmij Home Urla i Id */
	/* Włóż do urla i redaction_id */
	/********************************/
	
	$redaction_id = 'R000051';

	$woocommerce_vg = new Client(
		'https://gazeta.wokulski.online',
		'ck_94a52f57e7b19a60c2546dc5125b0d5bd7ce315f',
		'cs_6fcb9eba7dbc0e470feca5ab12fd172fb79dad28',
		[
			'wp_api' => true,
			'version' => 'wc/v3'
		]
	);

	/* A adres z paska adresu */

	$woocommerce_local = new Client(
		'https://gazeta1.wokulski.online',
		'ck_9d257932c663ac95fc7e91e1e66310eb824f9e8c',
		'cs_b685836f54a98b152372cda99e8e2498ccba6f46',
		[
			'wp_api' => true,
			'version' => 'wc/v3'
		]
	);

;?>

