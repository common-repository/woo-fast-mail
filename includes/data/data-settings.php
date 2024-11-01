<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Array of settings
 */
return array(
	'enabled'           => array(
		'title'           => __( 'Activar Fast Mail', 'woocommerce-shipping-fastmail' ),
		'type'            => 'checkbox',
		'label'           => __( 'Activar este método de envió', 'woocommerce-shipping-fastmail' ),
		'default'         => 'no'
	),

	'debug'      				=> array(
		'title'           => __( 'Modo Depuración', 'woocommerce-shipping-fastmail' ),
		'label'           => __( 'Activar modo depuración', 'woocommerce-shipping-fastmail' ),
		'type'            => 'checkbox',
		'default'         => 'no',
		'desc_tip'    => true,
		'description'     => __( 'Activar el modo de depuración para mostrar información de depuración en la compra/pago y envío.', 'woocommerce-shipping-fastmail' )
	),

	'title'             => array(
		'title'           => __( 'Título', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( 'Controla el título que el usuario ve durante el pago.', 'woocommerce-shipping-fastmail' ),
		'default'         => __( 'Fast Mail', 'woocommerce-shipping-fastmail' ),
		'desc_tip'        => true
	),
	
 	'enviroment'      				=> array(
		'title'           => __( 'Modo Demo', 'woocommerce-shipping-fastmail' ),
		'label'           => __( 'Activar modo demo', 'woocommerce-shipping-fastmail' ),
		'type'            => 'checkbox',
		'default'         => 'no',
		'desc_tip'    => true,
		'description'     => __( 'Activar el modo de testeo.', 'woocommerce-shipping-fastmail' )
	),
	
	'origin_idfranjahoraria' 	=> array(
		'title'           => __( 'Franja Horaria para retirar paquetes', 'woocommerce-shipping-fastmail' ),
		'type'            => 'select',
		'description'     => __( 'Obligatorio sólo para Orden de Retiro valores posibles: mañana, tarde, corrido', 'woocommerce-shipping-fastmail' ),
		'default'         => '',
		'class'           => 'packing_method',
		'options'         => array(
			'mañana'       => __( 'mañana', 'woocommerce-shipping-fastmail' ),
			'tarde'       => __( 'tarde', 'woocommerce-shipping-fastmail' ),
			'corrido'       => __( 'corrido', 'woocommerce-shipping-fastmail' ),
		),		
		'desc_tip'        => true
    ),		
	
	'origin_observaciones' 	=> array(
		'title'           => __( 'Observaciones', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( '', 'woocommerce-shipping-fastmail' ),
		'default'         => '',
		'desc_tip'        => true
    ),	
 
   'api'              => array(
		'title'           => __( 'Configuración de la API', 'woocommerce-shipping-fastmail' ),
		'type'            => 'title',
		'description'     => __( '', 'woocommerce-shipping-fastmail' ),
    ),
	
   'sucursal_origen'          => array(
		'title'           => __( 'Sucursal Origen', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( 'Sucursal Origen', 'woocommerce-shipping-fastmail' ),
		'default'         => __( '', 'woocommerce-shipping-fastmail' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_user'         => array(
		'title'           => __( 'Usuario en Fast Mail', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( 'Cuenta de mail con la que ingresa a Fast Mail.', 'woocommerce-shipping-fastmail' ),
		'default'         => __( '', 'woocommerce-shipping-fastmail' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'api_password'     => array(
		'title'           => __( 'Password de Fast Mail', 'woocommerce-shipping-fastmail' ),
		'type'            => 'password',
		'description'     => __( 'Password con el que ingresa a Fast Mail.', 'woocommerce-shipping-fastmail' ),
		'default'         => __( '', 'woocommerce-shipping-fastmail' ),
    'placeholder' => __( '', 'meta-box' ),
    ),
	
   'ajuste_precio'    => array(
		'title'           => __( 'Ajustar costos de envío % (porcentual)', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( 'Agregar costo extra al precio. Ingresar valor numérico mayor a 0.', 'woocommerce-shipping-fastmail' ),
		'default'         => __( '', 'woocommerce-shipping-fastmail' ),
    'placeholder' => __( '1', 'meta-box' ),		
    ),	

   'envio_gratis'    => array(
		'title'           => __( 'Envio gratis para montos iguales o superiores a', 'woocommerce-shipping-fastmail' ),
		'type'            => 'text',
		'description'     => __( 'Ingresar valor numérico mayor a 0.', 'woocommerce-shipping-fastmail' ),
		'default'         => __( '', 'woocommerce-shipping-fastmail' ),
    ),	
  
		'mercado_pago'      => array(
				'title'           => __( 'No cobrar el costo de envío', 'woocommerce-shipping-fastmail' ),
				'label'           => __( 'No agregar el costo de envío en el Total (carrito/checkout).', 'woocommerce-shipping-fastmail' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Al activar este modulo, no se agregara el costo de envío en el Total (carrito/checkout).', 'woocommerce-shipping-fastmail' )
		),	
	
 		'redondear_total'      => array(
				'title'           => __( 'Ajustar Totales', 'woocommerce-shipping-fastmail' ),
				'label'           => __( 'Mostrar costos totales sin decimales. Ej: $56.96 a $57', 'woocommerce-shipping-fastmail' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'desc_tip'    => true,
				'description'     => __( 'Mostrar costos totales sin decimales. Ej: $56.96 a $57', 'woocommerce-shipping-fastmail' )
		),	

    'packing'           => array(
		'title'           => __( 'Paquetes y Operativas', 'woocommerce-shipping-fastmail' ),
		'type'            => 'title',
		'description'     => __( 'Los siguientes ajustes determinan cómo los artículos se embalan antes de ser enviado a Fast Mail.', 'woocommerce-shipping-fastmail' ),
    ),

	'packing_method'   => array(
		'title'           => __( 'Método Embalaje', 'woocommerce-shipping-fastmail' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'packing_method',
		'options'         => array(
			'per_item'       => __( 'Por defecto: artículos individuales.', 'woocommerce-shipping-fastmail' ),
		),
	),

 	'services'  => array(
		'type'            => 'service'
	),

);