<?php

declare(strict_types=1);

require_once './includes/dbh.inc.php';
require_once 'singlepost_modal.inc.php';
require_once 'singlepost_contr.inc.php';


if (!isset($_GET['id'])) {
    header("Location: ../../index");
    die();
}

$res = is_id_exist($pdo, intval($_GET['id']));

if (isset($_GET['id']) && !$res) {
    header("Location: ../../index");
    die();
}

$single_post = get_single_post($pdo, intval($_GET['id']));
$comments = get_comments($pdo, intval($_GET['id']));
$categories = get_categories($pdo);
$realted_posts = get_related_posts($pdo, intval($_GET['id']));

function return_id()
{
    global $single_post;
    foreach ($single_post as $post) {
        echo $post['id'];
    }
}
function print_title()
{
    global $single_post;
    foreach ($single_post as $post) {
        echo $post['title'];
    }
}
function print_img()
{
    global $single_post;
    foreach ($single_post as $post) {
        echo '<img src="./post_imgs/' . $post['post_img'] . '" width="800px" height="400px" alt="Image of: ' . $post['title'] . ' blog">';
    }
}

function print_post()
{
    global $single_post;
    foreach ($single_post as $post) {
        echo '<div class="mb-4">';
        echo '<h2 class="pt-2 tm-color-primary tm-post-title">' . $post['title'] . '</h2>';
        $created_at = $post['created_at'];
        $date = new DateTime($created_at);
        $formatted_date = $date->format("F j, Y");
        echo '<p class="tm-mb-40">' . $formatted_date . ' posted by ' . $post['author_name'] . '</p>';
        echo '<p>' . $post['description'] . '</p>';
        echo '<p>' . nl2br($post['text']) . '</p>';

        if (isset($post['category_names'])) {
            $post['category_names'] = explode(',', $post['category_names']);
            echo '<span class="d-block text-right tm-color-primary">';
            foreach ($post['category_names'] as $categ) {
                echo $categ . '. ';
            }
            echo '</span>';
        }
        echo '</div>';
    }
}

function print_comments()
{
    global $comments;
    $cpt = 0;
    if (isset($_GET['nbr-cmnts'])) {
        $nbr_cmnts = $_GET['nbr-cmnts'] + 5;
    } else {
        $nbr_cmnts = 5;
    }
    if (count($comments) > 0) {
        foreach ($comments as $cmnt) {
            $cpt += 1;
            if ($cpt === $nbr_cmnts - 4) {
                echo '<div id="last-cmnt" class="tm-comment tm-mb-45">';
            } else {
                echo '<div class="tm-comment tm-mb-45">';
            }
            echo '<figure class="tm-comment-figure" title="' . $cmnt['name'] . '">';
            echo '<img src="./profile_images/' . $cmnt['profile_photo_path'] . '" width="110px" alt="profile image Of: ' . $cmnt['name'] . '" class="mb-2 rounded-circle img-thumbnail">';
            echo '<figcaption class="tm-color-primary text-center">' . $cmnt['username'] . '</figcaption>';
            echo '</figure>';
            echo '<div>';
            echo '<p>' . $cmnt['content'] . '</p>';
            echo '<div class="d-flex justify-content-between">';
            $created_at = $cmnt['created_at'];
            $date = new DateTime($created_at);
            $formatted_date = $date->format("F j, Y");
            echo '<span class="tm-color-primary">' . $formatted_date . '</span>';
            if (isset($_SESSION['user_id']) && ($cmnt['user_id'] == $_SESSION['user_id'])) {
                echo '<form action="/includes/comments/comments.inc.php" method="post">';
                echo '<input type="hidden" name="comment-id" value="' . $cmnt['id'] . '">';
                echo '<input type="hidden" name="user-id" value="' . $cmnt['user_id'] . '">';
                echo '<button class="btn btn-outline-danger btn-sm">Delete</button>';
                echo '</form>';
                $_SESSION['post_id'] = $cmnt['post_id'];
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<hr>';
            if ($cpt === $nbr_cmnts) {
                break;
            }
        }
        if (count($comments) > $nbr_cmnts) {
            echo '<a href="?id=' . $cmnt['post_id'] . '&nbr-cmnts=' . $nbr_cmnts . '#last-cmnt" class="btn btn-outline-info">Load more comments</a>';
        }
    } else {
        echo '<h4>There is no comments Yet, Be the First one to comment this post!</h4>';
    }
}

function print_categs()
{
    global $categories;
    foreach ($categories as $categ) {
        echo '<li><a href="/posts?categ=' . $categ['name'] . '" class="tm-color-primary">' . $categ['name'] . '</a></li>';
    }
}
function print_realted_posts()
{
    global $realted_posts;
    $posts_uniq = array_map(
        'unserialize',
        array_unique(array_map('serialize', $realted_posts))
    );
    foreach ($posts_uniq as $relpost) {
        echo '<a href="/singlepost?id=' . $relpost['id'] . '" class="d-block tm-mb-40">';
        echo '<figure>';
        echo '<img src="./post_imgs/' . $relpost['post_img'] . '" alt="Image of: ' . $relpost['title'] . '" class="mb-3 img-fluid">';
        echo '<figcaption class="tm-color-primary">' . $relpost['title'] . '</figcaption>';
        echo '</figure>';
        echo '</a>';
    }
}

function check_register_errors()
{
    if (isset($_SESSION["register_errors"])) {
        $errors = $_SESSION["register_errors"];
        //var_dump($errors);
        echo '<div class="alert-msg-container">';
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
    }
}
function registred_cmnt()
{
    if (isset($_SESSION["register_errors"]) && isset($_SESSION["registerd_cmnt"])) {
        echo '<textarea class="form-control" name="comment" rows="6" required>' . $_SESSION["registerd_cmnt"] . '</textarea>';
    } else {
        echo '<textarea class="form-control" name="comment" rows="6" required></textarea>';
    }
    unset($_SESSION["register_errors"]);
    unset($_SESSION["registerd_cmnt"]);
}
function check_cmnt_delError()
{
    if (isset($_GET['error-del-cmnt'])) {
        echo '<div class="alert alert-danger alert-msg">' . $_GET['error-del-cmnt'] . '</div>';
    } elseif (isset($_GET['del-cmnt-success'])) {
        echo '<div class="alert alert-success" role="alert">' . $_GET['del-cmnt-success'] . '</div>';
    }
}
