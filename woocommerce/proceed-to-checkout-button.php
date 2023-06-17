<?php
/**
 * Botón Proceder al pago
 *
 * Contiene el marcado para el botón de proceder a la compra en el carrito.
 *
 * Esta plantilla puede ser modificada copiándola en yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * SIN EMBARGO, en ocasiones WooCommerce necesitará actualizar los archivos de plantilla y usted
 * (el desarrollador del tema) necesitará copiar los nuevos archivos a su tema para
 * mantener la compatibilidad. Intentamos hacer esto lo menos posible, pero ocurre.
 * sucede. Cuando esto ocurre la versión del archivo de plantilla será bumped y
 * el readme listará cualquier cambio importante.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
	<?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?>
</a>
