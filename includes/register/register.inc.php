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
    //if (isset($_POST['csrf-token']) && ($_POST['csrf-token'] === $_SESSION['csrf_token'])) {

    $csrftoken = $_POST['csrf-token'];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (!empty($_FILES['profile-pic']['name'])) {
        $profile_pic = $_FILES['profile-pic'];
    }
        
    try {
            
        require_once '../dbh.inc.php';
        require_once 'register_modal.inc.php';
        require_once 'register_contr.inc.php';
        
        //ERROR HANDLER
        $errors = [];
        
        if(is_inputs_empty($name,$email,$password,$username)){
            $errors["empty_inputs"] = "Fill in the required fields (*)!";
        }
        if(!empty($name)){
            if(is_name_invalid($name)){
                $errors["name_invalid"] = "Name is invalid!";
            }
        }
        
        if(!empty($email)){
            if(is_email_invalid($email)){
                $errors["invalid_email"] = "Invalid Email used!";
            }
        }
        

        if(is_username_invalid($username)){
            $errors["username_invalid"] = "username invalid!";
        }
        
        if(is_username_taken($pdo,$username)){
            $errors["username_taken"] = "username already taken!";
        }
        
        if(is_email_registred($pdo,$email)){
            $errors["email_registred"] = "Email already registred!";
        }
        
        if (!empty($profile_pic)) {
            if(is_img_invalid($profile_pic)){
                $errors["img_invalid"] = "Image is Invalid";
            }
        }
        
        if(!empty($password)){
            if(is_password_invalid($password)){
                $errors["password_invalid"] = "Password is weak!";
            }
        }

        require_once '../config_session.inc.php';
        
        // if (isset($csrftoken) && ($csrftoken != $_SESSION['csrf_token'])) {
        //     $errors["csrf_error"] = "CSRF token match failed!";   
        // }
        // unset($_SESSION['csrf_token']);
        
        if($errors){
           $_SESSION["register_errors"] = $errors;
           $registerData = [
               "name" => $name,
            "email" => $email,
            "username" => $username
        ];
           $_SESSION["register_data"] = $registerData;
           header("Location: ../../register");

           die();
        }
        // INSERT NEW USER
        
        $newUser = create_user($pdo, $name, $username, $email, $password, $profile_pic);
        
        // handle session ID
        
        $newSessionID = session_create_id();
        $sessionID = $newSessionID . "_" . $newUser['id'];
        session_id($sessionID);
        
        $_SESSION['user_id'] = $newUser['id'];
        $_SESSION['user_name'] = htmlspecialchars($newUser['name']);
        $_SESSION['username'] = htmlspecialchars($newUser['username']);
        $_SESSION['user_email'] = $newUser['email'];
        $_SESSION['profile_img'] = $newUser['profile_photo_path'];
        $_SESSION['password'] = $newUser['password'];
        $_SESSION['isadmin'] = $newUser['isadmin'];
        
        $_SESSION['last_regeneration'] = time();
        
        header("Location: ../../account?register=success");
        $pdo = null;
        $stmt = null;
        die();
        
    } catch (PDOException $e) {
        die("Query Faild: " . $e->getMessage());
    }

}else{
    header("Location: ../../index");
    die();
}