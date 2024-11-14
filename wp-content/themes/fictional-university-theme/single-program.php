<?php get_header() ?>

<?php while(have_posts()) : ?>
    <?php the_post(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image"
            style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p style="color:red;">TODO replace later.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs
                </a>
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $programProfessors = new WP_Query([
            'posts_per_page' => -1,     // shows all records without pagination
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'relalted_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',    // "12" because the value must match the serialized object in database
                ],
            ],
        ]);

        if($programProfessors->have_posts()) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php echo get_the_title(); ?> Professors</h2>
            <ul class="professor-cards">
                <?php
                while($programProfessors->have_posts()) :
                    $programProfessors->the_post();
                    $eventDate = new DateTime(get_field('event_date'));
                ?>
                    <li class="professor-card__list-item">
                        <a class="professor-card" href="<?php the_permalink(); ?>">
                            <img class="professor-card__image" src="<?php the_post_thumbnail_url(); ?>" alt="">
                            <span class="professor-card__name"><?php the_title(); ?></span>
                        </a> 
                    </li>
                    
                <?php endwhile; ?>
            </ul>
        <?php endif;
        wp_reset_postdata();    // reset the global post object to the route query object, in this case "Program" object
        ?>

        <?php
        $programEvents = new WP_Query([
            'posts_per_page' => -1,
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => date('Ymd'),
                    'type' => 'numeric',
                ],
                [
                    'key' => 'relalted_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',
                ],
            ],
        ]);

        if($programEvents->have_posts()) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php echo get_the_title(); ?> Events</h2>
            <?php
            while($programEvents->have_posts()) :
                $programEvents->the_post();
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
                            <?php echo has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 17); ?>
                            <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                        </p>
                    </div>
                </div>

            <?php
            endwhile;
        endif;
        wp_reset_postdata();
        ?>
    </div>

<?php endwhile ?>

<?php get_footer(); ?>