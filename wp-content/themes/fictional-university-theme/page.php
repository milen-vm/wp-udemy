<?php get_header(); ?>

<?php while (have_posts()): ?>
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
        <?php
        $parentId = wp_get_post_parent_id(get_the_ID());
        if ($parentId != 0):
        ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($parentId); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parentId); ?>
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>

        <?php endif ?>

        <?php if($parentId || get_pages(['child_of' => get_the_ID()])): ?>

            <div class="page-links">
                <h2 class="page-links__title">
                    <a href="<?php echo get_permalink($parentId); ?>"><?php echo get_the_title($parentId); ?></a>
                </h2>
                <ul class="min-list">
                    <?php wp_list_pages([
                        'title_li' => null,
                        'child_of' => ($parentId === 0 ? get_the_ID() : $parentId),
                        'sort_column' => 'menu_order',
                    ]); ?>
                    <!-- <li class="current_page_item"><a href="#">Our History</a></li>
                    <li><a href="#">Our Goals</a></li> -->
                </ul>
            </div>

        <?php endif ?>

        <div class="generic-content">
            <?php the_content() ?>
        </div>
    </div>

<?php endwhile ?>

<?php get_footer(); ?>