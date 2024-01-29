<?php

require_once '../config_session.inc.php';
require_once '../dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once "../env.inc.php";
    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];

    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        die();
    }

    require_once 'comments-modal.inc.php';
    require_once 'comments_contr.inc.php';

    $post_id = $_SESSION['post_id'];
    if (isset($_POST['comment-id']) && isset($_SESSION['post_id']) && isset($_POST['user-id']) && ($_SESSION['user_id'] === intval($_POST['user-id']))) {
        try {
            $comment_id = $_POST["comment-id"];
            delete_user_comment($pdo, intval($comment_id), $_SESSION['user_id'], intval($post_id));
            header("Location: ../../singlepost?id=$post_id&del-cmnt-success=Comment Deleted");
            die();
        } catch (PDOException $e) {
            header('Location: ../../singlepost?id=' . $post_id . '&error-del-cmnt=something went wrong! Try again');
            die();
        }
    } else {
        header("Location: ../../singlepost?id=$post_id&error-del-cmnt=something went wrong!");
        die();
    }
} else {
    header("Location: ../../index");
    die();
}
unset($_SESSION['post_id']);
