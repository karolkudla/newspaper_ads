<?php

/****************************************************************************************************/
/****************************************************************************************************/
/*************************************** CENNIK GAZETY PAGE *****************************************/
/****************************************************************************************************/
/****************************************************************************************************/

add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
		add_menu_page(
			__( 'Cenniki Gazet', 'my-textdomain' ),
			__( 'Cenniki Gazet', 'my-textdomain' ),
			'edit_pages',
			'cennik-gazety',
			'cennik_gazety_page_contents',
			'dashicons-schedule',
			3
		);
	}

function cennik_gazety_page_contents() {
	
	global $woocommerce_vg;
	
	acf_form_head();
		
	/* Pobrać nazwy gazet i wyświetlić jako nazwy */
	/* ID Postów do których podpięte są cenniki */
	$tabs = [8704,8706,8708,8710,8712,8714,8716,8718,8721,8723];
	
	foreach ($tabs as $tab) {
		$newspaper_names[] = get_field('field_5fa83482b0088',$tab);
	}
	
	?>
	
	<style>
		.tab-content:not(.default-tab) {
			display: none;
		}
		.tab-content {
			padding: 10px 0 0 20px;
		}
		.nav-tab {
			cursor: pointer;
		}
	</style>
	
	<div class="wrap">
	
		<nav class="nav-tab-wrapper">
		
		<?php 
			foreach ($newspaper_names as $key => $name) {
				$empty = $key+1;
		;?>
				<div class="nav-tab" id="nav-tab-<?php echo $key+1;?>"><?php if ($name) {echo $name;} else {echo 'Cennik '.$empty;};?></div>
		<?php
			}
		;?>
		 
		</nav>
		
		<?php

			if (	isset($_GET['del'])	&& check_admin_referer( 'del_cennik' )	) {
				
				/* Pobierz Idki local i VG */
				$ids_local = json_decode(get_field('field_5fabef4f77afd',$_GET['del']),true);
				$ids_vg = json_decode(get_field('field_5faa818bd646c',$_GET['del']),true);
								
				if (	(count($ids_local) > 0) && (count($ids_vg) > 0) 	) {
													
					/* Usuń z Local */
					foreach ($ids_local as $local_id) {
						$test[] = wp_delete_post($local_id);
					}
					
					/* Usuń z VG */
					$delete_vg = ['delete'=> $ids_vg];

					try {
						$del_vg = $woocommerce_vg->post('products/batch', $delete_vg);
					} catch (Exception $e) {
						$debug['ERROR_DELETE_VARS'] = $e->getMessage();
					}
					
					/* Usuń z Mappingu, jeśli usunięte z local i vg */
					$vg_vari_ids_gazety = json_decode(get_field('field_5fbd3e90dee16',8725),true);
					unset($vg_vari_ids_gazety[$_GET['del']]);
					update_field('field_5fbd3e90dee16',json_encode($vg_vari_ids_gazety),8725);
						
					/* Usuń z formy */				
					foreach (get_fields($_GET['del']) as $field_name => $field_value) {
						update_field($field_name, '', $_GET['del']);
					}
				
				} else {
					echo "Błąd - ten cennik nie istnieje.";
				}
				
				header("Location: ".home_url()."/wp-admin/admin.php?page=cennik-gazety");
				
			}

		foreach ($tabs as $key => $post_id) { $num = $key+1; ?>
			
			<div class="tab-content <?php if ($key==0) {echo 'default-tab';};?>" cennik="nav-tab-<?php echo $num;?>">	
			
				<h1>Cennik nr <?php echo $num;?></h1>
				<h2>Dane gazety nr <?php echo $num;?></h2>
				
				<div class="cennik_form cennik_<?php echo $num;?>" >
					<?php 
						$options = array(
							'post_id' => $post_id,
							'field_groups' => array(8040),
							'form' => true, 
							'html_before_fields' => '',
							'html_after_fields' => '',
							'submit_value' => 'Zapisz cennik',
							'updated_message' => __('Cennik zapisany pomyślnie', 'acf')
						);
						acf_form( $options );
					?>
				</div>
			
				<?php if (	 get_field('nazwa',$post_id) !== ''   )  {;?>
				<div style="width: 100%; display: flex; justify-content: flex-end;">
					<a href="<?php echo wp_nonce_url( home_url().'/wp-admin/admin.php?page=cennik-gazety&del='.$post_id, 'del_cennik' );?>" style="color: red;">Usuń Cennik</a>
				</div>
				<?php };?>
			
		</div>
			
		<?php } ;?>

		

	</div>

	<?php
}

/* ZABLOKUJ POLA Z OBLICZONYM RABATEM W CENNIKU GAZETY */
function acf_read_only( $field ) { $field['readonly'] = 1; return $field; }
add_filter('acf/load_field/key=field_5fa877096a676', 'acf_read_only');
add_filter('acf/load_field/key=field_5fa879a7b0eb1', 'acf_read_only');
add_filter('acf/load_field/key=field_5fa879beb0eb2', 'acf_read_only');
add_filter('acf/load_field/key=field_5fa879d7b0eb3', 'acf_read_only');
/* add_filter('acf/load_field/key=field_5fa97320e5a46', 'acf_read_only'); */


;?>