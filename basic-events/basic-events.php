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