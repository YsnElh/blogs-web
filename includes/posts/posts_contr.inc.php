<?php

declare(strict_types=1);

function get_posts(object $pdo, int $nbr_off, int $nbr_limit, string $searchValue)
{
    return get_all_posts($pdo, $nbr_off, $nbr_limit, $searchValue);
}

function get_nbr_posts(object $pdo)
{
    return get_posts_number($pdo);
}

function get_nbr_posts_serched(object $pdo, string $searchValue)
{
    return get_posts_number_search($pdo, $searchValue);
}
function get_all_posts_json(object $pdo)
{
    $res = get_posts_json($pdo);
    return json_encode($res);
}
/* HANDLE CREATE NEW POST */
function get_categs_ids(object $pdo)
{
    return get_categsids($pdo);
}

function checkCategsNbr(array $categs1, array $categs2)
{
    if (count($categs1) === 0 &&  count($categs2) === 0) {
        return true;
    } else if (count($categs2) > 3) {
        return true;
    } else {
        return false;
    }
}

function checkCategs2Length(array $categs)
{
    foreach ($categs as $c) {
        if (strlen($c) > 25) {
            return true;
            break;
        }
    }
    return false;
}

function is_id_notexist(object $pdo, array $ids)
{
    $checkExist = false;
    foreach ($ids as $id) {
        $checkExist = id_notexist($pdo, $id);
        if ($checkExist === true) {
            break;
        }
    }
    return $checkExist;
}

function is_inputs_empty(string $title, string $description, string $text, array $post_img)
{
    if (empty($title) || empty($description) || empty($text) || empty($post_img['name']) || $post_img['error'] !== UPLOAD_ERR_OK || $post_img['size'] === 0) {
        return true;
    } else {
        return false;
    }
}

function is_title_invalid(string $title)
{
    $pattern = '/^.{1,100}$/';
    return !preg_match($pattern, $title);
}

function is_description_invalid(string $description)
{
    $pattern = '/^.{20,250}$/';
    return !preg_match($pattern, $description);
}
function is_text_invalid(string $text)
{
    $pattern = '/^.{800,20000}$/s';
    if (strlen($text) > 20000) {
        return ['status' => true, 'msg' => 'The text is too long!'];
    } else if (strlen($text) < 800) {
        return ['status' => true, 'msg' => 'The text is small!'];
    } else {
        return ['status' => !preg_match($pattern, $text), 'msg' => 'Invalid Text!'];
    }
}

function is_img_invalid($img)
{
    if (is_uploaded_file($img['tmp_name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return ['status' => true, 'msg' => 'Invalid file type.'];
        }

        $maxFileSize = 3 * 1024 * 1024; // 3MB
        $maxFileSizeStr = "3MB";
        if ($img['size'] > $maxFileSize) {
            return ['status' => true, 'msg' => "File size is bigger than $maxFileSizeStr"];
        }
        $maxWidth = 5000;
        $maxHeight = 2500;
        $imgInfo = getimagesize($img['tmp_name']);
        if ($imgInfo === false || $imgInfo[0] > $maxWidth || $imgInfo[1] > $maxHeight || ($imgInfo[0] != 2 * $imgInfo[1])) {
            return ['status' => true, 'msg' => 'Invalid image dimensions.Follow the instructions'];
        }

        return ['status' => false, 'msg' => 'File Upload sucsess'];
    } else {
        return ['status' => true, 'msg' => 'File upload error.Try Again'];
    }
}

function create_post(object $pdo, string $title, string $description, $post_img, string $text, string $user_id, string $user_name)
{
    $newPostID = set_post($pdo, $title, $description, handleImg($post_img, $user_name), $text, $user_id);
    return $newPostID;
}

function handleImg($img, $name, $update = false, $old_img_name = null, $post_id = null, $pdo = null): string
{
    $mainDirectory = '../../';
    $imageFolder = $mainDirectory . 'post_imgs/';
    if (!file_exists($imageFolder)) {

        mkdir($imageFolder, 0755, true);
        $defaultImage = $mainDirectory . 'img/image-not-available.png';
        $newImagePath = $imageFolder . 'image-not-available.png';
        copy($defaultImage, $newImagePath);
    }

    if (isset($img['tmp_name']) && is_uploaded_file($img['tmp_name'])) {
        if ($update && $old_img_name && $pdo && checkPostHaveImg($pdo, $old_img_name, $post_id)) {
            $oldimg = $imageFolder . $old_img_name;
            if (file_exists($oldimg)) {
                unlink($oldimg);
            }
        }

        $imageExtension = pathinfo($img['name'], PATHINFO_EXTENSION);
        $imageName = strtolower(str_replace(' ', '_', $name)) . '_' . date('Ymd_His') . '_' . uniqid() . "_post." . $imageExtension;
        $imagePath = $imageFolder . $imageName;

        move_uploaded_file($img['tmp_name'], $imagePath);

        return $imageName;
    } else {
        if ($update && $old_img_name && $pdo && checkPostHaveImg($pdo, $old_img_name, $post_id)) {
            return $old_img_name;
        }
        return "image-not-available.png";
    }
}

function create_new_categ(object $pdo, string $name)
{
    return set_categ($pdo, $name);
}

function assign_ids_post(object $pdo, $newPostID, array $combinedCategIDs)
{
    foreach ($combinedCategIDs as $categID) {
        set_post_categ($pdo, $newPostID, $categID);
    }
}

//HANDLE EDIT POST

function checkUserHasPost(object $pdo, int $post_id, int $user_id)
{
    $userPostsIds = getUserPostsIDS($pdo, $user_id);
    foreach ($userPostsIds as $post) {
        if (intval($post['id']) == $post_id) {
            return true;
            break;
        }
    }
    return false;
}

function getPostInfos(object $pdo, int $post_id)
{
    return get_post($pdo, $post_id);
}

function getCategsIDs(object $pdo, int $post_id)
{
    return get_categs_by_id($pdo, $post_id);
}


function is_inputs_empty_EDIT(string $title, string $description, string $text)
{
    if (empty($title) || empty($description) || empty($text)) {
        return true;
    } else {
        return false;
    }
}

function update_post(
    object $pdo,
    string $title,
    string $description,
    $post_img,
    string $text,
    string $user_id,
    string $post_id,
    string $user_name,
    string $post_old_img
) {
    return updatepost($pdo, $title, $description, handleImg($post_img, $user_name, true, $post_old_img, $post_id, $pdo), $text, $user_id, $post_id);
}

function assign_ids_post_edit(object $pdo, string $post_id, array $categs_ids)
{
    delete_assign_ids($pdo, $post_id);
    foreach ($categs_ids as $categID) {
        set_post_categ($pdo, $post_id, $categID);
    }
}
