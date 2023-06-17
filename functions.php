<?php

include_once 'template-parts/funtions.php';
include_once 'peronalizacion/login.php';



function verificar_numero_tickets( $order_id, $numero_ticket ) {
    global $wpdb;
    $order_itemmeta_table = $wpdb->prefix . 'woocommerce_order_itemmeta'; // Get the order item meta table name

    // Query to check if the ticket number already exists for the given order ID
    $results = $wpdb->get_results( $wpdb->prepare( "
        SELECT COUNT(*) as ticket_count FROM $order_itemmeta_table
        WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s AND order_item_id IN (
            SELECT order_item_id FROM $wpdb->prefix . 'woocommerce_order_items'
            WHERE order_id = %d
        )
    ", $numero_ticket, $order_id ) );

    // Get the ticket count from the query results
    $ticket_count = isset( $results[0]->ticket_count ) ? $results[0]->ticket_count : 0;

    if ( $ticket_count > 0 ) {
        // Display an error message if the ticket number already exists
        wc_add_notice( 'The ticket number ' . $numero_ticket . ' is not available. Please choose another number to complete the payment process.', 'error' );

        // Stop the payment process
        return false;
    }

    // If the ticket number does not exist in the table, allow the payment process
    return true;
}

// Hook to verify the ticket number before finalizing the payment process
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets', 10, 2 );
agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION q';

    echo '<p>' . $texto_actualizacion . '</p>';
}