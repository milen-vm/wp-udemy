<?php

/*
  Plugin name: Word Filter Plugin
  Description: Filter given words.
  Version: 1.0
  Author: Home
  Author URI: https://abv.bg
*/

if(!defined('ABSPATH')) {
    exit;   // Exit if accessed directly.
}

class WordFilterPlugin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'menu']);
    }

    public function menu()
    {
        $svg = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+';
        // $svg = plugin_dir_url(__FILE__) . 'custom.svg';

        $pageHook = add_menu_page(
            'Words To Filter', 
            'Word Filter',
            'manage_options', 
            'word-filter',
            [$this, 'wordFilterPage'],
            $svg,
            100
        );

        add_submenu_page(
            'word-filter',
            'Word To Filter',
            'Words List',
            'manage_options',
            'word-filter',
            [$this, 'wordFilterPage']
        );

        add_submenu_page(
            'word-filter',
            'Word Filter Options',
            'Options',
            'manage_options',
            'word-filter-options',
            [$this, 'optionsSubPage']
        );
        // loade custom css for the admin page of the plugin
        add_action('load-' . $pageHook, [$this, 'pageAssets']);
    }

    public function pageAssets()
    {
        wp_enqueue_style('fileterAdminCss', plugin_dir_url(__FILE__) . 'styles.css');
    }

    public function optionsSubPage()
    { ?>
456
    <?php }

    public function wordFilterPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <form method="POST"></form>
            <label for="plugin_words_to_filter">
                <p>Enter a <strong>coma-separated</strong> list of words to filter from your site's content.</p>
            </label>
            <div class="word-filter__flex-container">
                <textarea name="plugin_words_to_filter" id="plugin_words_to_filter"></textarea>
            </div>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </div>
    <?php }
}

$wordFilterPlugin = new WordFilterPlugin();