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
        </div>
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