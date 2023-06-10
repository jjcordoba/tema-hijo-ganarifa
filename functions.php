<?php



add_filter( 'woocommerce_account_menu_items', 'ayudawp_ocultar_direccion', 999 );

 

function ayudawp_ocultar_direccion( $items ) {

unset($items['downloads']);

return $items;

}



/* Cancelar automaticamente pedidos pendientes tras 1 hora */

add_action( 'woocommerce_order_status_pending', 'cancelped_cancelar_pedidos_pendientes' );

function cancelped_cancelar_pedidos_pendientes( $order_id ) {

if ( ! wp_next_scheduled( 'cancelped_cancelar_pedidos_pendientes_una_hora', array( $order_id ) ) ) {

wp_schedule_single_event( time() + 3600, 'cancelped_cancelar_pedidos_pendientes_una_hora', array( $order_id ) );

}

}

add_action( 'cancelped_cancelar_pedidos_pendientes_una_hora', 'bbloomer_cancel_order' );

function bbloomer_cancel_order( $order_id ) {

$order = wc_get_order( $order_id );

wp_clear_scheduled_hook( 'cancelped_cancelar_pedidos_pendientes_una_hora', array( $order_id ) );

if ( $order->has_status( array( 'pending' ) ) ) { 

$order->update_status( 'cancelled', 'Pedido pendiente cancelado tras 1 hora' );

}

}

/* Distinto boton de pago segun pasarela elegida */

add_filter( 'woocommerce_available_payment_gateways', 'ayudawp_renombrar_boton_pago' );

function ayudawp_renombrar_boton_pago( $gateways ) {

if ( $gateways['cod'] ) {

$gateways['cod']->order_button_text = 'Confirmar contra reembolso';

} elseif ( $gateways['bacs'] ) {

$gateways['bacs']->order_button_text = 'Ver datos bancarios';

} elseif ( $gateways['stripe'] ) {

$gateways['stripe']->order_button_text = 'Pago Seguro';

} 

return $gateways;

}



/* Guardar y mostrar aceptación de términos y condiciones */

// 1. Guardamos la aceptación en los datos meta del pedido

add_action( 'woocommerce_checkout_update_order_meta', 'ayudawp_guardar_aceptacion_tos' );

function ayudawp_guardar_aceptacion_tos( $order_id ) {

if ( $_POST['terms'] ) update_post_meta( $order_id, 'terms', esc_attr( $_POST['terms'] ) );

}

// 2. Mostramos la aceptación en la edición del pedido

add_action( 'woocommerce_admin_order_data_after_billing_address', 'ayudawp_mostrar_aceptacion_tos' );

function ayudawp_mostrar_aceptacion_tos( $order ) {

if ( get_post_meta( $order->get_id(), 'terms', true ) == 'on' ) {

echo '<p><strong>Terminos y condiciones: </strong>Aceptados</p>';

} else echo '<p><strong>Terminos y condiciones: </strong>N/D</p>';

}





/* Guardar campos de finalizar compra */

add_action( 'woocommerce_checkout_update_order_review', 'ayudawp_guarda_campos_pago', 9999 );

function ayudawp_guarda_campos_pago( $posted_data ) {

parse_str( $posted_data, $output );

WC()->session->set( 'checkout_data', $output );

return $posted_data;

}

add_filter( 'woocommerce_checkout_get_value', 'ayudawp_recupera_campos_pago', 9999, 2 );

function ayudawp_recupera_campos_pago( $value, $index ) {

$data = WC()->session->get( 'checkout_data' );

if ( ! $data || empty( $data[$index] ) ) return $value;

return is_bool( $data[$index] ) ? (int) $data[$index] : $data[$index];

}

add_filter( 'woocommerce_ship_to_different_address_checked', 'ayudawp_recupera_campos_pago_envio' );

function ayudawp_recupera_campos_pago_envio( $checked ) {

$data = WC()->session->get( 'checkout_data' );

if ( ! $data || empty( $data['ship_to_different_address'] ) ) return $checked;

return true;

}


function ocultar_seccion_fecha_actual() {
    $fecha_actual = current_time('Y-m-d h:i A'); // Obtiene la fecha y hora actual en formato 'YYYY-MM-DD hh:mm AM/PM'
    $producto_id = get_the_ID(); // Obtiene el ID del producto actual

    // Obtén la fecha y hora del campo ACF usando la clave 'field_62534f3b7fea1'
    $fecha_producto = get_field('field_62534f3b7fea1', $producto_id);
    $fecha_producto = date('Y-m-d h:i A', strtotime($fecha_producto)); // Convierte la fecha del campo ACF a formato 'YYYY-MM-DD hh:mm AM/PM'

    if (strtotime($fecha_producto) <= strtotime($fecha_actual)) {
        echo '<style>.ocultar-seccion { display: none; }</style>';
    }
}
add_action('wp_head', 'ocultar_seccion_fecha_actual');

function mostrar_seccion_ganadores_fecha_actual() {
    $fecha_actual = current_time('Y-m-d h:i A'); // Obtiene la fecha y hora actual en formato 'YYYY-MM-DD hh:mm AM/PM'
    $producto_id = get_the_ID(); // Obtiene el ID del producto actual

    // Obtén la fecha y hora del campo ACF usando la clave 'field_62534f3b7fea1'
    $fecha_producto = get_field('field_62534f3b7fea1', $producto_id);
    $fecha_producto = date('Y-m-d h:i A', strtotime($fecha_producto)); // Convierte la fecha del campo ACF a formato 'YYYY-MM-DD hh:mm AM/PM'

    if (strtotime($fecha_actual) >= strtotime($fecha_producto)) {
        echo '<style>.ganadores { display: block; }</style>';
    }
}
add_action('wp_head', 'mostrar_seccion_ganadores_fecha_actual');



