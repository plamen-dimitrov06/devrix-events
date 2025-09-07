<?php
/**
 * Plugin Name:       Basic Events
 * Description:       A simple plugin to create and manage events.
 * Version:           1.0.0
 * Author:            Plamen Dimitrov
 * Text Domain:       basic-events
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register the "Event" custom post type.
 */
function basic_events_register_post_type() {
    $labels = [
        'name'                  => _x('Events', 'Post Type General Name', 'basic-events'),
        'singular_name'         => _x('Event', 'Post Type Singular Name', 'basic-events'),
        'menu_name'             => __('Events', 'basic-events'),
        'name_admin_bar'        => __('Event', 'basic-events'),
        'archives'              => __('Event Archives', 'basic-events'),
        'attributes'            => __('Event Attributes', 'basic-events'),
        'parent_item_colon'     => __('Parent Event:', 'basic-events'),
        'all_items'             => __('All Events', 'basic-events'),
        'add_new_item'          => __('Add New Event', 'basic-events'),
        'add_new'               => __('Add New', 'basic-events'),
        'new_item'              => __('New Event', 'basic-events'),
        'edit_item'             => __('Edit Event', 'basic-events'),
        'update_item'           => __('Update Event', 'basic-events'),
        'view_item'             => __('View Event', 'basic-events'),
        'view_items'            => __('View Events', 'basic-events'),
        'search_items'          => __('Search Event', 'basic-events'),
        'not_found'             => __('Not found', 'basic-events'),
        'not_found_in_trash'    => __('Not found in Trash', 'basic-events'),
        'featured_image'        => __('Event Banner', 'basic-events'),
        'set_featured_image'    => __('Set Event banner', 'basic-events'),
        'remove_featured_image' => __('Remove Event banner', 'basic-events'),
        'use_featured_image'    => __('Use as Event banner', 'basic-events'),
        'insert_into_item'      => __('Insert into event', 'basic-events'),
        'uploaded_to_this_item' => __('Uploaded to this event', 'basic-events'),
        'items_list'            => __('Events list', 'basic-events'),
        'items_list_navigation' => __('Events list navigation', 'basic-events'),
        'filter_items_list'     => __('Filter events list', 'basic-events'),
    ];
    $args = [
        'label'                 => __('Event', 'basic-events'),
        'description'           => __('Post Type for Events', 'basic-events'),
        'labels'                => $labels,
        'supports'              => ['title', 'editor', 'thumbnail'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    ];
    register_post_type('event', $args);
}
add_action('init', 'basic_events_register_post_type');

/**
 * Add the "Event Details" custom meta box.
 */
function basic_events_add_meta_box() {
    add_meta_box(
        'basic_events_details',
        'Event Details',
        'basic_events_details_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'basic_events_add_meta_box');

/**
 * Display the custom meta box content.
 * Includes form fields for event date, location, URL, attendees, and media.
 */
function basic_events_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'basic_events_nonce');

    $event_date          = get_post_meta($post->ID, '_event_date', true);
    $event_location      = get_post_meta($post->ID, '_event_location', true);
    $event_url           = get_post_meta($post->ID, '_event_url', true);
    $max_attendees       = get_post_meta($post->ID, '_max_attendees', true);
    $current_registrations = get_post_meta($post->ID, '_registration_count', true);
    $event_video_url     = get_post_meta($post->ID, '_event_video_url', true);
    $event_banner_id     = get_post_meta($post->ID, '_event_banner_id', true);

    echo '<table class="form-table">';
    // Event Date
    echo '<tr><th><label for="event_date">' . esc_html__('Event Date', 'basic-events') . '</label></th>';
    echo '<td><input type="date" name="event_date" id="event_date" value="' . esc_attr($event_date) . '" class="regular-text"></td></tr>';

    // Event Location
    echo '<tr><th><label for="event_location">' . esc_html__('Event Location', 'basic-events') . '</label></th>';
    echo '<td><input type="text" name="event_location" id="event_location" value="' . esc_attr($event_location) . '" class="regular-text"></td></tr>';

    // Event URL
    echo '<tr><th><label for="event_url">' . esc_html__('Event URL', 'basic-events') . '</label></th>';
    echo '<td><input type="url" name="event_url" id="event_url" value="' . esc_attr($event_url) . '" class="regular-text"></td></tr>';

    // Maximum Attendees
    echo '<tr><th><label for="max_attendees">' . esc_html__('Maximum Attendees', 'basic-events') . '</label></th>';
    echo '<td><input type="number" name="max_attendees" id="max_attendees" value="' . esc_attr($max_attendees) . '" class="regular-text"></td></tr>';

    // Current Registration Count
    echo '<tr><th><label>' . esc_html__('Current Registrations', 'basic-events') . '</label></th>';
    echo '<td><span id="registration_count_display">' . intval($current_registrations) . '</span></td></tr>';

    // Video/Trailer Field
    echo '<tr><th><label for="event_video_url">' . esc_html__('Video/Trailer URL', 'basic-events') . '</label></th>';
    echo '<td><input type="text" name="event_video_url" id="event_video_url" value="' . esc_url($event_video_url) . '" class="regular-text"><br>';
    echo '<p class="description">' . esc_html__('Enter the URL of a video (e.g., from YouTube or Vimeo).', 'basic-events') . '</p></td></tr>';
    
    // Image Upload/Banner Field
    $image_url = $event_banner_id ? wp_get_attachment_url($event_banner_id) : '';
    echo '<tr><th><label for="event_banner_id">' . esc_html__('Event Banner', 'basic-events') . '</label></th>';
    echo '<td><input type="hidden" name="event_banner_id" id="event_banner_id" value="' . esc_attr($event_banner_id) . '" class="regular-text">';
    echo '<img id="event_banner_preview" src="' . esc_url($image_url) . '" style="max-width: 300px; display:' . ($image_url ? 'block' : 'none') . ';" /><br>';
    echo '<input type="button" class="button" id="upload_banner_button" value="' . esc_attr__('Upload/Select Banner', 'basic-events') . '">';
    echo '<input type="button" class="button button-secondary" id="remove_banner_button" value="' . esc_attr__('Remove Banner', 'basic-events') . '" style="display:' . ($image_url ? 'inline-block' : 'none') . ';"></td></tr>';

    echo '</table>';

    // Media uploader script
    ?>
    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            $('#upload_banner_button').click(function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media({
                    title: 'Choose Banner Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#event_banner_id').val(attachment.id);
                    $('#event_banner_preview').attr('src', attachment.url).show();
                    $('#remove_banner_button').show();
                });
                mediaUploader.open();
            });

            $('#remove_banner_button').click(function(e) {
                e.preventDefault();
                $('#event_banner_id').val('');
                $('#event_banner_preview').attr('src', '').hide();
                $(this).hide();
            });
        });
    </script>
    <?php
}

/**
 * Save the custom meta box data when the post is saved.
 */
function basic_events_save_meta_box_data($post_id) {
    if (!isset($_POST['basic_events_nonce']) || !wp_verify_nonce($_POST['basic_events_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Save/update meta fields
    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
    }
    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
    }
    if (isset($_POST['event_url'])) {
        update_post_meta($post_id, '_event_url', sanitize_url($_POST['event_url']));
    }
    if (isset($_POST['max_attendees'])) {
        update_post_meta($post_id, '_max_attendees', intval($_POST['max_attendees']));
    }
    if (isset($_POST['event_video_url'])) {
        update_post_meta($post_id, '_event_video_url', esc_url_raw($_POST['event_video_url']));
    }
    if (isset($_POST['event_banner_id'])) {
        update_post_meta($post_id, '_event_banner_id', intval($_POST['event_banner_id']));
    }
}
add_action('save_post', 'basic_events_save_meta_box_data');

/**
 * Enqueue scripts for the media uploader and custom post type.
 */
function basic_events_enqueue_media_uploader() {
    global $post_type;
    if ('event' == $post_type) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'basic_events_enqueue_media_uploader');

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
