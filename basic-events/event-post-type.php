<?php

/**
 * Events help organize group gatherings.
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