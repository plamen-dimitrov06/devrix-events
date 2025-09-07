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

# Post type : Register the "Event" custom post type.
require_once plugin_dir_path(__FILE__) . 'event-post-type.php';

# Admin : Add the inputs for the event details.
# Admin : Save the event meta data.
require_once plugin_dir_path(__FILE__) . 'includes/events/event-inputs.php';
require_once plugin_dir_path(__FILE__) . 'includes/events/event-save.php';

/**
 * Load scripts for the media uploader if we're working with events.
 * @TODO move this somewhere else
 */
function basic_events_enqueue_media_uploader() {
    global $post_type;
    if ('event' == $post_type) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'basic_events_enqueue_media_uploader');

# Shortcode : Form for registering for an event on the storefront.
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/event-registration.php';

/**
 * Filter the event archive query to order by event date.
 *
 * @param WP_Query $query The main WP_Query object.
 */
function basic_events_archive_query($query) {
    if (is_admin() || !$query->is_main_query() || !is_post_type_archive('event')) {
        return;
    }

    $query->set('meta_key', '_event_date');
    $query->set('orderby', 'meta_value');
    $query->set('order', 'ASC');
}
add_action('pre_get_posts', 'basic_events_archive_query');

function basic_events_load_archive_template($template) {
    if (is_post_type_archive('event')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'archive-event.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'basic_events_load_archive_template');