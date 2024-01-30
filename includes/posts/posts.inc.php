<?php

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

    require_once '../dbh.inc.php';
    require_once 'posts_modal.inc.php';
    require_once 'posts_contr.inc.php';

    $title = $_POST["title"];

    $description = $_POST["description"];

    $text = $_POST["text"];

    $post_img = $_FILES['post-img'];

    if (isset($_POST['categs'])) {
        $categs1 = $_POST['categs'];
    } else {
        $categs1 = [];
    }

    if (isset($_POST['new_categ_value'])) {
        $categs2 = $_POST['new_categ_value'];
    } else {
        $categs2 = [];
    }

    try {

        $errors = [];

        if (is_inputs_empty($title, $description, $text, $post_img)) {
            $errors["empty_inputs"] = "Fill in the required fields! Follow the instructions";
        }

        if (checkCategsNbr($categs1, $categs2)) {
            $errors["categs_error"] = "The categories are required! Follow the instructions";
        }
        if (checkCategs2Length($categs2)) {
            $errors["categs2_error"] = "Some new Categories are long";
        }

        if (is_id_notexist($pdo, $categs1)) {
            $errors["dev_tools_err"] = "Stop messing with the devtools!!";;
        }

        if (!empty($title)) {
            if (is_title_invalid($title)) {
                $errors["title_invalid"] = "Title is invalid!";
            }
        }

        if (!empty($description)) {
            if (is_description_invalid($description)) {
                $errors["description_invalid"] = "Description is invalid!";
            }
        }

        if (!empty($text)) {
            $textCheck = is_text_invalid($text);
            if ($textCheck['status']) {
                $errors["text"] = $textCheck['msg'];
            }
        }

        if (!empty($post_img)) {
            $resImgCheck = is_img_invalid($post_img);
            if ($resImgCheck['status']) {
                $errors["img_invalid"] = $resImgCheck['msg'];
            }
        }

        require_once '../config_session.inc.php';

        if ($errors) {
            $_SESSION["register_errors"] = $errors;
            $registerData = [
                "title" => $title,
                "description" => $description,
                "text" => $text,
                "categs1" => $categs1,
                "categs2" => $categs2
            ];

            $_SESSION["register_data"] = $registerData;
            header("Location: ../../create-post");
            die();
        }
        if ($_SESSION["user_id"] && $_SESSION["user_name"]) {
            $user_id = $_SESSION["user_id"];
            $user_name = $_SESSION["user_name"];
        } else {
            header("Location: ../../create-post?error=session problem");
            die();
        }

        $newPostID = create_post($pdo, $title, $description, $post_img, $text, $user_id, $user_name);
        $newCategIDs = array();
        $removedCategIDs = array();

        if (count($categs2) > 0) {
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
            } else {
                $randomKeys = array_rand($categs1, $needTobeAdded);
                $randomCategAdded = array_intersect_key($categs1, array_flip($randomKeys));
            }
        }

        $combinedCategIDs = array_merge($randomCategAdded, $checkNbrCategNewAdded);
        $totalIdsCategsunique = array_unique($combinedCategIDs);
        assign_ids_post($pdo, $newPostID, $totalIdsCategsunique);

        header("Location: ../../singlepost?id=$newPostID");
        $pdo = null;
        $stmt = null;
        die();
    } catch (PDOException $e) {
        $errMSG = $e->getMessage();
        echo $errMSG;
        //header("Location: ../../create-post?error=$errMSG");
        die();
    }
} else {
    header("Location: ../../index");
    die();
}
