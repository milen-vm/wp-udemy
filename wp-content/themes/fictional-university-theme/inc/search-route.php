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
        $data = [
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => $postType,
        ];

        if($postType === 'post' || $postType === 'page') {
            $key = 'generalInfo';
            $data['authorName'] = get_the_author();
        } elseif ($postType === 'professor') {
            $key = 'professors';
            $data['image'] = get_the_post_thumbnail_url(null,'professorLandscape');
        } elseif ($postType === 'program') {
            $key = 'programs';
            $data['id'] = get_the_ID();
        } elseif ($postType === 'event') {
            $key = 'events';
            $data = putEventData($data);
        } elseif($postType === 'campus') {
            $key = 'campuses';
        }

        array_push($results[$key], $data);
    }

    [$programsProfessors, $programsEvents] = getProgramsRelatedItems($results['programs']);

    if($programsProfessors) {
        $results['professors'] = array_merge($results['professors'], $programsProfessors);

        $results['professors'] = array_values(
            array_unique($results['professors'], SORT_REGULAR)
        );
    }

    if($programsEvents) {
        $results['events'] = array_merge($results['events'], $programsEvents);

        $results['events'] = array_values(
            array_unique($results['events'], SORT_REGULAR)
        );
    }

    return $results;
}
/**
 * Call function only if post type is event.
 * 
 * @param array $data
 * @return array
 */
function putEventData(array $data): array
{
    $eventDate = new DateTime(get_field('event_date'));
    $data['month'] = $eventDate->format('M');
    $data['day'] = $eventDate->format('d');
    $data['description'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 17);

    return $data;
}
/**
 * Searching for the professors, events related to given programs.
 * 
 * @param array $programs
 * @return array
 */
function getProgramsRelatedItems(array $programs): array
{
    $metaQuery = [];
    foreach($programs as $program) {
        array_push($metaQuery, [
            'key' => 'relalted_programs',
            'compare' => 'LIKE',
            'value' => '"' . $program['id'] . '"',
        ]);
    }

    if(!$metaQuery) {
        return [];
    }

    $metaQuery['relation'] = 'OR';      // condition in SQL: cond1 OR cond2 OR cond3 ...
    $relatedItems = new WP_Query([
        'post_type' => ['professor', 'event',],
        'meta_query' => $metaQuery,
    ]);

    $professors = [];
    $events = [];
    while($relatedItems->have_posts()) {
        $relatedItems->the_post();
        $type = get_post_type();

        $data = [
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => $type,
        ];

        if($type === 'professor') {
            $data['image'] = get_the_post_thumbnail_url(null,'professorLandscape');
            array_push($professors, $data);
        }

        if($type === 'event') {
            $data = putEventData($data);
            array_push($events, $data);
        }

    }

    return [$professors, $events];
}

add_action('rest_api_init', 'universityRegisterSearch');