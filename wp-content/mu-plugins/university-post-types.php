<?php

function university_post_types()
{
    // Event post type
    register_post_type('event', [
        'supports' => [
            'title', 'editor', 'excerpt',
        ],
        'rewrite' => [
            'slug' => 'events',
        ],
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event',
        ],
        'menu_icon' => 'dashicons-calendar',    // WordPress Dashicons - developer.wordpress.org
    ]);

    // Program post type
    register_post_type('program', [
        'supports' => [
            'title', 'editor',
        ],
        'rewrite' => [
            'slug' => 'programs',
        ],
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program',
        ],
        'menu_icon' => 'dashicons-awards',    // WordPress Dashicons - developer.wordpress.org
    ]);
}

add_action('init', 'university_post_types');