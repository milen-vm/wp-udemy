<?php
/**
 * CUSTOM APPLICATION FUNCTIONS
 */

 function get_page_ID_by_slug(string $slug): int|null
 {
     $page = get_page_by_path($slug);
     if($page) {
 
         return (int) $page->ID;
     }
 
     return null;
 }

 /**
 * Page banner template.
 * 
 * @return void
 */
function pageBanner(string $title = '', string $subtitle = '', string $background = ''): void
{
    if(empty($background)) {
        $image = get_field('page_banner_background_image');

        if(
            !is_archive() &&
            !is_home() &&
            is_array($image) &&
            isset($image['sizes']) &&
            $image['sizes']['pageBanner']
        ) {
            $background = $image['sizes']['pageBanner'];
        } else {
            $background = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    if(empty($title)) {
        $title = get_the_title();
    }

    if(empty($subtitle)) {
        $subtitle = get_field('page_banner_subtitle');
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
            style="background-image: url(<?php echo $background; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $title; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $subtitle; ?></p>
            </div>
        </div>
    </div>
<?php
}

/**
 * Get all likes for the current professor and check
 * is logged user already has created a like.
 * 
 * @param int $professorId
 * @return array<WP_Query|bool>
 */
function userProfessorLikes(int $professorId): array
{
    $likes = new WP_Query([
        'post_type' => 'like',
        'meta_query' => [
            [
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => $professorId,
            ]
        ]
    ]);

    $status = false;
    foreach($likes->posts as $like) {
        $authorId = (int) $like->post_author;
        if($authorId === get_current_user_id()) {
            $status = true;
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

    return [$likes, $status];
}
/**
 * Get professor likes
 * 
 * @param mixed $professorId
 * @return WP_Query
 */
function getProfessorLikes($professorId): WP_Query
{
    $likes = new WP_Query([
        'post_type' => 'like',
        'meta_query' => [
            [
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => $professorId,
            ]
        ]
    ]);

    return $likes;
}

/**
 * Get current user like.
 * 
 * @param mixed $likes
 */
function getCurrentUserLike($likes)
{
    $userId = get_current_user_id();
    $userLike = null;

    foreach($likes->posts as $like) {
        $authorId = (int) $like->post_author;
        if($authorId === $userId) {
            $userLike = $like;
        }
    }

    return $userLike;
}
