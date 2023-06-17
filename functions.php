<?php

include_once 'template-parts/funtions.php';
include_once 'peronalizacion/login.php';



add_action('woocommerce_after_checkout_validation', 'verificar_tickets_repetidos', 10, 2);

function verificar_tickets_repetidos($data, $errors) {
    if (isset($_POST['woocommerce_checkout_place_order'])) {
        $numeroTickets = $_POST['billing_cotasescolhidas']; // Obtener el número de tickets del formulario de pago

        // Verificar si el número de tickets está repetido
        if (numeroTicketsRepetidos($numeroTickets)) {
            // Mostrar mensaje de número de tickets no disponible
            $mensaje = 'El número de ticket elegido (' . $numeroTickets . ') ya ha sido comprado y no está disponible.';
            $errors->add('validation', $mensaje);

            // Impedir finalizar la compra
            wc_add_notice($mensaje, 'error');
        }
    }
}

function numeroTicketsRepetidos($numeroTickets) {
    global $wpdb;

    $tablaMeta = $wpdb->prefix . 'woocommerce_order_itemmeta';
    $campoTickets = 'billing_cotasescolhidas';

    // Consulta personalizada para verificar si el número de tickets ya existe en los pedidos
    $consulta = $wpdb->prepare("
        SELECT COUNT(*)
        FROM $tablaMeta
        WHERE meta_key = %s
        AND meta_value = %s",
        $campoTickets,
        $numeroTickets
    );
    $resultado = $wpdb->get_var($consulta);

    // Comprueba si el número de tickets ya existe
    if ($resultado > 0) {
        return true; // El número de tickets está repetido
    }

    return false; // El número de tickets no está repetido
}
