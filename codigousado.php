<?php

function guardar_en_tabla_rifas($post_id) {
	global $wpdb;
	$tabla_nombre = $wpdb->prefix . 'rifas';
	$post_type = get_post_type($post_id);

	// Verificar si existe la tabla y crearla si no existe
	if ($wpdb->get_var("SHOW TABLES LIKE '$tabla_nombre'") != $tabla_nombre) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $tabla_nombre (
					id INT(11) NOT NULL AUTO_INCREMENT,
					post_title VARCHAR(255) NOT NULL,
					post_content LONGTEXT NOT NULL,
					PRIMARY KEY (id)
			) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	}

	if ($post_type == 'product') {
			$post_title = get_the_title($post_id);
			$post_content = get_post_field('post_content', $post_id);

			$data = array(
					'post_title' => $post_title,
					'post_content' => $post_content
			);

			$format = array(
					'%s',
					'%s'
			);

			$where = array(
					'id' => $post_id
			);

			$where_format = array(
					'%d'
			);

			$wpdb->replace($tabla_nombre, $data, $format, $where, $where_format);
	}
}
add_action('save_post', 'guardar_en_tabla_rifas');





function crear_tabla_rifas() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'rifas';

	// Verificar si la tabla ya existe
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			// Crear la tabla si no existe
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					post_author bigint(20) unsigned NOT NULL DEFAULT 0,
					post_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					post_date_gmt datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					post_content longtext NOT NULL,
					post_title text NOT NULL,
					post_excerpt text NOT NULL,
					post_status varchar(20) NOT NULL DEFAULT 'publish',
					comment_status varchar(20) NOT NULL DEFAULT 'open',
					ping_status varchar(20) NOT NULL DEFAULT 'open',
					post_password varchar(255) NOT NULL DEFAULT '',
					post_name varchar(200) NOT NULL DEFAULT '',
					to_ping text NOT NULL,
					pinged text NOT NULL,
					post_modified datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					post_modified_gmt datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
					post_content_filtered longtext NOT NULL,
					post_parent bigint(20) unsigned NOT NULL DEFAULT 0,
					guid varchar(255) NOT NULL DEFAULT '',
					menu_order int(11) NOT NULL DEFAULT 0,
					post_type varchar(20) NOT NULL DEFAULT 'post',
					post_mime_type varchar(100) NOT NULL DEFAULT '',
					comment_count bigint(20) NOT NULL DEFAULT 0,
					num_ganadores int(11) DEFAULT 0,
					cantidad_ganadores int(11) DEFAULT 0,
					premios_ganadores	varchar(255) NULL,
					fecha_sorteo	datetime NULL,
					PRIMARY KEY  (ID)
			) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	}
}
function guardar_campos_rifas($post_id) {
	if (get_post_type($post_id) == 'product') {
		$cantidad_ganadores = get_field('cantidad_ganadores', $post_id);
		$premios_ganadores = ''; // Variable para almacenar la lista de premios

		// Obtener los premios del campo "premios_ganadores"
		$premios = get_field('premios_ganadores', $post_id);
		
		// Recorrer la lista de premios y agregarlos a la variable
		if ($premios) {
			foreach ($premios as $premio) {
				$nombre_premio = $premio['premio'];
				$premios_ganadores .= $nombre_premio . ', ';
			}
		}

		$premios_ganadores = rtrim($premios_ganadores, ', '); // Eliminar la última coma y espacio en blanco

		$fecha_sorteo = get_field('data_sorteio', $post_id); // Obtener la fecha y hora del sorteo

		global $wpdb;
		$table_name = $wpdb->prefix . 'rifas';

		// Obtener el ID de la fila correspondiente a la publicación
		$query = $wpdb->prepare("SELECT id FROM $table_name WHERE post_title = %s AND post_content = %s", get_the_title($post_id), get_post_field('post_content', $post_id));
		$result = $wpdb->get_results($query);
		$row_id = $result[0]->id;

		// Actualizar la columna "cantidad_ganadores" en la fila correspondiente
		$wpdb->update(
			$table_name,
			array('cantidad_ganadores' => $cantidad_ganadores),
			array('id' => $row_id),
			array('%d'),
			array('%d')
		);

		// Actualizar la columna "premios_ganadores" en la fila correspondiente
		$wpdb->update(
			$table_name,
			array('premios_ganadores' => $premios_ganadores),
			array('id' => $row_id),
			array('%s'),
			array('%d')
		);

		// Actualizar la columna "fecha_sorteo" en la fila correspondiente
		$wpdb->update(
			$table_name,
			array('fecha_sorteo' => $fecha_sorteo),
			array('id' => $row_id),
			array('%s'),
			array('%d')
		);
	}
}

add_action('save_post', 'guardar_campos_rifas');

add_filter( 'woocommerce_add_to_cart_redirect', 'plugin_rifa_addon_cart_redirect_checkout' );

function plugin_rifa_addon_cart_redirect_checkout( $url ) {
    return wc_get_checkout_url();
}
