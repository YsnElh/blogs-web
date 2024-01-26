<?php

function formatDateTime($dt){
    if (!$dt) {
        return "Not Set";
    }
    $dateTime  = new DateTime($dt);
    return $dateTime->format('j F Y, g:i A');
}
//SPP: Single Pending Post
require_once "./includes/dbh.inc.php";
require_once 'panel_modal.inc.php';
require_once 'panel_contr.inc.php';

$id = $_GET['id'] ?? null;
if (!is_numeric($id) || empty($id)) {
    $pdo = null;
    $stmt = null;
    header("Location: ../../panel?manage-posts");
    die();
}

$post = [];
if ($id) {
    $post = get_single_pen_post($pdo,intval($id));
}
function print_single_pen_post(){
    global $post;
    if (count($post) > 0) {
        echo '<div>';
        foreach ($post as $p) {
            echo '<h2 class="tm-color-primary">'.$p['title'].'</h2>';
            echo '<img src="./post_imgs/'.$p['post_img'].'" class="val-post-img" alt="'.$p['title'].' post image">';
            echo '<p><b>Description: </b>'.$p['description'].'</p>';
            echo '<p><b>Text: </b>'.$p['text'].'</p>';
            echo '<p><b>Date Creation: </b>'.formatDateTime($p['created_at']).'</p>';
            echo '<p><b>Last Update Date: </b>'.formatDateTime($p['updated_at']).'</p>';
            echo '<p><b>User Name: </b>'.$p['user_name'].'</p>';
            echo '<p><b>User Email: </b>'.$p['user_email'].'</p>';
            echo '<form action="./includes/panel/panel.inc.php" method="POST">';
            echo '<input type="hidden" name="post-id" value="'.$p['id'].'">';
            echo '<input type="hidden" name="form-name" value="approve-post">';
            echo '<button class="btn btn-outline-info m-1">Approve</button>';
            echo '</form>';
            echo '<form action="./includes/panel/panel.inc.php" method="POST">';
            echo '<input type="hidden" name="post-id" value="'.$p['id'].'">';
            echo '<input type="hidden" name="form-name" value="del-post">';
            echo '<button class="btn btn-outline-danger m-1">Delete</button>';
            echo '</form>';
        }
        echo '</div>';

    }else{
        $pdo = null;
        $stmt = null;
        header("Location: ../../panel?manage-posts");
        die();
    }
}