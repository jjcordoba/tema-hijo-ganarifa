<?php

function autocompletar_formulario_tarjeta() {
    ?>
    <script>
    jQuery(function($) {
        // Rellena automáticamente los campos del formulario de tarjeta
        $('#id_del_campo_numero_tarjeta').val('3711 803032 57522');
        $('#id_del_campo_nombre_titular').val('Martines');
        $('#id_del_campo_vencimiento').val('12/25');
        $('#id_del_campo_codigo_seguridad').val('1234');
        $('#id_del_campo_documento_titular').val('41121548');
    });
    </script>
    <?php
}
add_action('woocommerce_after_checkout_form', 'autocompletar_formulario_tarjeta');


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
            // Mostrar mensaje de error con el número de ticket no disponible
            $message = 'El ticket número ' . $numero_tickets . ' ya no está disponible. Por favor, elige otro número de ticket.';
            wc_add_notice($message, 'error');
            return;
        }
    }

    // Si no se encuentra el número de tickets en ningún pedido existente, permitir el procesamiento del pago
    return;
}

// Hook para verificar el número de tickets antes de procesar el pago
add_action('woocommerce_checkout_process', 'verificar_numero_tickets');

function agregar_texto_actualizacion_checkout() {
    // Coloca aquí el texto que deseas mostrar
    $texto_actualizacion = 'VERCION 2';

    echo '<p>' . $texto_actualizacion . '</p>';
}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
