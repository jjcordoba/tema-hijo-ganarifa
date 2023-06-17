<?php

// Add custom field to the order form for ticket number
add_action( 'woocommerce_after_order_notes', 'add_ticket_number_field' );

function add_ticket_number_field( $checkout ) {
    echo '<div id="ticket_number_field">';
    
    woocommerce_form_field( 'ticket_number', array(
        'type'         => 'text',
        'class'        => array( 'form-row-wide' ),
        'label'        => __( 'Ticket Number' ),
        'required'     => true,
    ), $checkout->get_value( 'ticket_number' ) );
    
    echo '</div>';
}
// Validate ticket number before order creation
add_action( 'woocommerce_checkout_process', 'validate_ticket_number' );

function validate_ticket_number() {
    $ticket_number = $_POST['ticket_number'];
    
    // Check if the ticket number already exists in orders
    $order_exists = false;
    $orders = wc_get_orders( array(
        'status' => array( 'pending', 'processing', 'on-hold', 'completed' ), // Orders with these statuses
        'meta_key' => 'ticket_number', // Custom field name
        'meta_value' => $ticket_number, // Ticket number to check
    ) );
    
    if ( count( $orders ) > 0 ) {
        $order_exists = true;
    }
    
    // Display error message if the ticket number is already used
    if ( $order_exists ) {
        wc_add_notice( __( 'Este número de billete ya ha sido utilizado. Por favor, introduzca un número de ticket diferente.' ), 'error' );
    }
}
