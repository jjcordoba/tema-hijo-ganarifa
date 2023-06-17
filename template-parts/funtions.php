<?php 
function verificar_numero_tickets($order_id, $numero_ticket) {
    global $wpdb;

    // Obtener el ID del meta_key "billing_cotasescolhidas" en la tabla "woocommerce_order_itemmeta"
    $meta_key_id = $wpdb->get_var( $wpdb->prepare( "
        SELECT meta_id FROM $wpdb->woocommerce_order_itemmeta
        WHERE meta_key = 'billing_cotasescolhidas' AND order_item_id = %d
    ", $order_id ) );

    // Verificar si se encontró un meta_key correspondiente
    if ( $meta_key_id ) {
        // Obtener el meta_value del meta_key encontrado
        $meta_value = $wpdb->get_var( $wpdb->prepare( "
            SELECT meta_value FROM $wpdb->woocommerce_order_itemmeta
            WHERE meta_id = %d
        ", $meta_key_id ) );

        // Verificar si el número de ticket ya existe en el meta_value
        if ( $meta_value == $numero_ticket ) {
            // Obtener el ID del producto en el pedido
            $product_id = $wpdb->get_var( $wpdb->prepare( "
                SELECT product_id FROM $wpdb->woocommerce_order_items
                WHERE order_id = %d
            ", $order_id ) );

            // Obtener el nombre del producto
            $product_name = get_the_title( $product_id );

            // Mostrar mensaje de número no disponible con el número elegido y detener el proceso de pago
            wc_add_notice( 'El número ' . $numero_ticket . ' no está disponible para el producto "' . $product_name . '". Por favor, elige otro número para completar el proceso de pago.', 'error' );
            return false;
        }
    }

    // Si el número de ticket no existe en la tabla, permitir el proceso de pago
    return true;
}

// Hook para verificar el número de tickets antes de finalizar el proceso de pago
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets', 10, 2 );



function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION 5';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
