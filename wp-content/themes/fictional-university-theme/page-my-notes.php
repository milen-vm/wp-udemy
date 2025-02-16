<?php
if(!is_user_logged_in()) {
    wp_redirect(esc_url(wp_login_url()));
    exit;
}
get_header();
pageBanner();

while (have_posts()):
    the_post();
?>
    <div class="container container--narrow page-section">
        <ul id="my-notes" class="min-mist link-list">
            <?php
            $userNotes = new WP_Query([
                'post_type' => 'note',
                'post_per_page' => -1,      // returns all records in db
                'author' => get_current_user_id(),
            ]);

            while($userNotes->have_posts()) :
                $userNotes->the_post();
            ?>
                <li>
                    <input class="note-title-field" type="text" value="<?php echo esc_attr(get_the_title()); ?>">
                    <span class="edit-note">
                        <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                    </span>
                    <span class="delete-note">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                    </span>
                    <!-- Because the note content is seved in db like html format wp_strip_all_tags removes all tags. -->
                    <textarea class="note-body-field" name="" id=""><?php echo esc_attr(wp_strip_all_tags(get_the_content())); ?></textarea>
                </li>
            <?php
            endwhile;
            ?>
        </ul>
    </div>

<?php endwhile ?>

<?php get_footer(); ?>