<?php

function university_files(): void
{
    wp_enqueue_style('osm-styles', '//unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    // Make sure you put js script AFTER Leaflet's CSS
    wp_enqueue_script('osm-leaflet-js-script', '//unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['jquery']);
    /**
     * The script version ('ver' param) is needed to show on brouser that there is new version
     * and must be updated.
     * 
     * The last param is to put the script at the bottom of the page.
     */
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), ['jquery'], '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    /**
     * Output js code into our source. Script must be already registered.
     * 
     * First argumet is the name of the file that will be afected.
     * Second argumet is the variable name.
     * Third argumet is array of data that will be available in js file.
     */
    wp_localize_script('main-university-js', 'universityData', [
        'root_url' => get_site_url(),
    ]);
}

/**
 * Features that custom wordpress theme can support.
 * 
 * @return void
 */
function university_fetures(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');   // feature image for custom post type in the editor

    add_image_size('professorLandscape', 400, 260, true);   // create custom size for uplouded images
    add_image_size('professorPortrait', 480, 650, true);    // use plugin to recreate old uploaded images - "Regenerate Thumbnails"
    add_image_size('pageBanner', 1500, 300, true);

    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLoacationOne', 'Footer Location One');
    register_nav_menu('footerLoacationTwo', 'Footer Location Two');
}

/**
 * Initial settings for Wordpress default queryes.
 * 
 * @param mixed $query
 * @return void
 */
function university_ajust_queries($query): void
{
    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);  // no pagination, shows all items on one page
    }

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);  // no pagination, shows all items on one page
    }

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

/**
 * Adds Google api key to make working custom map field for Campus post type.
 * 
 * @param mixed $api
 * 
 * @return mixed
 */
function universityMapKey($api): mixed
{
    $api['key'] = 'Google maps API key';

    return $api;
}

/**
 * Not in use. Using OpenStreetMap.
 */
// add_filter('acf/fields/google_map/api', 'universityMapKey');

/** custom user functions */

function get_page_ID_by_slug(string $slug): int|null
{
    $page = get_page_by_path($slug);
    if($page) {

        return (int) $page->ID;
    }

    return null;
}

/**
 * Page banner template.
 * 
 * @return void
 */
function pageBanner(string $title = '', string $subtitle = '', string $background = ''): void
{
    if(empty($background)) {
        $image = get_field('page_banner_background_image');

        if(
            !is_archive() &&
            !is_home() &&
            is_array($image) &&
            isset($image['sizes']) &&
            $image['sizes']['pageBanner']
        ) {
            $background = $image['sizes']['pageBanner'];
        } else {
            $background = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    if(empty($title)) {
        $title = get_the_title();
    }

    if(empty($subtitle)) {
        $subtitle = get_field('page_banner_subtitle');
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
            style="background-image: url(<?php echo $background; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $title; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $subtitle; ?></p>
            </div>
        </div>
    </div>
<?php
}