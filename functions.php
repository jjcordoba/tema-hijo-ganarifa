<?php

include_once 'template-parts/funtions.php';
include_once 'peronalizacion/login.php';

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

add_action('woocommerce_after_checkout_validation', 'verificar_tickets_repetidos', 10, 2);

function verificar_tickets_repetidos($data, $errors) {
    if (isset($_POST['woocommerce_checkout_place_order'])) {
        $numeroTickets = $_POST['billing_cotasescolhidas']; // Obtener el número de tickets del formulario de pago

        // Verificar si el número de tickets está repetido
        if (numeroTicketsRepetidos($numeroTickets)) {
            // Mostrar mensaje de número de tickets no disponible
            $mensaje = 'El número de ticket elegido (' . $numeroTickets . ') ya ha sido comprado y no está disponible.';
            $errors->add('validation', $mensaje);

            // Impedir finalizar la compra
            wc_add_notice($mensaje, 'error');
        }
    }
}

function numeroTicketsRepetidos($numeroTickets) {
    global $wpdb;

    $tablaMeta = $wpdb->prefix . 'woocommerce_order_itemmeta';
    $campoTickets = 'billing_cotasescolhidas';

    // Consulta personalizada para verificar si el número de tickets ya existe en los pedidos
    $consulta = $wpdb->prepare("
        SELECT COUNT(*)
        FROM $tablaMeta
        WHERE meta_key = %s
        AND meta_value = %s",
        $campoTickets,
        $numeroTickets
    );
    $resultado = $wpdb->get_var($consulta);

    // Comprueba si el número de tickets ya existe
    if ($resultado > 0) {
        return true; // El número de tickets está repetido
    }

    return false; // El número de tickets no está repetido
}
