<?php get_header(); ?>

<h1><?php bloginfo('name'); ?></h1>
<p><?php bloginfo('description'); ?>`</p>

<?php $blog_posts = new WP_Query( array( 'post_type' => 'post', 'post_status’' => 'publish', 'posts_per_page' => -1 ) ); ?>
<?php while(have_posts()) : ?>
    <?php the_post(); ?>

    <h2 style="color: green;">
        <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </h2>

    <?php the_content(); ?>

    <hr />
<?php endwhile ?>

<?php get_footer(); ?>