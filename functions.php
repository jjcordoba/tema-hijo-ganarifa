<?php

include_once 'template-parts/functions.php';
include_once 'personalizacion/login.php';

function verificar_numero_tickets( $order_id, $numero_ticket ) {
    global $wpdb;
    $order_itemmeta_table = $wpdb->prefix . 'woocommerce_order_itemmeta'; // Obtener el nombre de la tabla de metadatos del artículo del pedido

    // Consulta para verificar si el número de ticket ya existe para el ID de pedido dado
    $results = $wpdb->get_results( $wpdb->prepare( "
        SELECT COUNT(*) as ticket_count FROM $order_itemmeta_table
        WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s AND order_item_id IN (
            SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_items
            WHERE order_id = %d
        )
    ", $numero_ticket, $order_id ) );

    // Obtener el recuento de tickets de la consulta
    $ticket_count = isset( $results[0]->ticket_count ) ? $results[0]->ticket_count : 0;

    if ( $ticket_count > 0 ) {
        // Mostrar un mensaje de error si el número de ticket ya existe
        wc_add_notice( 'El número de ticket ' . $numero_ticket . ' no está disponible. Por favor, elige otro número para completar el proceso de pago.', 'error' );

        // Detener el proceso de pago
        return false;
    }

    // Si el número de ticket no existe en la tabla, permitir el proceso de pago
    return true;
}

// Gancho para verificar el número de ticket antes de finalizar el proceso de pago
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets', 10, 2 );

function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERSIÓN q';

    echo '<p>' . $texto_actualizacion . '</p>';
}
