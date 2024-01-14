<?php

declare(strict_types=1);

require_once "./includes/config_session.inc.php";
require_once "./includes/dbh.inc.php";

require_once "posts_modal.inc.php"; 
require_once "posts_contr.inc.php"; 

function checkRoute(){
    global $pdo;
    if (isset($_GET['post-id']) && !empty($_GET['post-id']) && isset($_SESSION['user_id'])) {
        if (!checkUserHasPost($pdo,intval($_GET['post-id']),$_SESSION['user_id'])) {
            header("Location: /login");
            die();
        }
    }else{
        header("Location: /login");
        die();

    }
}

function check_register_errors(){
    if (isset($_SESSION["register_errors_edit"])) {
        $errors = $_SESSION["register_errors_edit"];
        echo '<div class="alert-msg-container">';
        foreach($errors as $error){
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
        
    }
}

$userID = $_SESSION['user_id'];
$post = null;
$categs_ids = null;

if (isset($_GET['post-id']) && !empty($_GET['post-id']) && isset($userID)) {
    $postID = $_GET['post-id'];
    $post = getPostInfos($pdo,intval($postID));
    $categs_ids = getCategsIDs($pdo,intval($postID));
}


function printCheck(){
    global $post;
    global $categs_ids;
    echo '';
}
//GENERATE CSRF TOKEN

function generateCsrfToken(){
    $_SESSION["token"] = bin2hex(random_bytes(32));
    $_SESSION["token-expire"] = time() + 3600 ;
    return $_SESSION["token"];
}

function printForm(){
    global $post;
    unset($_SESSION['token']);
    if ($post && count($post) > 0) {
        foreach ($post as $p) {
            echo '<h1 class="tm-color-primary">EDIT POST "'.$p['title'].'"</h1>';
            echo '<div class="contanair">';
            echo '<form action="./includes/posts/postsEDIT.inc.php" method="POST" enctype="multipart/form-data">';
            echo '<input type="hidden" name="token" value="'.generateCsrfToken().'">';

            //TITLE
            echo '<div class="form-group row mb-4">';
            echo '<label id="title-label" for="title" class="col-sm-3 col-form-label tm-color-primary">Title</label>';
            echo '<div class="col-sm-9">';
            echo '<div class="input-i">';
            if (isset($_SESSION["register_data_edit"]['title'])) {
                echo '<input class="form-control mr-0 ml-auto" value="'.$_SESSION["register_data_edit"]['title'].'" name="title" id="title" type="text" autocomplete="text" required>';
            }else{
                echo '<input class="form-control mr-0 ml-auto" value="'.$p['title'].'" name="title" id="title" type="text" autocomplete="text" required>';
            }
            echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Title should not pass 50 characters"></i>';
            echo '</div></div></div>';

            //DESCRIPTION
            echo '<div class="form-group row mb-4">';
            echo '<label id="description-label" for="description" class="col-sm-3 col-form-label tm-color-primary">Description</label>';
            echo '<div class="col-sm-9">';
            echo '<div class="input-i">';
            if (isset($_SESSION["register_data_edit"]['description'])) {
                echo '<textarea class="form-control" id="description" name="description" rows="3"required>'.$_SESSION["register_data_edit"]['description'].'</textarea>';
            }else{
                echo '<textarea class="form-control" id="description" name="description" rows="3"required>'.$p['description'].'</textarea>';
            }
            echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Description should be bigger than 20 characters and smaller than 250"></i>';
            echo '</div></div></div>';

            //TEXT
            echo '<div class="form-group row mb-4">';
            echo '<label id="text-label" for="text" class="col-sm-3 col-form-label tm-color-primary">Text</label>';
            echo '<div class="col-sm-9">';
            echo '<div class="input-i">';
            if (isset($_SESSION["register_data_edit"]['text'])) {
                echo '<textarea class="form-control mr-0 ml-auto" id="text" name="text" rows="15" required>'.$_SESSION["register_data_edit"]['text'].'</textarea>';
            }else{
                echo '<textarea class="form-control mr-0 ml-auto" id="text" name="text" rows="15" required>'.$p['text'].'</textarea>';
            }
            echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="The Text should contain atleast 10 ligne (800 characters)"></i>';
            echo '</div></div></div>';

            //IMG
            echo '<div class="form-group row mb-4">';
            echo '<label for="post_img" id="post-img-label" class="col-sm-3 col-form-label tm-color-primary">Post Image</label>';
            echo '<div class="col-sm-9">';
            echo '<div class="input-i">';
            echo '<input class="form-control mr-0 ml-auto" name="post-img" id="post_img" type="file" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">';
            echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="•The image must be (jpg or jpeg or png).•The Width must be double the height => width=2height.•The maximum allowed dimensions are 5000x2500 pixels.•The file size must not exceed 3 megabytes (3MB)."></i>';
            echo '</div>';
            echo '<div class="image-container">';
            echo '<img src="#" id="image-preview" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 150px; margin-top: 10px;display: none;">';
            echo '<i onclick="removeImage()" class="fas fa-trash-alt del-btn" id="del-btn-i"></i>';
            returnImageErr();
            echo '</div>';
            echo '<span id="img-preview-infos" style="font-size: 15px;display:none"></span>';
            echo '</div></div>';
            
            //CATEGS HANDLE
            echo '<div class="form-group row mb-4" id="categs-checkboxs">';
            echo '<label class="col-sm-3 col-form-label tm-color-primary" for="categs">Choose Categories</label>';
            echo '<div class="col-sm-9">';
            echo '<div class="input-i">';
            echo '<select class="form-control" name="categs[]" id="categs-choices" multiple></select>';
            echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Max categories allowed for a post is 4, if you choose more we will assigned the new categories you add, and the rest will be randomly picked from what you choose here"></i>';
            echo '</div>';
            echo '<span style="font-size: 15px;">Use (ctrl + left click mouse) to select multiple categories</span><br>';
            echo '<label for="new-categ" class="tm-color-primary" for="categs">Other Categorie</label>';
            echo '<div class="d-flex">';
            echo '<input class="form-control" placeholder="Add new categories, only 3 allowed" type="text" name="new-categ" id="new-categ">';
            echo '<button class="btn btn-outline-info ml-2" onclick="addNewCategory(event)">ADD</button>';
            echo '</div>';
            registred_categs();
            echo '</div></div>';
            echo '<input type="hidden" name="post_id" value="'.$p['id'].'">';
            echo '<input type="hidden" name="post_old_img" value="'.$p['post_img'].'">';
            echo '<button class="tm-btn tm-btn-primary tm-btn-small" id="register-btn" type="submit">Submit</button>';
            echo '</form>';
            echo '</div>';
        }
    }
}


function returnImageErr(){
    echo '';
}

function registred_categs(){
    if (isset($_SESSION["register_data_edit"]['categs2'])) {
        echo '<div class="mt-2" id="new-categs-list">';
            foreach($_SESSION["register_data_edit"]['categs2'] as $index => $categ){
                echo '<div class="d-flex mb-2" id="new-categs-list" data-index="' . $index . '">';
                echo '<input value="'.$categ.'" class="form-control" name="new_categ_value[]" type="text" autocomplete="text" required readonly>';
                echo '<button class="btn btn-outline-danger ml-2"><i class="fas fa-trash-alt"></i></button>';
                echo '</div>';
            }
            if (isset($_SESSION["register_errors"]['categs_error'])) {
                echo '<div class="error-msg">'.$_SESSION["register_errors"]['categs_error'].'</div>';
            }
        echo '</div>';  
    }else{
        echo '<div class="mt-2" id="new-categs-list"></div>';
    }
}

function categsInitial(){
    global $categs_ids;
    if (isset($_SESSION["register_data_edit"]['categs1'])) {

        echo json_encode($_SESSION["register_data_edit"]['categs1']);

    }else if(isset($categs_ids)){
        $categsArray = array_map(function($item) {
            return strval($item['id']);
        }, $categs_ids);

        echo json_encode($categsArray);
        
    }else{

        echo '[]';
    }
}
function unsetDataSession(){
    unset($_SESSION["register_errors_edit"]);
    unset($_SESSION["register_data_edit"]); 
}