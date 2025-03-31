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
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHTML'], 'word-count-settings-page', 'wcp_first_section');
        // store record in wp_options db table
        register_setting('wordcountplugin', 'wcp_location', [
            'sanitize_callback' => 'sanitize_text_field',       // build in wp function
            'defaul' => '0',
        ]);
    }

    public function locationHTML()
    { ?>
        <select name="wcp_location">
            <option value="0">Beginning of post</option>
            <option value="1">End of post</option>
        </select>
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