<?php
error_reporting(0);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Shipping_FastMail class.
 *
 * @extends WC_Shipping_Method
 */
class WC_Shipping_FastMail extends WC_Shipping_Method {
	private $default_boxes;
	private $found_rates;

	/**
	 * Constructor
	 */
	public function __construct( $instance_id = 0 ) {
		
		$this->id                   = 'fastmail_wanderlust';
		$this->instance_id 			 		= absint( $instance_id );
		$this->method_title         = __( 'Fast Mail Logistica', 'woocommerce-shipping-fastmail' );
 		$this->method_description   = __( 'Obtain shipping rates dynamically via the Fast Mail API for your orders.', 'woocommerce' );
		$this->default_boxes 				= include( 'data/data-box-sizes.php' );
		$this->supports             = array(
			'shipping-zones',
			'instance-settings',
		);

		$this->init();
		
 		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	/**
	 * init function.
	 */
	public function init() {
		// Load the settings.
		$this->init_form_fields = include( 'data/data-settings.php' );
		$this->init_settings();
		$this->instance_form_fields = include( 'data/data-settings.php' );
	 
		// Define user set variables
		$this->title           = $this->get_option( 'title', $this->method_title );
		$this->enviroment      = $this->get_option( 'enviroment' );
		$this->origin_idfranjahoraria = $this->get_option( 'origin_idfranjahoraria' );
		$this->origin_observaciones	 = $this->get_option( 'origin_observaciones' );
		$this->sucursal_origen				 = $this->get_option( 'sucursal_origen' );		
		$this->api_user				 = $this->get_option( 'api_user' );
		$this->api_password		 = $this->get_option( 'api_password' );
		$this->ajuste_precio   = $this->get_option( 'ajuste_precio' );
		$this->tipo_servicio   = $this->get_option( 'tipo_servicio' );
		$this->debug           = ( $bool = $this->get_option( 'debug' ) ) && $bool == 'yes' ? true : false;
 		$this->services        = $this->get_option( 'services', array( ));
		$this->mercado_pago    = ( $bool = $this->get_option( 'mercado_pago' ) ) && $bool == 'yes' ? true : false;
		$this->redondear_total = ( $bool = $this->get_option( 'redondear_total' ) ) && $bool == 'yes' ? true : false;
	}

	/**
	 * Output a message
	 */
	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug ) {
			wc_add_notice( $message, $type );
		}
	}

	/**
	 * environment_check function.
	 */
	private function environment_check() {
		if ( ! in_array( WC()->countries->get_base_country(), array( 'AR' ) ) ) {
			echo '<div class="error">
				<p>' . __( 'Argentina tiene que ser el pais de Origen.', 'woocommerce-shipping-fastmail' ) . '</p>
			</div>';
		} 
	}

	/**
	 * admin_options function.
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();

		// Show settings
		parent::admin_options();
	}


	/**
	 * generate_box_packing_html function.
	*/
	public function generate_service_html() {
		ob_start();
		include( 'data/services.php' );
		return ob_get_clean();
	}

	
	/**
	 * validate_box_packing_field function.
	 *
	 * @param mixed $key
	*/
		public function validate_service_field( $key ) {
						
 		$service_name     = isset( $_POST['service_name'] ) ? $_POST['service_name'] : array();
		$service_operativa     = isset( $_POST['service_operativa'] ) ? $_POST['service_operativa'] : array();
		$service_enabled    = isset( $_POST['service_enabled'] ) ? $_POST['service_enabled'] : array();
			  	
		$services = array();

		if ( ! empty( $service_operativa ) && sizeof( $service_operativa ) > 0 ) {
			for ( $i = 0; $i <= max( array_keys( $service_operativa ) ); $i ++ ) {

				if ( ! isset( $service_operativa[ $i ] ) )
					continue;
		
				if ( $service_operativa[ $i ] ) {
  					$services[] = array(
 						'service_name'     =>  $service_name[ $i ],
						'operativa'     => $service_operativa[ $i ] ,
						'enabled'    => isset( $service_enabled[ $i ] ) ? true : false
					);
				}
			}
 
		}
			
		return $services;
	}

	/**
	 * Get packages - divide the WC package into packages/parcels suitable for a OCA quote
	 */
	public function get_fastmail_packages( $package ) {
				return $this->per_item_shipping( $package );
	}

	/**
	 * per_item_shipping function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return array
	 */
	private function per_item_shipping( $package ) {
		$to_ship  = array();
		$group_id = 1;

		// Get weight of order
		foreach ( $package['contents'] as $item_id => $values ) {

			if ( ! $values['data']->needs_shipping() ) {
				$this->debug( sprintf( __( 'Product # is virtual. Skipping.', 'woocommerce-shipping-fastmail' ), $item_id ), 'error' );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( __( 'Product # is missing weight. Aborting.', 'woocommerce-shipping-fastmail' ), $item_id ), 'error' );
				return;
			}

			$group = array();

			$group = array(
				'GroupNumber'       => $group_id,
				'GroupPackageCount' => $values['quantity'],
				'Weight' => array(
					'Value' => $values['data']->get_weight(),
					'Units' => 'KG'
				),
				'packed_products' => array( $values['data'] )
			);

			if ( $values['data']->get_length() && $values['data']->get_height() && $values['data']->get_width() ) {

				$dimensions = array( $values['data']->get_length(), $values['data']->get_width(), $values['data']->get_height() );

				sort( $dimensions );

				$group['Dimensions'] = array(
					'Length' => $values['data']->get_length(),
					'Width'  => $values['data']->get_width(),
					'Height' => $values['data']->get_height(),
					'Units'  => 'CM'
				);
			}

			$group['InsuredValue'] = array(
				'Amount'   => round( $values['data']->get_price() ),
				'Currency' => get_woocommerce_currency()
			);

			$to_ship[] = $group;

			$group_id++;
		}

		return $to_ship;
	}



	/**
	 * calculate_shipping function.
	 *
	 * @param mixed $package
	 */
	public function calculate_shipping( $package = array() ) {
		global $wp_session;
		// Debugging
		$this->debug( __( 'Fast Mail modo de depuración está activado - para ocultar estos mensajes, desactive el modo de depuración en los ajustes.', 'woocommerce-shipping-fastmail' ) );		
		
		// Get requests
		$fastmail_packages   = $this->get_fastmail_packages( $package );
				
		// Ensure rates were found for all packages
		$packages_to_quote_count = sizeof( $fastmail_requests );
 		
		$fastmail_package = $fastmail_packages[0]['GroupPackageCount'];	
		
		$dimension_unit = esc_attr( get_option('woocommerce_dimension_unit' ));
 		$weight_unit = esc_attr( get_option('woocommerce_weight_unit' ));
 		$weight_multi = 0;
		$dimension_multi = 0;
 		if ($dimension_unit == 'm') { $dimension_multi =  1;}
 		if ($dimension_unit == 'cm') {  $dimension_multi =  100;}
 		if ($dimension_unit == 'mm') { $dimension_multi =  1000;}
 		if ($weight_unit == 'kg') { $weight_multi =  1;}
 		if ($weight_unit == 'g') {  $weight_multi =  0.001;}
						
		foreach ($fastmail_packages as $key) {
			$fastmail_package = $key['GroupPackageCount'];
	 		$fastmail_weight = $key['Weight']['Value'] * $weight_multi;
			$fastmail_lenth = $key['Dimensions']['Length'] / $dimension_multi;
			$fastmail_width = $key['Dimensions']['Width'] / $dimension_multi;		
			$fastmail_height = $key['Dimensions']['Height'] / $dimension_multi;	
			$fastmail_amount = $key['InsuredValue']['Amount'];	
			$fastmail_weightb += $fastmail_weight * $fastmail_package;
 			$fastmail_volume = $fastmail_lenth * $fastmail_width * $fastmail_height;
			$fastmail_volumesy += $fastmail_volume * $fastmail_package;
			$fastmail_volumesy = number_format($fastmail_volumesy, 10);	
			$fastmail_packageb = 1;	
		}
		
		$origen_datos[] = array (
			'origin_idfranjahoraria' => $this->origin_idfranjahoraria,
			'origin_observaciones' => $this->origin_observaciones,
			'sucursal_origen' => $this->sucursal_origen,
			'enviroment' => $this->enviroment,
			'api_user' => $this->api_user,
			'api_password' => $this->api_password,
			'fastmail_lenth' => $fastmail_lenth * 100,
			'fastmail_width' => $fastmail_width * 100,
			'fastmail_height' => $fastmail_height * 100,
			'fastmail_amount' => $fastmail_amount,
			'fastmail_weightb' => $fastmail_weightb,	
 			'fastmail_cantidad' => $fastmail_packageb,	
		);
		$origen_datos = json_encode($origen_datos);
		$wp_session['origen_datos'] = $origen_datos;
					 						 
		$seguro = round($package['contents_cost']);

	 	$mercado_pago = $this->mercado_pago;		
 		if($mercado_pago =='1'){
			add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_lfastmaill_pickup_free_label', 10, 2 );
			function remove_lfastmaill_pickup_free_label($full_label, $method){
				$full_label = str_replace("(Gratis)","",$full_label);
			return $full_label;
			}
			add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_lfastmaill_pickup_free_label_en', 10, 2 );
			function remove_lfastmaill_pickup_free_label_en($full_label, $method){
				$full_label = str_replace("(Free)","",$full_label);
			return $full_label;
			}		
		}
		
		if($this->enviroment == 'yes'){
				$url = 'http://epresis.fastmail.com.ar/sistema/web/api/v1/precios.json';
		} else {
				$url = 'http://epresis.fastmail.com.ar/sistema/web/api/v1/precios.json';
		}
 
					
		foreach($this->services as $services) {
						
			if($services['enabled'] == 1){
								
 						$_query_string_precios = array(
											'username' => $this->api_user,
											'password' => $this->api_password,
											'sucursal' => $this->sucursal_origen,
											'cp' => $package['destination']['postcode'],
											'servicio' => $services['operativa'],
											'productos' => array(
												array(		
													'tipo' => 2,
													'peso' => $fastmail_weightb,
												)),
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
						curl_close($ch);  

						$fastmail_response = json_decode($result);
				
						if($this->ajuste_precio == '0'){
							$ajuste = '1';
						} else if($this->ajuste_precio == '0%'){
							$ajuste = '1';
						} else {
							$ajuste = $this->ajuste_precio;
						}
 				
						$redondear_total = $this->redondear_total;

						$porcentaje = $fastmail_response->precio * $ajuste / 100;

						$precio = $fastmail_response->precio + $porcentaje;
						$precio_base = $fastmail_response->precio + $porcentaje;
				
						$precio_baseb = $fastmail_response->precio * 0.40;
				
						$precio = $precio + $precio_baseb;
					
					
						if($redondear_total=='1'){
							$precio = round($precio, 0, PHP_ROUND_HALF_UP);
						}
				
				
											
				if($mercado_pago =='1'){
 					$precio = number_format($precio, 2);
					$titulo = $services['service_name'] . ' - Gratis' ;
					//$precio = '0';
					$rate = array(
						'id' => sprintf("%s-%s", $titulo, $services['service_name'] . '-' . 'operativa' . $services['operativa'] ),
						'label' => sprintf("%s", $titulo),
						'cost' => 0,
						'calc_tax' => 'per_item'
					);	
		          if($precio!='0'){
		 					  $this->add_rate( $rate );
		          }
				} else {
				  
					$titulo = $services['service_name'];
          
		          if($package['contents_cost'] >= 2000){
		            //$precio = 0;
		          }
					
					$rate = array(
						'id' => sprintf("%s-%s", $titulo, $services['service_name'] . '-' . 'operativa' . $services['operativa'] ),
						'label' => sprintf("%s", $titulo),
						'cost' => $precio,
						'calc_tax' => 'per_item',
						'package' => $package,
					);			
					if($precio!='0'){
						$this->add_rate( $rate );
					}
				}		
			}
			
		}	
 	 
 	}

	/**
	 * sort_rates function.
	 **/
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}