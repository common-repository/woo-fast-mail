<?php
	global $wp_session;

  add_action( 'wp_footer', 'only_numbers_fastmails');
	function only_numbers_fastmails(){
		if ( is_checkout() ) { ?>
 			<script type="text/javascript">
 				jQuery(document).ready(function () {
        jQuery('#order_sucursal_main').insertAfter( jQuery( '.woocommerce-checkout-review-order-table' ) );
				jQuery('#calc_shipping_postcode').attr({ maxLength : 4 });
				jQuery('#billing_postcode').attr({ maxLength : 4 });
				jQuery('#shipping_postcode').attr({ maxLength : 4 });

		          jQuery("#calc_shipping_postcode").keypress(function (e) {
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          	return false;
		          }
		          });
		          jQuery("#billing_postcode").keypress(function (e) {
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          return false;
		          }
		          });
		          jQuery("#shipping_postcode").keypress(function (e) {
		          if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		          return false;
		          }
		          });


						jQuery('#billing_postcode').focusout(function () {
				    	if (jQuery('#ship-to-different-address-checkbox').is(':checked')) {
				    		var state = jQuery('#shipping_state').val();
				    		var post_code = jQuery('#shipping_postcode').val();
				    	} else {
				    		var state = jQuery('#billing_postcode').val();
				    		var post_code = jQuery('#billing_postcode').val();
				    	}


 							var selectedMethod = jQuery('input:checked', '#shipping_method').attr('id');
							var selectedMethodb = jQuery( "#order_review .shipping .shipping_method option:selected" ).val();
							if (selectedMethod == null) {
									if(selectedMethodb != null){
										selectedMethod = selectedMethodb;
									} else {
										return false;
									}
							}
							var order_sucursal = 'ok';
							var operativa = selectedMethod.substr(selectedMethod.indexOf("operativa") + 9)
							var cuit = selectedMethod.substr(selectedMethod.indexOf("cuit") + 4)
     					var cuit_ok = cuit.substr(0, 13);
 							if (selectedMethod.indexOf("-sas") >= 0 || selectedMethod.indexOf("-sasp") >= 0 || selectedMethod.indexOf("-pasp") >= 0 || selectedMethod.indexOf("-pas") >= 0) {
							jQuery("#order_sucursal_main_result").fadeOut(100);
							jQuery("#order_sucursal_main_result_cargando").fadeIn(100);
				    	jQuery.ajax({
				    		type: 'POST',
				    		cache: false,
				    		url: wc_checkout_params.ajax_url,
				    		data: {
 									action: 'check_sucursales',
									post_code: post_code,
									order_sucursal: order_sucursal,
									operativa: operativa,
									cuit: cuit_ok,
				    		},
				    		success: function(data, textStatus, XMLHttpRequest){
											jQuery("#order_sucursal_main_result").fadeIn(100);
 											jQuery("#order_sucursal_main_result_cargando").fadeOut(100);
											jQuery("#order_sucursal_main_result").html('');
											jQuery("#order_sucursal_main_result").append(data);

 											var selectList = jQuery('#pv_centro_fastmail_estandar option');
											var arr = selectList.map(function(_, o) { return { t: jQuery(o).text(), v: o.value }; }).get();
											arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
											selectList.each(function(i, o) {
												o.value = arr[i].v;
												jQuery(o).text(arr[i].t);
											});
											jQuery('#pv_centro_fastmail_estandar').html(selectList);
											jQuery("#pv_centro_fastmail_estandar").prepend("<option value='0' selected='selected'>Sucursales Disponibles</option>");

										},
										error: function(MLHttpRequest, textStatus, errorThrown){alert(errorThrown);}
									});
				    	return false;
							}
				    });

				});

				function toggleCustomBox() {
 				        var selectedMethod = jQuery('input:checked', '#shipping_method').attr('id');
								var selectedMethodb = jQuery( "#order_review .shipping .shipping_method option:selected" ).val();
								if (selectedMethod == null) {
									if(selectedMethodb != null){
										selectedMethod = selectedMethodb;
									} else {
										return false;
									}
								}
									//sas, sasp, pasp, pas
                if (selectedMethod.indexOf("-sas") >= 0 || selectedMethod.indexOf("-sasp") >= 0 || selectedMethod.indexOf("-pasp") >= 0 || selectedMethod.indexOf("-pas") >= 0) {

                  jQuery('#order_sucursal_main').show();
									jQuery('#order_sucursal_main').insertAfter( jQuery('.shop_table') );

									if (jQuery('#ship-to-different-address-checkbox').is(':checked')) {
										var state = jQuery('#shipping_state').val();
										var post_code = jQuery('#shipping_postcode').val();
									} else {
										var state = jQuery('#billing_postcode').val();
										var post_code = jQuery('#billing_postcode').val();
									}

									var order_sucursal = 'ok';
									var operativa = selectedMethod.substr(selectedMethod.indexOf("operativa") + 9)
									var cuit = selectedMethod.substr(selectedMethod.indexOf("cuit") + 4)
     							var cuit_ok = cuit.substr(0, 13);
									jQuery("#order_sucursal_main_result").fadeOut(100);
									jQuery("#order_sucursal_main_result_cargando").fadeIn(100);
									jQuery.ajax({
										type: 'POST',
										cache: false,
										url: wc_checkout_params.ajax_url,
										data: {
											action: 'check_sucursales',
											post_code: post_code,
											order_sucursal: order_sucursal,
											operativa: operativa,
											cuit: cuit_ok,
										},
										success: function(data, textStatus, XMLHttpRequest){
													jQuery("#order_sucursal_main_result").fadeIn(100);
													jQuery("#order_sucursal_main_result_cargando").fadeOut(100);
													jQuery("#order_sucursal_main_result").html('');
													jQuery("#order_sucursal_main_result").append(data);

	 											var selectList = jQuery('#pv_centro_fastmail_estandar option');
												var arr = selectList.map(function(_, o) { return { t: jQuery(o).text(), v: o.value }; }).get();
												arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
												selectList.each(function(i, o) {
													o.value = arr[i].v;
													jQuery(o).text(arr[i].t);
												});
												jQuery('#pv_centro_fastmail_estandar').html(selectList);
												jQuery("#pv_centro_fastmail_estandar").prepend("<option value='0' selected='selected'>Sucursales Disponibles</option>");

												},
												error: function(MLHttpRequest, textStatus, errorThrown){alert(errorThrown);}
											});
									return false;

                } else {
                  jQuery('#order_sucursal_main').hide();
                }
				}; //ends toggleCustomBox

				jQuery(document).ready(toggleCustomBox);
				jQuery(document).on('change', '#shipping_method input:radio', toggleCustomBox);
 				jQuery(document).on('change', '#order_review .shipping .shipping_method', toggleCustomBox);


			</script>

			<style type="text/css">
         #order_sucursal_main h3 {
            text-align: left;
            padding: 5px 0 5px 115px;
        }
				.fastmail-logo {
					position: absolute;
    			margin: 0px;
				}
			</style>
		<?php }
	}	//ends only_numbers_fastmails

	/**
		* Process the checkout
	*/
	add_action('woocommerce_checkout_process', 'checkout_field_fastmail_process');
	function checkout_field_fastmail_process() {
			global $woocommerce, $wp_session;

			$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
			$chosen_shipping = $chosen_methods[0];
			$wp_session['chosen_shipping'] = $chosen_shipping;

	}

	/**
	 * Update the order meta with field value
	*/
	add_action( 'woocommerce_checkout_update_order_meta', 'order_sucursal_main_update_order_meta_fastmail', 10);
	function order_sucursal_main_update_order_meta_fastmail( $order_id ) {
		global $wp_session;

 	    if ( ! empty( $_POST['pv_centro_fastmail_estandar'] ) ) {
				update_post_meta( $order_id, 'sucursal_pv_centro_fastmail_estandar', $_POST['pv_centro_fastmail_estandar'] );
	    }

		  $chosen_shipping = json_encode($wp_session['chosen_shipping'] );

			update_post_meta( $order_id, 'origen_datos', $wp_session['origen_datos'] );
			update_post_meta( $order_id, 'chosen_shipping', $chosen_shipping );

 	}

	 /**
	 * Show info at order
	 */
	add_action('add_meta_boxes', 'woocommerce_fastmail_box_add_box');

	function woocommerce_fastmail_box_add_box() {
		add_meta_box( 'woocommerce-fastmail-box', __( 'Fast Mail', 'woocommerce-fastmail' ), 'woocommerce_fastmail_box_create_box_content', 'shop_order', 'side', 'default' );
	}
	function woocommerce_fastmail_box_create_box_content() {
		global $post;
			$site_url = get_site_url();
		  $order = wc_get_order( $post->ID );
			$shipping = $order->get_items( 'shipping' );

			echo '<div class="fastmail-single">';
			echo '<strong>Operativa</strong></br>';
			foreach($shipping as $method){
				echo $method['name'];
			}
			echo '</div>';

		//ETIQUETA
		$fastmail_shipping_label_tracking = get_post_meta($post->ID, '_tracking_number', true);
		$etiqueta = get_post_meta($post->ID, 'etiqueta_fastmail', true);
		$fastmail_estado_ordenretiro = get_post_meta($post->ID, 'fastmail_estado_ordenretiro', true);
		$fastmail_estado_numeroenvio = get_post_meta($post->ID, 'fastmail_estado_numeroenvio', true);

 			if(!empty($fastmail_shipping_label_tracking)){
				echo  '<div style="position: relative; width: 100%; height: 60px;"><a style=" width: 225px;text-align: center;background: #ce1616;color: white;padding: 10px;margin: 10px;float: left;text-decoration: none;" href="'. $etiqueta .'" target="_blank">IMPRIMIR ETIQUETA</a></div>';
			}

			if(!empty($fastmail_shipping_label_tracking)){
 				echo  '<div style="position: relative; width: 100%; height: 60px;" >Nro. Seguimiento: '.$fastmail_estado_numeroenvio.'</div>';
			}


		if (empty($fastmail_estado_numeroenvios)){ ?>

			<style type="text/css">
				#generar-fastmail {
					background: #ce1616;
					color: white;
					width: 100%;
					text-align: center;
					height: 40px;
					padding: 0px;
					line-height: 37px;
					margin-top: 20px;
				}
			</style>

			<div id="generar-fastmail" class="button" data-id="<?php echo $post->ID; ?>">Generar Etiqueta</div>
			<div id="editar-fastmail" style="display:none;" class="button" data-id="<?php echo $post->ID; ?>">Editar Datos</div>

			<div class="fastmail-single-label"> </div>

		<?php } else { ?>



	<?php	}
	}

 
	function fastmail_admin_notice() {
		global $wp_session;

			?>
			<div class="notice error my-acf-notice is-dismissible" >
					<p><?php print_r($wp_session['fastmail_notice'] ); ?></p>
			</div>

			<?php
	}

  add_action('wp_ajax_check_sucursales', 'check_sucursales', 1);
	add_action('wp_ajax_nopriv_check_sucursales', 'check_sucursales', 1);

	function check_sucursales() {
		global $wp_session;

		 	$url = 'http://epresis.fastmail.com.ar/sistema/web/api/v1/servicios.json';

    $delivery_zones = WC_Shipping_Zones::get_zones();
			  foreach($delivery_zones as $zones){
				 	foreach($zones['shipping_methods'] as $methods){
					 	if($methods->id =='fastmail_wanderlust'){
						 	if($methods->enabled == 'yes'){
						 		$sucursal_origen = $methods->instance_settings['sucursal_origen'];
						 		$api_user = $methods->instance_settings['api_user'];
                $api_password = $methods->instance_settings['api_password'];
 						 	}
					 	}
				 	}
			  }


 						$_query_string_precios = array(
											'username' => $api_user,
											'password' => $api_password,
											'sucursal' => $sucursal_origen,
											'cp' => 1200,

 						);

            $data_json = json_encode($_query_string_precios);

						$headers= array('Accept: application/json','Content-Type: application/json');

							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
							$result = curl_exec($ch);
							curl_close($ch);  // Seems like good practice
							$result = json_decode($result);
              foreach($result as $servicio){
                echo '</br><strong>Operativa: </strong>' . $servicio->cod_serv.'</br>';
                echo '<strong>Servicio: </strong>' . $servicio->descripcion.'</br>';
                echo '<strong>Detalle: </strong>' . $servicio->detalle_servicio .'</br></br></br>';
              }


 			die();
		}


?>
