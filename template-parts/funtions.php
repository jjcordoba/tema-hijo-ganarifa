<?php
function verificar_numero_tickets($numero_tickets) {
  // Obtener todos los pedidos
  $pedidos = get_posts(array(
      'post_type' => 'shop_order', // Asegúrate de que esto sea el tipo de post correcto
      'numberposts' => -1,
      'post_status' => array('wc-processing', 'wc-completed') // Incluye aquí los estados de pedido que deseas verificar
  ));

  // Recorrer los pedidos y verificar si el número de tickets está presente en cada uno
  foreach ($pedidos as $pedido) {
      // Obtener el número de tickets del pedido actual
      $tickets_pedido = get_post_meta($pedido->ID, 'billing_cotasescolhidas', true);

      // Verificar si el número de tickets coincide
      if ($tickets_pedido === $numero_tickets) {
          // Mostrar mensaje de error y detener el procesamiento del pago
          wc_add_notice('El número de tickets elegido ya no está disponible.', 'error');
          return false;
      }
  }

  // Si no se encuentra el número de tickets en ningún pedido existente, permitir el procesamiento del pago
  return true;
}

// Hook para verificar el número de tickets antes de procesar el pago
add_action('woocommerce_checkout_process', 'verificar_numero_tickets');
