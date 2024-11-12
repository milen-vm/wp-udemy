<?php

function university_files(): void
{
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), ['jquery'], '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

function university_fetures(): void
{
    add_theme_support('title-tag');

    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLoacationOne', 'Footer Location One');
    register_nav_menu('footerLoacationTwo', 'Footer Location Two');
}

function university_ajust_queries($query): void
{
    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query',[
            'key' => 'event_date',
            'compare' => '>=',
            'value' => date('Ymd'),
            'type' => 'numeric',
        ]);
    }
}

add_action('wp_enqueue_scripts', 'university_files');;

add_action('after_setup_theme', 'university_fetures');

add_action('pre_get_posts', 'university_ajust_queries');

/** custom user functions */

function get_page_ID_by_slug(string $slug): int|null
{
    $page = get_page_by_path($slug);
    if($page) {

        return (int) $page->ID;
    }

    return null;
}