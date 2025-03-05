<?php

require_once get_theme_file_path('/inc/search-route.php');

add_action('wp_enqueue_scripts', 'university_files');;
add_action('after_setup_theme', 'university_fetures');
add_action('pre_get_posts', 'university_ajust_queries');
add_action('rest_api_init', 'university_custom_rest');
add_action('admin_init', 'redirectSubscriberToFrontPage');
add_action('wp_loaded', 'noSubscriberAdminBar');
add_action('login_enqueue_scripts', 'ourLoginCss');
// customize wp items
add_filter('login_headerurl', 'ourHeaderUrl');
add_filter('login_headertitle', 'ourLoginTitle');
// Before save note actions.
// priority is used when many functions is called for one hook
// accepted_arg is for caunt of arguments in callback function
add_filter('wp_insert_post_data', 'beforeSaveNote', 10, 2);

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
        'nonce' => wp_create_nonce('wp_rest'),   // "number used ones" - similar to login token, authorized token
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

function university_custom_rest(): void
{
    /**
     * First argument is the publication type that will be updated.
     * Second is the name of the new field.
     * Third one is an array that describes how to manage this new field.
     * Function in 'get_callback' returns the value of the new field.
     */
    register_rest_field('post', 'authorName', [
        'get_callback' => function() {
            return get_the_author();
        }
    ]);

    register_rest_field('note', 'userNoteCount', [
        'get_callback' => function() {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ]);
}

/**
 * Redirect user after login.
 * 
 * @return void
 */
function redirectSubscriberToFrontPage(): void
{
    $user = wp_get_current_user();
    if(count($user->roles) === 1 && $user->roles[0] === 'subscriber') {
        wp_redirect(site_url('/'));

        exit;
    }
}

/**
 * Hide admin bar for subscriber user role.
 * 
 * @return void
 */
function noSubscriberAdminBar(): void
{
    $user = wp_get_current_user();
    if(count($user->roles) === 1 && $user->roles[0] === 'subscriber') {
        show_admin_bar(false);
    }
}

/**
 * Customize login screen
 * 
 * @return string
 */
function ourHeaderUrl(): string
{
    return esc_url(site_url('/'));
}

/**
 * Add styles to wp login page.
 * 
 * @return void
 */
function ourLoginCss(): void
{
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

/**
 * Change login title.
 * 
 * @return string
 */
function ourLoginTitle(): string
{
    return get_bloginfo('name');
}

/**
 * CUSTOM APPLICATION FUNCTIONS
 */

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

/**
 * Force note posts to be private and sanitize.
 */
function beforeSaveNote($data, $post): mixed
{
    if($data['post_type'] === 'note') {
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);

        $postId = isset($post['ID']) ? $post['ID'] : 0;
        if(count_user_posts(get_current_user_id(), 'note') > 4 && $postId === 0) {
            exit('No more notes allowed to write.');
        }
    }

    if($data['post_type'] === 'note' && $data['post_status'] !== 'trash') {
        $data['post_status'] = 'private';
    }

    return $data;
}