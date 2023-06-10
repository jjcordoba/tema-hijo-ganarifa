<?php

//agregar cp a la base de datos 

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
