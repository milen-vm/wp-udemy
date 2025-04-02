<?php

/*
  Plugin name: First Plugin
  Description: New plugin.
  Version: 1.0
  Author: Home
  Author URI: https://abv.bg
*/

class WordCountAndTimePlugin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminPage']);
        add_action('admin_init', [$this, 'settings']);
        add_filter('the_content', [$this, 'ifWrap']);
    }

    public function adminPage()
    {
        /**
         * 'manage_options' = admin
         */
        add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page', [$this, 'html']);
    }
    
    public function html()
    { ?>
        <div class="wprap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                settings_fields('wordcountplugin');
                do_settings_sections('word-count-settings-page');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function settings()
    {
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');
        // Loacation option
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHTML'], 'word-count-settings-page', 'wcp_first_section');
        // store record in wp_options db table
        register_setting('wordcountplugin', 'wcp_location', [
            'sanitize_callback' => [$this, 'sanitizeLocation'],       // build in wp function
            'default' => '0',
        ]);
        // Head line text option
        add_settings_field(
            'wcp_headline', 
            'Headline Text', 
            [$this, 'headlineHTML'], 
            'word-count-settings-page', 
            'wcp_first_section'
        );
        register_setting('wordcountplugin', 'wcp_headline', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Post Statistics',
        ]);
        // Word count option
        add_settings_field(
            'wcp_wordcount', 
            'Word Count', 
            [$this, 'checkboxHTML'], 
            'word-count-settings-page', 
            'wcp_first_section',
            ['name' => 'wcp_wordcount',]
        );
        register_setting('wordcountplugin', 'wcp_wordcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1',
        ]);
        // Character count option
        add_settings_field(
            'wcp_charactercount', 
            'Character Count', 
            [$this, 'checkboxHTML'], 
            'word-count-settings-page', 
            'wcp_first_section',
            ['name' => 'wcp_charactercount',]
        );
        register_setting('wordcountplugin', 'wcp_charactercount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1',
        ]);
        // read time count option
        add_settings_field(
            'wcp_read_time', 
            'Read Time', 
            [$this, 'checkboxHTML'], 
            'word-count-settings-page', 
            'wcp_first_section',
            ['name' => 'wcp_read_time',]
        );
        register_setting('wordcountplugin', 'wcp_read_time', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1',
        ]);
    }

    public function sanitizeLocation($input): string
    {
        if($input != '0' && $input != '1') {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginnig or end.');

            return get_option('wcp_location');
        }

        return $input;
    }

    public function locationHTML(): void
    {
        // but some options are autoloaded in memory, set in wp_options table 'autoload' column
        $wcpLocation = get_option('wcp_location');        
    ?>
        <select name="wcp_location">
            <option value="0" <?php selected($wcpLocation, '0'); ?>>Beginning of post</option>
            <option value="1" <?php selected($wcpLocation, '1'); ?>>End of post</option>
        </select>
    <?php }

    public function headlineHTML(): void
    { ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
    <?php }

    public function checkboxHTML($args)
    { ?>
        <input type="checkbox" name="<?php echo $args['name']; ?>" value="1" <?php checked(get_option($args['name'], '1')); ?>>
    <?php }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

// add_filter('the_content', 'addToEndOfPost');
// function addToEndOfPost($content): string
// {
//     if(is_single() && is_main_query()) {

//         return (string) $content . '<p>This is my Home.</p>';
//     }

//     return $content;
// }