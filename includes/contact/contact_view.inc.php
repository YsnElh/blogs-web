<?php

declare(strict_types=1);

function check_register_errors()
{

    if (isset($_SESSION["register_errors"])) {
        $errors = $_SESSION["register_errors"];
        echo '<div class="alert-msg-container">';
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
    } elseif (isset($_GET['success']) && !empty($_GET['success'])) {
        echo '<div class="alert alert-success single-alert">' . $_GET['success'] . '</div>';
    }
}

function registeredName()
{
    if (isset($_SESSION["register_data"]['name'])) {
        echo htmlspecialchars($_SESSION["register_data"]['name']);
    }
}
function registeredEmail()
{
    if (isset($_SESSION["register_data"]['email'])) {
        echo htmlspecialchars($_SESSION["register_data"]['email']);
    }
}
function registeredSub()
{
    if (isset($_SESSION["register_data"]['subject'])) {
        echo htmlspecialchars($_SESSION["register_data"]['subject']);
    }
}
function registeredMsg()
{
    if (isset($_SESSION["register_data"]['message'])) {
        echo htmlspecialchars($_SESSION["register_data"]['message']);
    }
}


function unsetSessVars()
{
    unset($_SESSION["register_errors"], $_SESSION["register_data"]);
}
