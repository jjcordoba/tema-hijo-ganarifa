<?php 
function verificar_numero_tickets( $passed, $product_id, $quantity ) {
    $numero_ticket = $_POST['billing_cotasescolhidas'];

    if ( ! empty( $numero_ticket ) ) {
        global $wpdb;

        // Verificar si el número de ticket ya existe en otro pedido
        $existing_ticket = $wpdb->get_var( $wpdb->prepare( "
            SELECT COUNT(*) FROM $wpdb->woocommerce_order_itemmeta
            WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
        ", $numero_ticket ) );

        if ( $existing_ticket > 0 ) {
            wc_add_notice( 'El número ' . $numero_ticket . ' no está disponible. Por favor, elige otro número para completar el proceso de pago.', 'error' );
            $passed = false;
        }
    }

    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'verificar_numero_tickets', 10, 3 );

function verificar_numero_tickets_checkout() {
    $numero_ticket = $_POST['billing_cotasescolhidas'];

    if ( ! empty( $numero_ticket ) ) {
        global $wpdb;

        // Verificar si el número de ticket ya existe en otro pedido
        $existing_ticket = $wpdb->get_var( $wpdb->prepare( "
            SELECT COUNT(*) FROM $wpdb->woocommerce_order_itemmeta
            WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
        ", $numero_ticket ) );

        if ( $existing_ticket > 0 ) {
            wc_add_notice( 'El número ' . $numero_ticket . ' no está disponible. Por favor, elige otro número para completar el proceso de pago.', 'error' );
            wc_clear_notices();
            return;
        }
    }
}
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets_checkout' );



function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION a';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');





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