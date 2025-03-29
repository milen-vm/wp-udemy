<?php

/*
  Plugin name: First Plugin
  Description: New plugin.
  Version: 1.0
  Author: Home
  Author URI: https://abv.bg
*/

add_filter('the_content', 'addToEndOfPost');

function addToEndOfPost($content): string
{
    if(is_single() && is_main_query()) {

        return (string) $content . '<p>This is my Home.</p>';
    }

    return $content;
}