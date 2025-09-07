<?php

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