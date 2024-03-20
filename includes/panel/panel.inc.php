<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Origin request check
    require_once "../env.inc.php";
    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];

    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        die();
    }
    if (!isset($_POST["form-name"]) && empty($_POST["form-name"])) {
        header("Location: ../../panel");
        die();
    }
    $form_names_allowed = ['update-role', 'approve-post', 'del-post', 'approve-categ', 'del-categ', 'del-msg'];
    $form_name = $_POST["form-name"];

    if (!in_array($form_name, $form_names_allowed)) {
        header("Location: ../../");
        die();
    }
    require_once '../config_session.inc.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['isadmin'] == 0) {
        header("Location: /account");
        die();
    }
    require_once '../dbh.inc.php';
    require_once 'panel_modal.inc.php';
    require_once 'panel_contr.inc.php';

    switch ($form_name) {
        case "update-role":
            $user_id = $_POST['user-id'];
            $role = $_POST['user-role'];
            $roles_allowed = ['user', 'admin'];

            $errors = [];

            if (is_inputs_invalid($user_id, $role, $roles_allowed)) {
                $errors["error"] = "Something went Wrong";
            }

            if ($errors) {
                $_SESSION["register_errors"] = $errors;
                header("Location: ../../panel");
                die();
            }

            change_role($pdo, $user_id, $role);
            header("Location: ../../panel?success=User $user_id is now $role");
            $pdo = null;
            $stmt = null;
            die();
            break;
        case "approve-categ":
            $categ_id = $_POST['categ-id'];
            if (!isset($categ_id) || empty($categ_id) || !is_numeric($categ_id)) {
                $_SESSION["register_errors"] = "Something went wrong, try later";
                header("Location: ../../panel?manage-posts");
                die();
            }

            change_categ($pdo, intval($categ_id), "update");
            $pdo = null;
            $stmt = null;
            header("Location: ../../panel?manage-posts");
            die();
            break;
        case "del-categ":
            $categ_id = $_POST['categ-id'];
            if (!isset($categ_id) || empty($categ_id) || !is_numeric($categ_id)) {
                $_SESSION["register_errors"] = "Something went wrong, try later";
                header("Location: ../../panel?manage-posts");
                die();
            }

            change_categ($pdo, intval($categ_id), "del");
            $pdo = null;
            $stmt = null;
            header("Location: ../../panel?manage-posts");
            die();
            break;
        case "approve-post":
            $post_id = $_POST['post-id'];
            if (!isset($post_id) || empty($post_id) || !is_numeric($post_id)) {
                $_SESSION["register_errors"] = "Something went wrong, try later";
                header("Location: ../../panel?manage-posts");
                die();
            }
            change_post_adm($pdo, intval($post_id), "update");
            $pdo = null;
            $stmt = null;
            header("Location: ../../panel?manage-posts");
            die();
            break;
        case "del-post":
            $post_id = $_POST['post-id'];
            if (!isset($post_id) || empty($post_id) || !is_numeric($post_id)) {
                $_SESSION["register_errors"] = "Something went wrong, try later";
                header("Location: ../../panel?manage-posts");
                die();
            }
            change_post_adm($pdo, intval($post_id), "del");
            $pdo = null;
            $stmt = null;
            header("Location: ../../panel?manage-posts");
            die();
            break;
        case "del-msg":
            $msg_id = $_POST['id_msg'];
            if (!isset($msg_id) || empty($msg_id) || !is_numeric($msg_id)) {
                $_SESSION["register_errors"] = "Something went wrong, try later";
                header("Location: ../../panel?users-msgs");
                die();
            }
            deleteUserMsg($pdo, $msg_id);
            $pdo = null;
            $stmt = null;
            header("Location: ../../panel?users-msgs");
            die();
            break;
    }
} else {
    header("Location: ../../");
    die();
}
