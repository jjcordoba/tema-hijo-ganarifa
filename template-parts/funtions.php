<?php 
function verificar_numero_tickets( $data, $errors ) {
    $numero_ticket = $data['billing_cotasescolhidas'];

    if ( ! empty( $numero_ticket ) ) {
        global $wpdb;

        // Verificar si el número de ticket ya existe en otro pedido
        $existing_ticket = $wpdb->get_var( $wpdb->prepare( "
            SELECT meta_value FROM $wpdb->woocommerce_order_itemmeta
            WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
        ", $numero_ticket ) );

        if ( $existing_ticket ) {
            $errors->add( 'ticket_number', 'El número ' . $numero_ticket . ' no está disponible. Por favor, elige otro número para completar el proceso de pago.' );
        }
    }
}
add_action( 'woocommerce_after_checkout_validation', 'verificar_numero_tickets', 10, 2 );



function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION 8';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
