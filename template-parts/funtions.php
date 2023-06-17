<?php
function verificar_numero_tickets() {
    // Obtener el número de tickets seleccionados en el carrito
    $numero_tickets = $_POST['billing_cotasescolhidas'];

    // Obtener todos los pedidos
    $pedidos = wc_get_orders(array(
        'limit' => -1,
        'status' => array('processing', 'completed') // Incluye aquí los estados de pedido que deseas verificar
    ));

    // Recorrer los pedidos y verificar si el número de tickets está presente en cada uno
    foreach ($pedidos as $pedido) {
        // Obtener el número de tickets del pedido actual
        $tickets_pedido = $pedido->get_meta('billing_cotasescolhidas');

        // Verificar si el número de tickets coincide
        if ($tickets_pedido == $numero_tickets) {
            // Mostrar mensaje de error
            $message = 'El número de tickets ' . $numero_tickets . ' ya no está disponible. Por favor, elige otro número de tickets.';
            wc_add_notice($message, 'error');
            return;
        }
    }

    // Si no se encuentra el número de tickets en ningún pedido existente, permitir el procesamiento del pago
    return;
}

// Hook para verificar el número de tickets antes de procesar el pago
add_action('woocommerce_checkout_process', 'verificar_numero_tickets');
