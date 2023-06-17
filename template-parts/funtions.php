<?php


from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def autocompletar_formulario_checkout(url_checkout):
    # Configurar el controlador de Selenium (requiere el controlador específico de tu navegador)
    driver = webdriver.Chrome('ruta_al_controlador_de_chrome/chromedriver')
    
    try:
        # Navegar hasta la página de checkout
        driver.get(url_checkout)
        
        # Esperar a que se cargue el formulario de tarjeta
        formulario_tarjeta = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.ID, 'id_del_formulario_de_tarjeta'))
        )
        
        # Rellenar los campos del formulario
        numero_tarjeta = driver.find_element(By.ID, 'id_del_campo_numero_tarjeta')
        numero_tarjeta.send_keys('3711 803032 57522')
        
        nombre_titular = driver.find_element(By.ID, 'id_del_campo_nombre_titular')
        nombre_titular.send_keys('Martines')
        
        vencimiento = driver.find_element(By.ID, 'id_del_campo_vencimiento')
        vencimiento.send_keys('12/25')
        
        codigo_seguridad = driver.find_element(By.ID, 'id_del_campo_codigo_seguridad')
        codigo_seguridad.send_keys('1234')
        
        documento_titular = driver.find_element(By.ID, 'id_del_campo_documento_titular')
        documento_titular.send_keys('41121548')
        
        # Enviar el formulario
        formulario_tarjeta.submit()
    
    finally:
        # Cerrar el controlador de Selenium
        driver.quit()

# Llamar a la función para iniciar el proceso de rellenado automático del formulario de checkout
url_checkout = 'https://ganarifa.com/pedido/'
autocompletar_formulario_checkout(url_checkout)

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
    $texto_actualizacion = 'VERCION 1';

}

// Hook para mostrar el texto en el checkout
add_action('woocommerce_review_order_after_order_total', 'agregar_texto_actualizacion_checkout');
