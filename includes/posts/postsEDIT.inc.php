<?php

if($_SERVER["REQUEST_METHOD"] === "POST"){

    require_once "../env.inc.php";
    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];
    
    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        die();
    }
    
    require_once '../dbh.inc.php';
    require_once '../config_session.inc.php';
    require_once 'posts_modal.inc.php';
    require_once 'posts_contr.inc.php';

    // Problem : THE TOKEN ALWAYS MISMATCH
    /* if (isset($_SESSION["token"]) && isset($_POST["token"])) {
        echo $_SESSION["token"] . '<br>';
        echo $_POST["token"] . '<br>';
        if ($_SESSION["token"] == $_POST["token"]) {
            if (time() >= $_SESSION["token-expire"]) {
                echo "Token expired";
            }else{
                echo 'matched';
                unset($_SESSION ["token"]);
                unset($_SESSION["token-expire"]);
            }
        }else{
            echo 'mismatched';
        }
    }else{
        echo 'token not set';
    } */

    $user_name = $_SESSION["user_name"];
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $text = $_POST["text"];
    $post_img = $_FILES['post-img'];
    $post_old_img = $_POST['post_old_img'];
    
    $categs1 = array();
    if (isset($_POST['categs'])) {
        $categs1 = $_POST['categs'];
    }
    $categs2 = array();
    if (isset($_POST['new_categ_value'])) {
        $categs2 = $_POST['new_categ_value'];
    }
    
    try {
        
        $errors = [];
        
        if(is_inputs_empty_EDIT($title,$description,$text)){
            $errors["empty_inputs"] = "Fill in the required fields! Follow the instructions";
        }
        
        if (checkCategsNbr($categs1,$categs2)) {
            $errors["categs_error"] = "The categories are required! Follow the instructions";
        }
        if (checkCategs2Length($categs2)) {
            $errors["categs2_error"] = "Some new Categories are long";
        }
        
        if(is_id_notexist($pdo, $categs1)){
            $errors["dev_tools_err"] = "Stop messing with the devtools!!";;
        }
        
        if(!empty($title)){
            if(is_title_invalid($title)){
                $errors["title_invalid"] = "Title is invalid!";
            }
        }

        if(!empty($description)){
            if(is_description_invalid($description)){
                $errors["description_invalid"] = "Description is invalid!";
            }
        }

        if(!empty($text)){
            $textCheck = is_text_invalid($text);
            if($textCheck['status']){
                $errors["text"] = $textCheck['msg'];
            }
        }
        
        if(!checkUserHasPost($pdo,$post_id,$user_id)){
            $errors["user_post_own"] = "You can't update this post";
        }

        if (isset($post_img['name']) && is_uploaded_file($post_img['tmp_name'])) {
            $resImgCheck = is_img_invalid($post_img);
            if($resImgCheck['status']){
                $errors["img_invalid"] = $resImgCheck['msg'];
            }
        }

        if($errors){
           $_SESSION["register_errors_edit"] = $errors;
           $registerData = [
                "title" => $title,
                "description" => $description,
                "text" => $text,
                "categs1" => $categs1,
                "categs2" => $categs2
            ];
            
           $_SESSION["register_data_edit"] = $registerData;
           header("Location: ../../edit-post?post-id=$post_id");
           die();
        }

        $msgUpdate = update_post($pdo,$title,$description,$post_img,$text,$user_id,$post_id,$user_name,$post_old_img);
        $newCategIDs = array();
        $removedCategIDs = array();

        if(count($categs2) > 0){
            $categs2Un = array_unique($categs2);
            $categs2UnLwr = array_map('strtolower', $categs2Un);
            $dbCategs = get_categs_ids($pdo);

            foreach ($dbCategs as $dbCateg) {
                $dbCategName = strtolower($dbCateg['name']);
                if (in_array($dbCategName, $categs2UnLwr)) {
                    $removedCategIDs[] = $dbCateg['id'];
                    $categs2UnLwr = array_diff($categs2UnLwr, [$dbCategName]);
                }
            }
        
            foreach ($categs2UnLwr as $categ) {
                $newCategID = create_new_categ($pdo, $categ);
                $newCategIDs[] = $newCategID;
            }
        }
        $checkNbrCategNewAdded = array_unique(array_merge($newCategIDs, $removedCategIDs));
        $needTobeAdded = 4 - count($checkNbrCategNewAdded);
            
        $randomCategAdded = [];
        
        if ($needTobeAdded > 0) {
            if ($needTobeAdded > count($categs1)) {
                $randomCategAdded = $categs1;
            }else{
                $randomKeys = array_rand($categs1, $needTobeAdded);
                $randomCategAdded = array_intersect_key($categs1, array_flip($randomKeys));
            }
        }

        $combinedCategIDs = array_merge($randomCategAdded, $checkNbrCategNewAdded);
        $totalIdsCategsunique = array_unique($combinedCategIDs);
        assign_ids_post_edit($pdo,$post_id,$totalIdsCategsunique);

        header("Location: ../../singlepost?id=$post_id");
        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        $errMSG = $e->getMessage();
        echo $errMSG;
        header("Location: ../../edit-post?post-id=$post_id&error=$errMSG");
        die();
    }

}else{
    header("Location: ../../index");
    die();
}