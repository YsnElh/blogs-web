<?php

declare(strict_types=1);

function is_inputs_empty(string $name, string $email, string $pwd, string $username)
{
    if (empty($name) || empty($email) || empty($pwd) || empty($username)) {
        return true;
    } else {
        return false;
    }
}

function is_name_invalid(string $name)
{
    $regex = '/^[a-zA-Z\x{00C0}-\x{00FF}\s]{1,30}$/u';
    return !preg_match($regex, $name) || strlen($name) > 30;
}

function is_username_invalid(string $username)
{
    $regex = '/^(?!\.)(?!.*\.$)(?=.*[a-z].*[a-z])[a-z0-9._]{5,20}$/';
    return !preg_match($regex, $username);
}

function is_username_taken(object $pdo, string $username)
{
    if (get_username($pdo, $username)) {
        return true;
    } else {
        return false;
    }
}

function is_email_invalid(string $email)
{

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}

function is_email_registred(object $pdo, string $email)
{
    if (get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
}

function is_img_invalid($img)
{
    if (is_uploaded_file($img['tmp_name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return true;
        }

        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if ($img['size'] > $maxFileSize) {
            return true;
        }
        $maxDimension = 3000;
        $imgInfo = getimagesize($img['tmp_name']);
        if ($imgInfo === false || $imgInfo[0] > $maxDimension || $imgInfo[1] > $maxDimension || $imgInfo[0] !== $imgInfo[1]) {
            return true;
        }

        return false;
    } else {
        return true;
    }
}

function is_input_img_empty($profile_pic)
{
    if (empty($profile_pic)) {
        return true;
    } else {
        return false;
    }
}

function is_password_invalid(string $pwd)
{

    $regex = '/^(?=.*[A-Za-z])(?=.*\d).{8,}$/';
    return !preg_match($regex, $pwd);
}

function create_user(object $pdo, string $name, string $username, string $email, string $pwd, $img)
{
    return set_user($pdo, $name, $username, $email, $pwd, handleImage($img, $name));
}

function handleImage($img, $name): string
{
    $mainDirectory = '../../';
    $imageFolder = $mainDirectory . 'profile_images/';
    if (!file_exists($imageFolder)) {
        mkdir($imageFolder, 0755, true);

        $defaultImage = $mainDirectory . 'img/profile-img.png';
        $newImagePath = $imageFolder . 'profile-img.png';
        copy($defaultImage, $newImagePath);
    }

    if (isset($img['tmp_name']) && is_uploaded_file($img['tmp_name'])) {

        $imageExtension = pathinfo($img['name'], PATHINFO_EXTENSION);
        $imageName = strtolower(str_replace(' ', '_', $name)) . '_' . date('Ymd_His') . '_' . uniqid() . "." . $imageExtension;
        $imagePath = $imageFolder . $imageName;

        move_uploaded_file($img['tmp_name'], $imagePath);

        return $imageName;
    } else {
        return "profile-img.png";
    }
}

function get_usernames_json(object $pdo)
{
    $res = get_usernames($pdo);
    return json_encode($res);
}
