// Add the custom field to checkout
add_action('woocommerce_after_order_notes', 'custom_delivery_instructions_field');
function custom_delivery_instructions_field($checkout) {
    echo '<div id="custom_checkout_field"><h3>' . __('Delivery Instructions') . '</h3>';
    
    woocommerce_form_field('delivery_instructions', array(
        'type'        => 'textarea',
        'class'       => array('form-row-wide'),
        'label'       => __('Enter special delivery instructions'),
        'placeholder' => __('e.g. Leave at the back door'),
        'required'    => false,
    ), $checkout->get_value('delivery_instructions'));

    echo '</div>';
}

// Save the custom field data to order meta
add_action('woocommerce_checkout_update_order_meta', 'save_custom_delivery_instructions');
function save_custom_delivery_instructions($order_id) {
    if (!empty($_POST['delivery_instructions'])) {
        update_post_meta($order_id, 'delivery_instructions', sanitize_text_field($_POST['delivery_instructions']));
    }
}

// Display the custom field in the admin order edit page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_delivery_instructions_in_admin', 10, 1);
function display_custom_delivery_instructions_in_admin($order) {
    $delivery_instructions = get_post_meta($order->get_id(), 'delivery_instructions', true);
    if ($delivery_instructions) {
        echo '<p><strong>' . __('Delivery Instructions') . ':</strong> ' . esc_html($delivery_instructions) . '</p>';
    }
}

// Add the custom field to order emails
add_filter('woocommerce_email_order_meta_keys', 'add_custom_delivery_instructions_to_emails');
function add_custom_delivery_instructions_to_emails($keys) {
    $keys[] = 'delivery_instructions';
    return $keys;
}
