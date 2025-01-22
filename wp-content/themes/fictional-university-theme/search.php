<?php
get_header();
pageBanner('Search results', 'You search for &ldquo; ' . esc_html(get_search_query(false)) . ' &rdquo;');
?>

<div class="container container--narrow page-section">
    <?php
    if (!have_posts()) {
        echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
    }

    while(have_posts()) {
        the_post(); 
        get_template_part('template-parts/content', get_post_type());   // This functions adds dash after content.
    }

    echo get_search_form();
    
    echo paginate_links();
    ?>
</div>

<?php get_footer(); ?>