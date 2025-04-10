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
        add_menu_page(
            'Words To Filter', 
            'Word Filter',
            'manage_options', 
            'word-filter',
            [$this, 'wordFilterPage'],
            'dashicons-smiley',
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
    }

    public function optionsSubPage()
    { ?>
456
    <?php }

    public function wordFilterPage()
    { ?>
        123
    <?php }
}

$wordFilterPlugin = new WordFilterPlugin();