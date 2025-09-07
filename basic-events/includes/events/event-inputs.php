<?php

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