<?php

include_once 'template-parts/functions.php';
include_once 'personalizacion/login.php';

function verificar_numero_tickets( $passed, $order ) {
    $numero_ticket = ''; // Aquí debes obtener el número de ticket del pedido actual

    if ( ! empty( $numero_ticket ) ) {
        // Verificar si el número de ticket ya existe en la tabla woocommerce_order_itemmeta
        $existing_orders = $wpdb->get_col( $wpdb->prepare( "
            SELECT post_id
            FROM $wpdb->postmeta
            WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
        ", $numero_ticket ) );

        if ( ! empty( $existing_orders ) ) {
            // Mostrar un mensaje de error si el número de ticket ya existe
            wc_add_notice( 'El número de ticket ' . $numero_ticket . ' no está disponible. Por favor, elige otro número para completar el proceso de pago.', 'error' );

            // Detener el proceso de pago
            return false;
        }
    }

    // Si el número de ticket no existe, permitir el proceso de pago
    return $passed;
}

// Gancho para verificar el número de ticket antes de finalizar el proceso de pago
add_filter( 'woocommerce_order_process_checkout', 'verificar_numero_tickets', 10, 2 );



function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'error pago duplicado';
    
    ob_start();
    ?>
    <p><?php echo $texto_actualizacion; ?></p>
    <?php
    $html = ob_get_clean();
    
    echo $html;
}
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
