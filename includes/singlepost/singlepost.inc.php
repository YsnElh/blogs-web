<?php

require_once '../config_session.inc.php';

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_id"])){
    require_once "../env.inc.php";
    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];
    
    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        die();
    }
    $comment = $_POST["comment"];
    $post_id = $_POST["post-id"];
    $user_id = $_SESSION["user_id"];
    try {
        require_once '../dbh.inc.php';
        require_once 'singlepost_modal.inc.php';
        require_once 'singlepost_contr.inc.php';

        $errors = [];

        if(is_inputs_empty($comment,$post_id)){
            $errors["emptys_inputs"] = "Something went wrong, try again!";
        }

        if(is_comment_long($comment)){
            $errors["comment_long"] = "Comment is long!";
        }

        if($errors){
           $_SESSION["register_errors"] = $errors;
           $registerd_cmnt = $comment;
           $_SESSION["registerd_cmnt"] = $registerd_cmnt;
           header("Location: ../../singlepost?id=$post_id#comments-sec");
           die();
        }
        
        add_comment($pdo,$user_id,intval($post_id),$comment);
        header("Location: ../../singlepost?id=$post_id#comments-sec");
        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        die("Query Faild: " . $e->getMessage());
    }

}else{
    header("Location: ../../login?error-cmnt");
    die();
}