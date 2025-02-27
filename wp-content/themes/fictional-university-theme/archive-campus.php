<?php
get_header();
pageBanner('Our Campuses', 'We have several conveniently located campuses.');
?>

<div class="container container--narrow page-section">
    <div id="acf-map" class="acf-map">
        <?php
        while(have_posts()):
            the_post();
            $mapLocation = get_field('map_location');
            $lat = $mapLocation['markers'][0]['lat'];
            $lng = $mapLocation['markers'][0]['lng'];
            $label = $mapLocation['markers'][0]['label'];
            // echo '<pre>' . print_r(get_field('map_location'), true);
        ?>

            <div class="marker" data-lat="<?php echo $lat; ?>" data-lng="<?php echo $lng; ?>">
                <h3>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <span><?php echo $label; ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>  