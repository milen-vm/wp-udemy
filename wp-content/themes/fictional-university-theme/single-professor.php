<?php
get_header();
pageBanner();

while(have_posts()) :
    the_post();
?>
    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('professorPortrait', ['alt' => 'professor image']); ?>
                </div>
                <div class="two-thirds">
                    <?php
                    $likes = new WP_Query([
                        'post_type' => 'like',
                        'meta_query' => [
                            [
                                'key' => 'liked_professor_id',
                                'compare' => '=',
                                'value' => get_the_ID(),
                            ]
                        ]
                    ]);

                    $status = 'no';
                    foreach($likes->posts as $like) {
                        $authorId = (int) $like->post_author;
                        if($authorId === get_current_user_id()) {
                            $status = 'yes';
                        }
                    }
                    // query to check is the current user liked the professor
                    // $exists = new WP_Query([
                    //     'author' => get_current_user_id(),
                    //     'post_type' => 'like',
                    //     'meta_query' => [
                    //         [
                    //             'key' => 'liked_professor_id',
                    //             'compare' => '=',
                    //             'value' => get_the_ID(),
                    //         ]
                    //     ]
                    // ]);
                    ?>
                    <span class="like-box" data-exists="<?php echo $status; ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $likes->found_posts; ?></span>
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        <?php
        $relatedPrograms = get_field('relalted_programs');
        if(count($relatedPrograms)) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Subject(s) Taught</h2>
            <ul class="link-list min-list">
                <?php foreach($relatedPrograms as $program) : ?>
                    <li>
                        <a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a> 
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif ?>
    </div>

<?php endwhile ?>

<?php get_footer(); ?>