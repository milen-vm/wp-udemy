<?php 
get_header();
pageBanner(
    get_the_archive_title(),
    get_the_archive_description()
);

// used for custom page banner title
// $pageHeader = '';
// if(is_category()) {
//     $pageHeader = single_cat_title('', false);
// } elseif(is_author()) {
//     $pageHeader = 'Posts by ' . get_the_author();
// }
?>

<div class="container container--narrow page-section">
    <?php while(have_posts()): ?>
        <?php the_post(); ?>

        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <div class="metabox">
                <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('j.m.Y'); ?> in <?php echo get_the_category_list(', '); ?></p>
            </div>
            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
            </div>
        </div>
    <?php endwhile ?>
    <?php echo paginate_links(); ?>
</div>

<?php get_footer(); ?>