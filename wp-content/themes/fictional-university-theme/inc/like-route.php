<?php

function universityLikeRoutes(): void
{
    register_rest_route('university/v1', 'manageLike', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'createLike',
        'permission_callback' => '__return_true',   // TODO setup permission
    ]);
    register_rest_route('university/v1', 'manageLike', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'deleteLike',
        'permission_callback' => '__return_true',   // TODO setup permission
    ]);
}

function createLike($data): array|WP_Error
{
    // the user is logged in when a NONCE code is sent in headers
    if(!is_user_logged_in()) {
        exit('Only logged in users ca create a like.');
    }

    if(!isset($data['professorId'])) {
        exit('Missing data.');
    }

    $professorId = (int) sanitize_text_field($data['professorId']);

    if(get_post_type($professorId) !== 'professor') {
        exit('Invalid professor Id.');
    }

    $likes = getProfessorLikes($professorId);
    $status = getCurrentUserLike($likes) ? true : false;

    if($status) {
        exit('You already create a like.');
    }

    $id = wp_insert_post([
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => 'create post',      // title and content are not needed
        'meta_input' => [
            'liked_professor_id' => $professorId,
        ],
        // 'post_content' => '123',    // the content is not visible if editor is not enablet in university-post-type.php - post registration
    ]);

    $count = $id ? $likes->post_count + 1 : $likes->post_count;

    return [
        'id' => $id,
        'count' => $count,
    ];
}

function deleteLike($data): array|WP_Error
{
    // the user is logged in when a NONCE code is sent in headers
    if(!is_user_logged_in()) {
        exit('Only logged in users ca create a like.');
    }

    if(!isset($data['likeId'])) {
        exit('Missing data.');
    }

    $likeId = sanitize_text_field($data['likeId']);
    if(get_current_user_id() !== (int) get_post_field('post_author', $likeId) ||
        get_post_type($likeId) !== 'like'
    ) {
        exit('Invalid like post.');
    }

    $professorId = get_field('liked_professor_id', $likeId);
    wp_delete_post($likeId);
    $count = (getProfessorLikes($professorId))->post_count;

    return [
        'count' => $count,
    ];
}

add_action('rest_api_init', 'universityLikeRoutes');
