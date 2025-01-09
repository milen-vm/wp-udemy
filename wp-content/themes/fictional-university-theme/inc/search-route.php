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
            $eventDate = new DateTime(get_field('event_date'));
            $data['month'] = $eventDate->format('M');
            $data['day'] = $eventDate->format('d');
            $data['description'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 17);
        } elseif($postType === 'campus') {
            $key = 'campuses';
        }

        array_push($results[$key], $data);
    }

    $programsProfessors = getProfessorsForPrograms($results['programs']);
    if($programsProfessors) {
        $results['professors'] = array_merge($results['professors'], $programsProfessors);

        $results['professors'] = array_values(
            array_unique($results['professors'], SORT_REGULAR)
        );
    }

    return $results;
}
/**
 * Searching for the professors related to given programs.
 * 
 * @param array $programs
 * @return array
 */
function getProfessorsForPrograms(array $programs): array
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

    $metaQuery['relation'] = 'OR';
    $professors = new WP_Query([
        'post_type' => 'professor',
        'meta_query' => $metaQuery,
    ]);

    $result = [];
    while($professors->have_posts()) {
        $professors->the_post();
        $data = [
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => 'professor',
            'image' => get_the_post_thumbnail_url(null,'professorLandscape'),
        ];

        array_push($result, $data);
    }

    return $result;
}

add_action('rest_api_init', 'universityRegisterSearch');