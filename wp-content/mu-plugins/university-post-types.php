<?php

function university_post_types()
{
    /**
     * After every new post type permalinks must be updated.
     * In Wordpress admin Setings/Permalinks click "Save"
     */

    // Event post type
    register_post_type('event', [
        'capability_type' => 'event',   // this is needed for custom roles and permissions, for that post type
        'map_meta_cap' => true,     // this is for adding event related premissions in order to manage events post type
        'supports' => [
            'title', 'editor', 'excerpt',       // supported fields, excerpt - short description field of the post
        ],
        'rewrite' => [
            'slug' => 'events',     // rewrite url for archive page
        ],
        'has_archive' => true,      // alows archive list page for this post type
        'public' => true,
        'show_in_rest' => true,     // used for WP REST request to the server
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
            'title',    // support of default edditor is removed because is used custom field for the body content - main_body_content
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
        'menu_icon' => 'dashicons-awards',
    ]);

    // Professor post type
    register_post_type('professor', [
        'supports' => [
            'title', 'editor', 'thumbnail',     // thumbnail - feature image for custom post type in the editor
        ],
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor',
        ],
        'menu_icon' => 'dashicons-welcome-learn-more',
    ]);

    // Campus post type
    register_post_type('campus', [
        'capability_type' => 'campus',   // this is needed for custom roles and permissions, for that post type
        'map_meta_cap' => true,     // this is for adding event related premissions in order to manage events post type
        'supports' => [
            'title', 'editor', 'excerpt',
        ],
        'rewrite' => [
            'slug' => 'campuses',
        ],
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus',
        ],
        'menu_icon' => 'dashicons-location-alt',
    ]);

    // Note post type
    register_post_type('note', [
        'capability_type' => 'note',    // this is needed for custom roles and permissions, for that post type, if is not set is equal to blog post type permissions, must set up roles in admin
        'map_meta_cap' => true,     // this is for adding event related premissions in order to manage events post type
        'supports' => [
            'title', 'editor', 'thumbnail',
        ],
        'public' => false,      // post type is private and specific for every user, not showing in public queries and search results
        'show_ui' => true,      // show in admin
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note',
        ],
        'menu_icon' => 'dashicons-welcome-write-blog',
    ]);
}

add_action('init', 'university_post_types');