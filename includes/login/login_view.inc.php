<?php

declare(strict_types=1);

function check_login_errors(){
    if(isset($_SESSION["errors_login"])){
        $errors = $_SESSION["errors_login"];

        echo '<div class="alert-msg-container">';
        foreach($errors as $error){
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';

        unset($_SESSION["errors_login"]);
    }
    else if(isset($_GET['error-createpost'])){
        echo '<div class="alert-msg-container">';
        echo '<div class="alert alert-danger" role="alert">Login is required to create a post!</div>';
        echo '</div>';
    }else if(isset($_GET['error-cmnt'])){
        echo '<div class="alert-msg-container">';
        echo '<div class="alert alert-danger" role="alert">Login is required to comment!</div>';
        echo '</div>';
    }
    unset($_SESSION["register_errors"]);
}