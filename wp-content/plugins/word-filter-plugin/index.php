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
        add_filter('the_content', [$this, 'filterLogic']);
        add_action('admin_init', [$this, 'settings']);
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

    public function filterLogic($content): string
    {
        $option = get_option('plugin_words_to_filter');
        if(!$option) {

            return $content;
        }

        $words = array_map('trim', explode(',', $option));
        $content = str_ireplace($words, get_option('replacementText', '****'), $content);

        return $content;
    }

    public function pageAssets()
    {
        wp_enqueue_style('fileterAdminCss', plugin_dir_url(__FILE__) . 'styles.css');
    }

    public function wordFilterPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php if(isset($_POST['just-submitted'])) $this->handleform(); ?>
            <form method="POST">
                <input type="hidden" name="just-submitted" value="true" />
                <?php wp_nonce_field('saveFilterWords', 'filterNonce'); ?>
                <label for="plugin_words_to_filter">
                    <p>Enter a <strong>coma-separated</strong> list of words to filter from your site's content.</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter"><?php echo esc_textarea(get_option('plugin_words_to_filter')) ?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php }

    public function handleForm()
    {
        if(
            !isset($_POST['filterNonce']) ||
            !wp_verify_nonce($_POST['filterNonce'], 'saveFilterWords') ||
            !current_user_can('manage_options')
        ) { ?>
            <div class="error">
                <p>You do not have permission to perform that action!</p>
            </div>
        <?php 
            return;
        }

        update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter'])); 
    ?>
        <div class="updated">
            <p>Your filtered word were saved.</p>
        </div>
        
    <?php }

    public function optionsSubPage()
    { ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="POST">
                <?php
                settings_errors();
                settings_fields('replacementFields');
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function settings()
    {
        // page argument is the slug of the page
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        add_settings_field(
            'replacement-text',
            'Filtered Text',
            [$this, 'replacementFieldHTML'],
            'word-filter-options',
            'replacement-text-section'
        );
    }

    public function replacementFieldHTML()
    { ?>
        <input type="text" name="replacementText" id="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')) ?>" />
        <p class="description">Leave blank to simply remove filtered words.</p>
    <?php }
}

$wordFilterPlugin = new WordFilterPlugin();