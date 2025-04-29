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
        // add_action('enqueue_block_editor_assets', [$this, 'adminAssets']);
        add_action('init', [$this, 'adminAssets']);
    }

    public function adminAssets()
    {
        wp_register_style('quizeEditCss', plugin_dir_url(__FILE__) . 'build/index.css');
        // wp_enqueue_script('newBlockType', plugin_dir_url(__FILE__) . 'build/index.js', ['wp-blocks', 'wp-element',]);
        // deps parameter is dependencies that are needed for javascript code
        wp_register_script('newBlockType', plugin_dir_url(__FILE__) . 'build/index.js', ['wp-blocks', 'wp-element', 'wp-editor']);
        register_block_type('myplugin/are-you-paying-attention', [
            'editor_script' => 'newBlockType',  // use this js for that block
            'editor_style' => 'quizeEditCss',   // use this css for that block
            'render_callback' => [$this, 'theHTML']
        ]);
    }

    public function theHTML(array $attributes): string
    {
        ob_start();
?>
<p>Today the sky is <?php echo esc_html($attributes['skyColor']); ?> and the grass is <?php echo esc_html($attributes['grassColor']); ?> !!!!</p>
<?php
        return ob_get_clean();
    }
}

$aypa = new AreYouPayingAttention();