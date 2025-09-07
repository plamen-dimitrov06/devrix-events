<?php

/**
 * Create a shortcode for the registration form.
 * [event_registration_form]
 */
function basic_events_registration_form_shortcode() {
    ob_start();

    // Check if the event exists and has a post ID
    $post_id = get_the_ID();
    if (!$post_id) {
        return '<p>' . esc_html__('Registration form could not be loaded.', 'basic-events') . '</p>';
    }

    $registration_count = get_post_meta($post_id, '_registration_count', true);
    $max_attendees      = get_post_meta($post_id, '_max_attendees', true);
    $max_attendees      = empty($max_attendees) ? PHP_INT_MAX : intval($max_attendees);
    
    // Check if registration is full
    if (intval($registration_count) >= $max_attendees) {
        return '<p>' . esc_html__('This event is full. Registration is closed.', 'basic-events') . '</p>';
    }

    // Handle form submission on the same page
    if (isset($_POST['basic_events_register']) && check_ajax_referer('basic_events_registration_nonce', 'registration_nonce', false)) {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name  = sanitize_text_field($_POST['last_name']);

        if (!empty($first_name) && !empty($last_name)) {
            // Update the registration count
            $new_count = intval($registration_count) + 1;
            update_post_meta($post_id, '_registration_count', $new_count);

            // Store attendee names
            $attendees = get_post_meta($post_id, '_event_attendees', true);
            $attendees = is_array($attendees) ? $attendees : [];
            $attendees[] = ['first_name' => $first_name, 'last_name' => $last_name];
            update_post_meta($post_id, '_event_attendees', $attendees);

            echo '<p style="color: green;">' . esc_html__('Thank you for registering!', 'basic-events') . '</p>';
        } else {
            echo '<p style="color: red;">' . esc_html__('Please fill out both name fields.', 'basic-events') . '</p>';
        }
    }

    ?>
    <form action="" method="post">
        <?php wp_nonce_field('basic_events_registration_nonce', 'registration_nonce'); ?>
        <p>
            <label for="first_name"><?php esc_html_e('First Name', 'basic-events'); ?>:</label><br>
            <input type="text" id="first_name" name="first_name" required>
        </p>
        <p>
            <label for="last_name"><?php esc_html_e('Last Name', 'basic-events'); ?>:</label><br>
            <input type="text" id="last_name" name="last_name" required>
        </p>
        <p>
            <input type="submit" name="basic_events_register" value="<?php esc_attr_e('Register', 'basic-events'); ?>">
        </p>
    </form>
    <?php

    return ob_get_clean();
}
add_shortcode('event_registration_form', 'basic_events_registration_form_shortcode');
