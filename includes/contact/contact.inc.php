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
    require_once '../config_session.inc.php';

    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    try {

        require_once '../dbh.inc.php';
        require_once 'contact_modal.inc.php';
        require_once 'contact_contr.inc.php';

        //ERROR HANDLER
        $errors = [];

        if (is_inputs_empty($name, $email, $subject, $message)) {
            $errors["empty_inputs"] = "Fill in the required fields (*)!";
        }
        if (!empty($name)) {
            if (is_name_invalid($name)) {
                $errors["name_invalid"] = "Name is invalid!";
            }
        }
        if (!empty($email)) {
            if (is_email_invalid($email)) {
                $errors["invalid_email"] = "Invalid Email used!";
            }
        }
        if (!empty($subject)) {
            if (is_subject_invalid($subject)) {
                $errors["subject_long"] = "Subject is long";
            }
        }
        if (!empty($message)) {
            if (is_message_invalid($message)) {
                $errors["message_lonk"] = "The message is long";
            }
        }

        if ($errors) {
            $_SESSION["register_errors"] = $errors;
            $registerData = [
                "name" => $name,
                "email" => $email,
                "subject" => $subject,
                "message" => $message,
            ];
            $_SESSION["register_data"] = $registerData;
            header("Location: ../../contact");
            die();
        }

        create_msg($pdo, $name, $email, $subject, $message);
        header("Location: ../../contact?success=Message Sent");
        $pdo = null;
        $stmt = null;
        die();
    } catch (PDOException $e) {
        die("Query Faild: " . $e->getMessage());
    }
} else {
    header("Location: ../../index");
    die();
}
