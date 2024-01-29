<?php

declare(strict_types=1);
require_once "./includes/dbh.inc.php";
require_once "account_contr.inc.php";


$nbr_pending_posts = get_nbr_pen_posts($pdo, $_SESSION['user_id'])['nbr_pen_posts'];

if (isset($_GET['search']) && $_GET['search'] == 'archived') {
    $posts = get_posts_contr($pdo, $_SESSION['user_id'], 'accepted', true);
} elseif (isset($_GET['search']) && $_GET['search'] == 'pending') {
    $posts = get_posts_contr($pdo, $_SESSION['user_id'], 'pending');
} else {
    $posts = get_posts_contr($pdo, $_SESSION['user_id']);
}

function sendVerificationEmail($to, $verificationLink)
{
    $subject = 'Email Verification';
    $message = "Click on the link below to verify your email:\n\n$verificationLink";
    $headers = 'From: your_email@gmail.com';

    mail($to, $subject, $message, $headers);
}

function msg_operation()
{
    if (isset($_GET['success']) && !empty($_GET['success'])) {
        echo '<div class="alert alert-success" role="alert">' . $_GET['success'] . '</div>';
    } else if (isset($_GET['error']) && !empty($_GET['error'])) {
        echo '<div class="alert alert-danger alert-msg" role="alert">' . $_GET['error'] . '</div>';
    }
}

function print_navbar()
{
    echo '<div class="button-container">';
    echo '<a href="/account" class="button" data-toggle="tooltip" data-placement="top" title="Home">';
    echo '<i class="fas fa-home"></i>';
    echo '</a>';
    echo '<a href="/create-post" class="button" data-toggle="tooltip" data-placement="top" title="Create Post">';
    echo '<i class="fas fa-pen icon"></i>';
    echo '</a>';
    if (isset($_SESSION['isadmin']) && $_SESSION['isadmin']) {
        echo '<a href="/panel" class="button" data-toggle="tooltip" data-placement="top" title="Admin Panel">';
        echo '<i class="fas fa-user-cog"></i>';
        echo '</a>';
    }
    echo '<form action="./includes/login/logout.inc.php" method="post">';
    echo '<button class="button" type="submit" data-toggle="tooltip" data-placement="top" title="Logout">';
    echo '<i class="fas fa-sign-out-alt"></i>';
    echo '</button>';
    echo '</form>';
    echo '</div>';
}

function outputName()
{
    if (isset($_SESSION["user_id"])) {
        echo '<div class="card">';
        echo '<img src="./profile_images/' . $_SESSION["profile_img"] . '" alt="' . $_SESSION["user_name"] . '" style="width:100%">';
        echo '<h1>' . $_SESSION["user_name"] . '</h1>';
        echo '<p class="title">' . $_SESSION["user_email"];
        echo '<img src="./img/loading2.gif" id="loading-img-email" style="display:none" width="20px" alt="loading icon">';
        if ($_SESSION["email_verified_at"]) {
            echo ' <i class="fas fa-check-circle" data-toggle="tooltip" data-placement="top" title="Email Verified"></i>';
        } else {
            echo ' <i class="fas fa-exclamation-circle" data-toggle="tooltip" data-placement="top" title="Email Not Verified"></i>';
            echo '<form id="form-verify-email" action="./includes/account/account.inc.php" method="post">';
            echo '<input type="hidden" name="remb-token" value="' . $_SESSION["remember_token"] . '">';
            echo '<input type="hidden" name="email" value="' . $_SESSION["user_email"] . '">';
            echo '<input type="hidden" name="user_id" value="' . $_SESSION["user_id"] . '">';
            echo '<input type="hidden" name="user_name" value="' . $_SESSION["user_name"] . '">';
            echo '<input type="hidden" name="vef" value="' . (isset($_SESSION["email_verified_at"]) ? "1" : "0") . '">';
            echo '<button class="btn btn-outline-info btn-sm m-1">Verify Email</button>';
            echo '</form>';
        }
        echo '</p>';
        echo '<p>@' . $_SESSION["username"] . '</p>';
        echo '</div>';
    } else {
        echo "You are not logged in";
    }
}

function return_nbr_Pend_posts()
{
    global $nbr_pending_posts;
    return $nbr_pending_posts;
}

function mini_navbar_posts()
{
    global $nbr_pending_posts;
    echo '<nav class="navbar navbar-expand-sm  navbar-dark mt-2" style="background-color: #0cc;border-radius:6px">';
    echo '<div class="navbar-collapse">';
    echo '<ul class="navbar-nav mr-auto">';
    if (!isset($_GET['search']) || $_GET['search'] == '') {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="">Active Posts</a>';
    echo '</li>';
    if (isset($_GET['search']) && $_GET['search'] == 'archived') {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="?search=archived">Archived Posts</a>';
    echo '</li>';
    if (isset($_GET['search']) && $_GET['search'] == 'pending') {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="?search=pending">' . ($nbr_pending_posts > 0 ? $nbr_pending_posts : '') . ' Pending Posts</a>';
    echo '</ul></div></nav>';
}

function print_posts()
{
    global $posts;
    if (count($posts) > 0) {
        foreach ($posts as $p) {
            echo '<div class="post-container">';
            echo '<img src="./post_imgs/' . $p['post_img'] . '" width="300px" alt="Image of the post: ' . $p['title'] . '">';
            echo '<div class="post-content">';
            echo '<h3><a href="' . (isset($_GET['search']) && in_array($_GET['search'], ['pending', 'archived']) ? '#' : '/singlepost?id=' . $p['id']) . '" class="menu__link">' . $p['title'] . '</a></h3>';
            echo '<div class="buttons-post">';
            echo '<a href="edit-post?post-id=' . $p['id'] . '" class="menu__link">Edit</a>';
            echo '<div class="input-check-container">';
            echo '<label class="switch">';
            if (!$p['isarchived']) {
                echo '<input value="' . $p['id'] . '" type="checkbox" checked onchange="updateStatus(this)">';
                echo '<span class="slider"></span>';
                echo '</label>';
                echo '<span class="info-check">Active</span>';
            } else {
                echo '<input value="' . $p['id'] . '" type="checkbox" onchange="updateStatus(this)">';
                echo '<span class="slider"></span>';
                echo '</label>';
                echo '<span class="info-check">Archived</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else if (isset($_GET['search'])) {
        echo '<h2>You have no ' . $_GET['search'] . ' posts</h2>';
    } else {
        echo '<h2>There is no posts</h2>';
    }
}
