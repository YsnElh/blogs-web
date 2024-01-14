<?php

declare(strict_types=1);

function check_register_errors(){

    if (isset($_SESSION["register_errors"])) {
        $errors = $_SESSION["register_errors"];
        echo '<div class="alert-msg-container">';
        foreach($errors as $error){
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
        
    }
}

function registeredInputs(){
    //HANDLE Title
    if(isset($_SESSION["register_data"]['title'])){
        echo '<div class="form-group row mb-4">';
        echo '<label id="title-label" for="title" class="col-sm-3 col-form-label tm-color-primary">Title*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="title" id="title" type="text" value="'.$_SESSION["register_data"]['title'].'" autocomplete="text" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Title should not pass 50 characters"></i>';
        echo '</div>';
        if (isset($_SESSION["register_errors"]['title_invalid'])) {
            echo '<div class="error-msg">'.$_SESSION["register_errors"]['title_invalid'].'</div>';
        }
        echo '</div>';
        echo '</div>';
    }else{
        echo '<div class="form-group row mb-4">';
        echo '<label id="title-label" for="title" class="col-sm-3 col-form-label tm-color-primary">Title*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<input class="form-control mr-0 ml-auto" name="title" id="title" type="text" autocomplete="text" required>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Title should not pass 50 characters"></i>';
        echo '</div></div></div>';

    }
    // HANDLE DESCRIPTION

    if (isset($_SESSION["register_data"]['description'])) {
        echo '<div class="form-group row mb-4">';
        echo '<label id="description-label" for="description" class="col-sm-3 col-form-label tm-color-primary">Description*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<textarea class="form-control" id="description" name="description" rows="3"required>'.$_SESSION["register_data"]['description'].'</textarea>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Description should be bigger than 20 characters and smaller than 250"></i>';
        echo '</div>';
        if (isset($_SESSION["register_errors"]['description_invalid'])) {
            echo '<div class="error-msg">'.$_SESSION["register_errors"]['description_invalid'].'</div>';
        }
        echo '</div></div>';
    }else{
        echo '<div class="form-group row mb-4">';
        echo '<label id="description-label" for="description" class="col-sm-3 col-form-label tm-color-primary">Description*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<textarea class="form-control" id="description" name="description" rows="3"required></textarea>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Description should be bigger than 20 characters and smaller than 250"></i>';
        echo '</div></div></div>';

    }
    //HANDLE TEXT
    if (isset($_SESSION["register_data"]['text'])) {
        echo '<div class="form-group row mb-4">';
        echo '<label id="text-label" for="text" class="col-sm-3 col-form-label tm-color-primary">Text*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<textarea class="form-control mr-0 ml-auto" id="text" name="text" rows="15" required>'.$_SESSION["register_data"]['text'].'</textarea>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="The Text should contain atleast 10 ligne (800 characters)"></i>';
        echo '</div>';
        if (isset($_SESSION["register_errors"]['text'])) {
            echo '<div class="error-msg">'.$_SESSION["register_errors"]['text'].'</div>';
        }
        echo '</div></div>';
    }else{
        echo '<div class="form-group row mb-4">';
        echo '<label id="text-label" for="text" class="col-sm-3 col-form-label tm-color-primary">Text*</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<textarea class="form-control mr-0 ml-auto" id="text" name="text" rows="15" required></textarea>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="The Text should contain atleast 10 ligne (800 characters)"></i>';
        echo '</div></div></div>';
    }

}

function registred_categs(){
    //HANDLE NEW CATEGS
    if (isset($_SESSION["register_data"]['categs2'])) {
        echo '<div class="mt-2" id="new-categs-list">';
            foreach($_SESSION["register_data"]['categs2'] as $index => $categ){
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
function returnImageErr(){
    //HANDLE NEW CATEGS
    if (isset($_SESSION["register_errors"]['img_invalid'])) {
        echo '<div class="error-msg">'.$_SESSION["register_errors"]['img_invalid'].'</div>';
    }else{
        echo '';
    }
}
function returnRegCategs1(){
    if (isset($_SESSION["register_data"]['categs1'])) {
        echo json_encode($_SESSION["register_data"]['categs1']);
    }else{
        echo '[]';
    }
}

function unsetDataSess(){

    if(isset($_SESSION["register_errors"])){
        unset($_SESSION["register_errors"]);
    }
    if(isset($_SESSION["register_data"])){
        unset($_SESSION["register_data"]); 
    }
}