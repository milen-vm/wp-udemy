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
    
    while($pastEvents->have_posts()):
        $pastEvents->the_post();
        $eventDate = new DateTime(get_field('event_date'));
    ?>

        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month"><?php echo $eventDate->format('M'); ?></span>
                <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p>
                    <?php echo wp_trim_words(get_the_excerpt(), 17); ?>
                    <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                </p>
            </div>
        </div>

    <?php
    endwhile;
    echo paginate_links(['total' => $pastEvents->max_num_pages]);
    ?>
</div>

<?php get_footer(); ?>