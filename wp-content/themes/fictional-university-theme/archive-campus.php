<?php
get_header();
pageBanner('Our Campuses', 'We have several conveniently located campuses.');
?>

<div class="container container--narrow page-section">
    <div class="acf-map">
        <?php    
        while(have_posts()):
            the_post();
        ?>

            <div class="marker">
                <?php echo '<pre>' . print_r(get_field('map_location'), true); ?>
            </div>

        <?php endwhile; ?>
    </div>
    <?php echo paginate_links(); ?>
</div>

<?php // get_footer(); ?>  