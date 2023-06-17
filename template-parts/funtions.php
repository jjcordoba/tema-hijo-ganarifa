<?php 
function verificar_numero_tickets() {
    // Obtener el número de ticket del campo de formulario
    $numero_ticket = $_POST['billing_cotasescolhidas'];

    // Verificar si se proporcionó un número de ticket
    if ( ! empty( $numero_ticket ) ) {
        global $wpdb;

        // Consultar la tabla woocommerce_order_itemmeta para verificar si el número de ticket ya existe en otro pedido
        $existing_ticket = $wpdb->get_var( $wpdb->prepare( "
            SELECT meta_value FROM $wpdb->woocommerce_order_itemmeta
            WHERE meta_key = 'billing_cotasescolhidas' AND meta_value = %s
        ", $numero_ticket ) );

        // Verificar si el número de ticket ya existe en otro pedido
        if ( $existing_ticket ) {
            $product_id = $_POST['add-to-cart'];

            // Obtener el nombre del producto
            $product_name = get_the_title( $product_id );

            // Mostrar mensaje de error con el número de ticket y el nombre del producto
            wc_add_notice( 'El número ' . $numero_ticket . ' no está disponible para el producto "' . $product_name . '". Por favor, elige otro número para completar el proceso de pago.', 'error' );
        }
    }
}
add_action( 'woocommerce_checkout_process', 'verificar_numero_tickets' );




function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION 6';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
