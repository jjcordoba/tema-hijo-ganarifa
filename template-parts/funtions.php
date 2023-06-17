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
