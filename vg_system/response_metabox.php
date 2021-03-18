<?php

/****************************************************************************************************/
/****************************************************************************************************/
/************************************* ZWROTKA ORDER META BOX ***************************************/
/****************************************************************************************************/
/****************************************************************************************************/

/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Oferta zwrotna', 'textdomain' ), 'wpdocs_my_display_callback', 'shop_order', 'normal', 'core' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wpdocs_my_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
	
	$order = wc_get_order( $post->ID );
	$order_items_count = count($order->get_items());

	$args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
		 'tax_query' => array( array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug', // Or 'term_id' or 'name'
				'terms'            => array('wokulski-online-gazeta-papierowa','wokulski-online-bannery-na-portalu') // A slug term
				// 'include_children' => false // or true (optional)
			)),
    );
	
	$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				$product = wc_get_product( get_the_id() );
				$variations_arrays[] = $product->get_children();
				$variations = new RecursiveIteratorIterator(new RecursiveArrayIterator($variations_arrays));
			endwhile;
		} else {
			echo __( 'No products found' );
		}
	wp_reset_postdata();
	
	;?>
	
	<style>
		.zwrotna_line {
			display: flex; 
			align-items: center;
			border-bottom: 1px solid lightgray;
		}
		
		.zwrotna_line div {
			padding: 5px;
			display: block;
		}
		
		input[type="number"] {
			text-align: right;
		}
		
		.select2-search--dropdown {
			display: none;
		}
	</style>
	
	<h3 style="margin-left: 10px; margin-bottom: 10px;">Skomponuj ofertę zwrotną na podstawie oferty zaproponowanej przez klienta</h3>
	
	<form>
		<?php
		
		$order_items = $order->get_items();
		foreach ( $order->get_items() as $item_id => $item ) {
			$order_data[] = [
				"nazwa_miejsca" => $item->get_name(),
				"nazwa_gazety" => $item->get_meta("Nazwa",true),
				"prod_id" => $item->get_product_id(),
				"data_publikacji" => $item->get_meta('Data Publikacji'),
				"obraz" => $item->get_meta('Obraz',true),
				"cena" => $item->get_total()
			];
		}
		
		$meta = json_decode(get_post_meta($post->ID,'oferta_zwrotna',true),true);
		if ($meta !== null) {
			$readonly = 'disabled';
		}

		for ($i=0;$i<$order_items_count+3;$i++) {
		;?>
			<div class="zwrotna_line">
				<div><?php echo $i+1;?></div>
				
				<div style="width: 100px; text-align: center;">
				
				<?php if ($order_data[$i]["obraz"]) { ;?>
				
					<a href="<?php echo $order_data[$i]['obraz'];?>" target="_blank"><img src="<?php echo $order_data[$i]['obraz'];?>" style="height: 100px; height: 100px; width: 100%; object-fit: contain;"></a>
					<select name="zwrotka_image_drop_down_<?php echo $i;?>" style="display:none;">
						<option value="<?php echo $order_data[$i]['obraz'];?>" selected></option>
					</select>
					
				<?php ;} else { ;?>
					
				<select class="zwrotka_image_drop_down" name="zwrotka_image_drop_down_<?php echo $i;?>" style="width: 100px;">
					<?php foreach (	$order_data as $key => $values ) {
							if ($meta[$i]['obraz'] == $values['obraz']) {$selected = 'selected';} else {$selected = '';};
							echo '<option value="'.$values['obraz'].'" data-img_src="'.$values['obraz'].'" '.$selected.'></option>';
						  } 
					;?>
				</select>
				
				<?php };?>	
					
				</div>
				<div style="width: 55%;">
				
					<div>
					<?php if ($order_data[$i]["nazwa_gazety"]) { ;?>
						<span style="font-size: 12px; color: gray;">Propozycja Klienta:</span><br><span style="color: blue"><?php echo $order_data[$i]["nazwa_gazety"];?> - <?php echo $order_data[$i]["nazwa_miejsca"];?> - <?php echo $order_data[$i]["cena"];?> zł</span>
					<?php } else { ;?>
						Dodatkowe miejsce reklamowe:
					<?php };?>
					</div>
					
					<select class="response_order_select_ad_space" order="<?php echo $i+1;?>" name="product_name_<?php echo $i;?>" <?php echo $readonly;?>>
						<option value=''>Wybierz miejsce reklamowe</option>
						<?php
							foreach ($variations as $key => $variation_id) {
								$variation_data = wc_get_product($variation_id);
								$parent_id = $variation_data->get_parent_id();
								if ($meta[$i]['variation_id'] == $variation_id) {$selected = 'selected';} else {$selected = '';};
								
								if ($variation_data->get_price() !== '') {
									echo '<option  parent_id="'.$parent_id.'" value="'.$variation_id.'" '.$selected.'>'.get_post_meta($parent_id,'nazwa',true).' - '.$variation_data->get_formatted_name().' - '.$variation_data->get_price().' zł</option>';
								}
							};
						;?>
					</select>
					
				</div>
				<div>
				
					<div>
					<?php if ($order_data[$i]["data_publikacji"]) { ;?>
						<span style="font-size: 12px; color: gray;">Propozycja Klienta:</span><br>
					<?php } else { ;?>
						Dodatkowa Data:
					<?php };?>
						<span style="color: blue"><?php echo $order_data[$i]['data_publikacji'];?></span>
					</div>
					<input style="width: 140px;" type="date" name="data_publikacji_<?php echo $i;?>" value="<?php echo $meta[$i]['meta_data_publikacji'];?>" <?php echo $readonly;?>>
					
				</div>
				
				<div style="display: none;">
					<div><span style="font-size: 12px; color: gray;">Cena:</span><br><span>przed rabatem</span></div>
					<input type="number" class="price_before_rabat" order="<?php echo $i+1;?>" style="width: 70px;"  name="price_before_rabat_<?php echo $i;?>" value="<?php echo $meta[$i]['before_rabat'];?>" readonly> zł
				</div>
				
				<div style="display: none;">
					<div><span style="font-size: 12px; color: gray;">Cena:</span><br><span>po rabacie</span></div>
					<input type="number" class="price_after_rabat" order="<?php echo $i+1;?>" style="width: 70px;"  name="price_after_rabat_<?php echo $i;?>" value="<?php echo $meta[$i]['after_rabat'];?>" readonly> zł
				</div>
			</div>
			
		<?php
		}
		;?>
				
		<div style="text-align: right; margin-top: 10px;">
			<div style="font-size: 20px; margin: 20px 0;">Podsumowanie:</div>
			<div style="margin: 5px 0;">Zamówienie klienta na kwotę:</div>
			<input class="client_order_total" style="width:120px;" type="number" value="<?php echo $order->get_total();?>" readonly> zł
			
			<div style="display: flex; justify-content: flex-end;">
				<div style="margin-right: 10px;">
					<div style="margin: 5px 0;">Suma propozycji przed rabatem:</div>
					<input style="width:120px;" type="number" name="total_from_response_before" class="total_from_response_before" value="<?php echo $meta['totals']['total_before'];?>" readonly> zł
				</div>
				<div>
					<div style="margin: 5px 0;">Suma propozycji po rabacie:</div>
					<input style="width:120px;" type="number" name="total_from_response_after" class="total_from_response_after" value="<?php echo $meta['totals']['total_after'];?>" readonly> zł
				</div>
			</div>
			
			<div style="margin: 5px 0;">Różnica:</div>
			<input class="client_order_diff" name="diff" style="width:120px;" type="number" value="<?php echo $meta['totals']['diff'];?>" readonly> zł
			
			
			<div class="client_total_info" style="margin: 10px 0; font-size: 16px; font-weight: 500;"></div>
			
		</div>
	<?php
		if ($meta == '') {
	;?>
			<div style="text-align: center; margin: 10px;"><button type="submit" class="button-primary">Wyślij propozycję zwrotną</button></div>
	<?php
		} else {
	;?>	
			<div style="text-align: center; font-size: 16px; color: darkred; font-weight: 500;">Propozycja zwrotna została już wysłana.</div>
		<?php } ;?>
	</form>
	
	<?php
		
}

/* ODBLOKUJ STANDARDOWE CUSTOM FIELDY */
/* add_filter('acf/settings/remove_wp_meta_box', '__return_false'); */

;?>