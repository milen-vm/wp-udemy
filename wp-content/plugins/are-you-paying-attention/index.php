<?php

/*
  Plugin name: Are You Paying Attention Quiz
  Description: Give your readers a multiple choise question.
  Version: 1.0
  Author: Home
  Author URI: https://abv.bg
*/

if(!defined('ABSPATH')) {
    exit;   // Exit if accessed directly.
}

class AreYouPayingAttention
{

    public function __construct()
    {
        add_action('enqueue_block_editor_assets', [$this, 'adminAssets']);
    }

    public function adminAssets()
    {
        wp_enqueue_script('newBlockType', plugin_dir_url(__FILE__) . 'build/index.js', ['wp-blocks', 'wp-element',]);
    }
}

$aypa = new AreYouPayingAttention();