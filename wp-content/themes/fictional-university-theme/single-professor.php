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
                    $likes = getProfessorLikes(get_the_ID());
                    $userLike = getCurrentUserLike($likes);
                    $status = $userLike ? 'yes' : 'no';
                    ?>
                    <span class="like-box"
                          data-like-id="<?php echo $userLike ? $userLike->ID : ''; ?>"
                          data-professor-id="<?php the_ID(); ?>"
                          data-exists="<?php echo $status; ?>"
                    >
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