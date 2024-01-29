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

    $email = $_POST['email'];
    $token = $_POST['remb-token'];
    $user_id = $_POST['user_id'];
    $name = $_POST['user_name'];

    try {

        require_once '../dbh.inc.php';
        require_once 'account_modal.inc.php';
        require_once 'account_contr.inc.php';

        //ERROR HANDLER
        $error = "";

        if (is_inputs_invalid($email, $token, $user_id, $name)) {
            $error = "Something Went wrong, try leter!";
        }

        if (!empty($error)) {
            setcookie("error", $error, time() + 60, "/");
            header("Location: ../../account");
            die();
        }

        update_sent_vef_at($pdo, $user_id);

        require_once "../glob_funcs.inc.php";
        $random_chars = bin2hex(random_bytes(300));
        $verificationLink = APP_URL . "/verify-email?token=$random_chars&uuid=$token";
        $msg = sendVerificationEmail($email, $verificationLink, strtoupper($name));
        setcookie("success", $msg, time() + 60, "/");
        header("Location: ../../account");
        die();
    } catch (PDOException $e) {
        die("Query Faild: " . $e->getMessage());
    }
} else {
    header("Location: ../../");
    die();
}
