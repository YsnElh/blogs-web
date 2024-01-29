<?php

declare(strict_types=1);

function get_single_post(object $pdo, int $id)
{
    return get_post($pdo, $id);
}

function get_comments(object $pdo, int $id)
{
    return get_post_comments($pdo, $id);
}
function is_id_exist(object $pdo, int $id_check)
{
    return get_postsIDS($pdo, $id_check);
}

function get_categories(object $pdo)
{
    return get_6categories($pdo);
}

function get_related_posts(object $pdo, int $id)
{
    return get_3related_posts($pdo, $id);
}

function is_inputs_empty(string $cmnt, string $postid)
{
    return empty($cmnt) || empty($postid);
}


function is_comment_long(string $cmnt)
{
    return !preg_match('/^.{1,500}$/', $cmnt);
}
function add_comment(object $pdo, int $userID, int $postID, string $cmnt)
{
    insert_comment($pdo, $userID, $postID, $cmnt);
}
