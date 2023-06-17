<?php 
// Función para verificar si el número de tickets ya está en uso
function verificar_numero_tickets($numero_tickets) {
    // Consulta a la base de datos de WordPress
    global $wpdb;
    
    // Nombre de la tabla de pedidos
    $tabla_pedidos = $wpdb->prefix . 'woocommerce_order_items';
    
    // Consulta para verificar si el número de tickets ya existe en la tabla de pedidos
    $consulta = $wpdb->prepare("
        SELECT order_item_id
        FROM $tabla_pedidos
        WHERE order_item_type = 'line_item'
        AND order_item_name = 'billing_cotasescolhidas'
        AND order_item_meta_value = %s
    ", $numero_tickets);
    
    // Ejecutar la consulta
    $resultado = $wpdb->get_var($consulta);
    
    // Si se encontró un pedido con el número de tickets, mostrar un mensaje de error
    if ($resultado) {
        $mensaje = 'El número de tickets elegido (' . $numero_tickets . ') ya ha sido comprado y no está disponible.';
        wc_add_notice($mensaje, 'error');
        // Redirigir al carrito o a la página de pago (ajustar según tu configuración)
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}

// Hook para ejecutar la función antes de procesar el pedido
add_action('woocommerce_checkout_process', 'verificar_numero_tickets');


// Hook para verificar el número de tickets antes de procesar el pago
add_action('woocommerce_checkout_process', 'verificar_numero_tickets');

function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION 3';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
