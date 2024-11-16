<?php
get_header();
pageBanner('Past Events', 'A recap ot our past events.');
?>

<div class="container container--narrow page-section">
    <?php
    $pastEvents = new WP_Query([
        'paged' => get_query_var('paged', 1),
        'posts_per_page' => 1,
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => [
            [
                'key' => 'event_date',
                'compare' => '<',
                'value' => date('Ymd'),
                'type' => 'numeric',
            ],
        ],
    ]);
    
    while($pastEvents->have_posts()) {
        $pastEvents->the_post();
        get_template_part('template-parts/content-event');
    }

    echo paginate_links(['total' => $pastEvents->max_num_pages]);
    ?>
</div>

<?php get_footer(); ?>