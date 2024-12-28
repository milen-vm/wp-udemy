<?php

function universityRegisterSearch(): void
{
    /**
     * First parameter is url segment after core prefix.
     * Second parameter is a current route.
     */
    register_rest_route('university/v1', 'search', [
        'methods' => WP_REST_SERVER::READABLE,  // const for 'GET' method
        'callback' => 'universitySearchResults',
    ]);
}

function universitySearchResults($data): array
{
    // Key 'term' in $data array is a get parameter from api url.
    $mainQuery = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'program', 'event', 'campus',],
        's' => sanitize_text_field($data['term']),  // search value
    ]);

    $results = [
        'generalInfo' => [],
        'professors' => [],
        'programs' => [],
        'events' => [],
        'campuses' => [],
    ];

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $key = '';
        $postType = get_post_type();
        if($postType === 'post' || $postType === 'page') {
            $key = 'generalInfo';
        } elseif ($postType === 'professor') {
            $key = 'professors';
        } elseif ($postType === 'program') {
            $key = 'programs';
        } elseif ($postType === 'event') {
            $key = 'events';
        } elseif($postType === 'campus') {
            $key = 'campuses';
        }

        array_push($results[$key], [
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => $postType,
            'authorName' => get_the_author(),
        ]);
    }

    return $results;
}

add_action('rest_api_init', 'universityRegisterSearch');