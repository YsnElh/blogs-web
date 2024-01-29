<?php

declare(strict_types=1);

function generateCSRFToken()
{
    $_SESSION["token-expire"] = time() + 3600;
    $_SESSION['_token'] = bin2hex(random_bytes(32));
    return $_SESSION['_token'];
}


function check_register_errors()
{

    if (isset($_SESSION["register_errors"])) {
        $errors = $_SESSION["register_errors"];
        echo '<div class="alert-msg-container">';
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
    }
}

function registeredInputs()
{
    // HANDLING NAME
    //echo '<input type="hidden" name="_token" value="' . generateCSRFToken() . '">';
    if (isset($_SESSION["register_data"]['name'])) {
        echo '<div class="form-group row mb-4">';
        echo '<label for="name" id="name-label" class="col-sm-3 col-form-label tm-color-primary">Name*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="name" id="name" type="text" value="' . $_SESSION["register_data"]['name'] . '" autocomplete="name"  required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•Only alphabetical characters are allowed.•Name max size is 30 characters."></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="form-group row mb-4">';
        echo '<label for="name" id="name-label" class="col-sm-3 col-form-label tm-color-primary">Name*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="name" id="name" type="text" autocomplete="name" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•Only alphabetical characters are allowed.•Name max size is 30 characters."></i>';
        echo '</div>';
        echo '<div class=" error" id="error-name-message"></div>';
        echo '</div>';
        echo '</div>';
    }

    // HANDLING Email
    if (isset($_SESSION["register_data"]['email'])) {
        echo '<div class="form-group row mb-4">';
        echo '<label for="email" id="email-label" class="col-sm-3 col-form-label tm-color-primary">Email*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="email" id="email" type="email" value="' . $_SESSION["register_data"]['email'] . '" autocomplete="email" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•Must not start or end with a space. •Should not contain spaces. •Must contain the `@` symbol. •Must have a domain after the `@` symbol. •The domain should contain at least one `.` (dot)."></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="form-group row mb-4">';
        echo '<label for="email" id="email-label" class="col-sm-3 col-form-label tm-color-primary">Email*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="email" id="email" type="email" autocomplete="email" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•Must not start or end with a space. •Should not contain spaces. •Must contain the `@` symbol. •Must have a domain after the `@` symbol. •The domain should contain at least one `.` (dot)."></i>';
        echo '</div>';
        echo '<div class="error" id="error-email-message"></div>';
        echo '</div>';
        echo '</div>';
    }

    // HANDLING username
    if (isset($_SESSION["register_data"]['username'])) {
        echo '<div class="form-group row mb-4">';
        echo '<label for="username" id="username-label" class="col-sm-3 col-form-label tm-color-primary">username*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="username" id="username" type="text" value="' . $_SESSION["register_data"]['username'] . '" autocomplete="username" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•The length must be bigger than 5. •Uppercase letters are not allowed. •you can use lowercase letters, underscores, dots and numbers. •Should not start or end with a dot(.)."></i>';
        echo '</div>';
        echo '<div class="error" id="error-username-message"></div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="form-group row mb-4">';
        echo '<label for="username" id="username-label" class="col-sm-3 col-form-label tm-color-primary">username*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="username" id="username" type="text" autocomplete="username" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•The length must be bigger than 5. •Uppercase letters are not allowed. •you can use lowercase letters, underscores, dots and numbers. •Should not start or end with a dot(.)."></i>';
        echo '</div>';
        echo '<div class="error" id="error-username-message"></div>';
        echo '</div>';
        echo '</div>';
    }
    unset($_SESSION["register_errors"]);
    unset($_SESSION["register_data"]);
}
