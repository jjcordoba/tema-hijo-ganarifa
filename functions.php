<?php

include_once 'template-parts/functions.php';
include_once 'personalizacion/login.php';

function verificar_numero_tickets( $order_id, $numero_ticket ) {
    global $wpdb;
    $order_itemmeta_table = $wpdb->prefix . 'woocommerce_order_itemmeta'; // Obtener el nombre de la tabla de metadatos de elementos de pedido

    // Consulta para verificar si el número de ticket ya existe para cualquier pedido
    $results = $wpdb->get_results( $wpdb->prepare( "
        SELECT COUNT(*) as ticket_count FROM $order_itemmeta_table
        WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
    ", $numero_ticket ) );

    // Obtener la cantidad de tickets de la consulta
    $ticket_count = isset( $results[0]->ticket_count ) ? $results[0]->ticket_count : 0;

    if ( $ticket_count > 0 ) {
        // Mostrar un mensaje de error si el número de ticket ya existe
        wc_add_notice( 'El número de ticket ' . $numero_ticket . ' ya está en uso. Por favor, elige otro número para completar el proceso de pago.', 'error' );

        // Detener el proceso de pago
        return false;
    }

    // Si el número de ticket no existe en ningún pedido, permitir el proceso de pago
    return true;
}

// Gancho para verificar el número de ticket antes de finalizar el proceso de pago
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets', 10, 2 );


function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERSIÓN q3';
    
    ob_start();
    ?>
    <p><?php echo $texto_actualizacion; ?></p>
    <?php
    $html = ob_get_clean();
    
    echo $html;
}

