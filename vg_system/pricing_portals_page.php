<?php

/****************************************************************************************************/
/****************************************************************************************************/
/*************************************** CENNIK PORTALE PAGE *****************************************/
/****************************************************************************************************/
/****************************************************************************************************/

add_action( 'admin_menu', 'my_admin_menu_2' );
function my_admin_menu_2() {
		add_menu_page(
			__( 'Cenniki Portale', 'my-textdomain' ),
			__( 'Cenniki Portale', 'my-textdomain' ),
			'edit_pages',
			'cennik-portale',
			'cennik_portale_page_contents',
			'dashicons-schedule',
			3
		);
	}

function cennik_portale_page_contents() {
	
	global $woocommerce_vg;

	acf_form_head();
	
	/* Pobrać nazwy gazet i wyświetlić jako nazwy */
	$tabs = [8727,8744,8746,8748,8750,8752,8754,8756,8758,8760];
	
	foreach ($tabs as $tab) {
		$portal_names[] = get_field('field_5fbeade42d6f8',$tab);
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
		.acf-field-group, .acf-field-message {
			display: inline-block;
			vertical-align: top;
		}
		.acf-field-5fc16535ee671 {
			width: 97% !important;
		}
		.rabat_portal .acf-input {
			width: 20%;
		}

	</style>
	
	<div class="wrap">
	
		<nav class="nav-tab-wrapper">

			<?php 
			foreach ($portal_names as $key => $name) {
				$empty = $key+1;
			;?>
					<div class="nav-tab" id="nav-tab-<?php echo $key+1;?>"><?php if ($name) {echo $name;} else {echo 'Cennik '.$empty;};?></div>
			<?php
				}
			;?>
		
		</nav>
		
		<?php 
		
			if (	isset($_GET['del'])	&& check_admin_referer( 'del_cennik_p' )	) {

				/* Pobierz Idki local i VG */
				$ids_local = json_decode(get_field('field_5fbfbace2e54f',$_GET['del']),true);
				$ids_vg = json_decode(get_field('field_5fbfbac52e54e',$_GET['del']),true);
								
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
					
					/* Usuń z Mappingu */
					$vg_vari_ids_portalu = json_decode(get_field('field_5fc147440faf3',8762),true);
					unset($vg_vari_ids_portalu[$_GET['del']]);
					update_field('field_5fc147440faf3',json_encode($vg_vari_ids_portalu),8762);
						
					/* Usuń z formy */				
					foreach (get_fields($_GET['del']) as $field_name => $field_value) {
						update_field($field_name, '', $_GET['del']);
					}
				
				} else {
					echo "Błąd - ten cennik nie istnieje.";
				}
				
				header("Location: ".home_url()."/wp-admin/admin.php?page=cennik-portale");
				
			}

		
		foreach ($tabs as $key => $post_id) { $num = $key+1; ;?>	
			
			<div class="tab-content <?php if ($key==0) {echo 'default-tab';};?>" cennik="nav-tab-<?php echo $num;?>">	
			
				<h1>Cennik nr <?php echo $num;?></h1>
				<h2>Dane Portalu nr <?php echo $num;?></h2>
				
				<div class="cennik_form cennik_<?php echo $num;?>" >
					<?php 
						$options = array(
							'post_id' => $post_id,
							'field_groups' => array(12125),
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
					<a href="<?php echo wp_nonce_url( home_url().'/wp-admin/admin.php?page=cennik-portale&del='.$post_id, 'del_cennik_p' );?>" style="color: red;">Usuń Cennik</a>
				</div>
				<?php };?>
			
			</div>
			
		<?php } ;?>

	</div>

	<?php
}

;?>