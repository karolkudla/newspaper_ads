<?php

function custom_menu_page_removing() {
	
	$user = wp_get_current_user();
	if ( in_array( 'administrator', (array) $user->roles ) == FALSE) {
		
		global $menu;
		foreach ($menu as $m) {
			if (
					$m[2] !== 'edit.php?post_type=shop_order' &&
					$m[2] !== 'cennik-gazety' &&
					$m[2] !== 'cennik-portale'
				) {
					remove_menu_page($m[2]);					
				}
		}
		
	}

}
add_action( 'admin_menu', 'custom_menu_page_removing' , 999);

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'VG Zamówienia', 'vg-zamowienia' ),
        'VG Zamówienia',
        'edit_posts',
        '/edit.php?post_type=shop_order',
        '',
        '',
        1
    );
}

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/* STYLE CENNIKA GAZET i PORTALI */

add_action('admin_head', 'my_custom_styles');

function my_custom_styles() {
  echo '<style>
  
	#screen-meta-links, 
	.woocommerce-layout {
		display: none;
	}
  
	.cennik_form form {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	
	.cennik_form .acf-form-submit input {
		margin: 10px;
	}
  
	.cennik_form form > .acf-fields {
		width: 100%;
		display: flex;
		margin-top: 10px;
	}
	
	.cennik_form form .acf-fields > .acf-field-accordion {
		margin: 5px;
	}
	
	/* GAZETY */
	
	.acf-field-5fa850a10aef4 {
		width: 50%;
	}
	
	.acf-field-5fa850e1d608b, 
	.acf-field-5fa875f6a9868 {
		width: 18%;
	}
	
	.acf-field-5fa850e1d608b input, 
	.acf-field-5fa875f6a9868 input {
		text-align: right;
	}
	
	
	.acf-field .acf-label label {
		font-weight: 400 !important;
	}
	
	.acf-field-5fa94c53fc59e {
		height: 88px;
		text-align: center;
		font-weight: 500;
	}
	
	.acf-field-5fa97320e5a46,
	.acf-field-5faa818bd646c,
	.acf-field-5fabef4f77afd,
	
	.acf-field-5fbfbab72e54d,
	.acf-field-5fbfbac52e54e,
	.acf-field-5fbfbace2e54f
	{
		display: none;
	}
	
	/* PORTALE */
	
	.cennik_form form > .acf-fields {
		flex-wrap: wrap;
	}
	
	.acf-field-5fbeb2b23ac30 {
		width: 100%;
		margin-top: 20px !important;
	}
	
	.acf-field-5fbeafbbfaa3f,
	.acf-field-5fbeb16841175 {
		width: 48%;
	}
	
	.acf-field-5fbeb2b23ac30 .acf-fields {
		text-align: left;
	}
	
	/* Rabat Input */
	.acf-field-5fbeba39765ad {
		width: 20%;
	}
	
	.updated:first-of-type {
		display: block;
	}
	
	.updated {
		display: none;
	}

  </style>';
}

;?>