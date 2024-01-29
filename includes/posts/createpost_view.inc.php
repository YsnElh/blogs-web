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
    }
}

function registeredInputs()
{
    function renderFormGroup($labelId, $labelText, $inputId, $inputType, $inputName, $inputRows, $required, $infoTooltip, $errorKey)
    {
        echo '<div class="form-group row mb-4">';
        echo '<label id="' . $labelId . '" for="' . $inputId . '" class="col-sm-3 col-form-label tm-color-primary">' . $labelText . '</label>';
        echo '<div class="col-sm-9">';
        echo '<div class="input-i">';
        echo '<textarea class="form-control ' . ($inputType === 'textarea' ? 'mr-0 ml-auto' : '') . '" id="' . $inputId . '" name="' . $inputName . '" ' . ($inputType === 'textarea' ? 'rows="' . $inputRows . '"' : '') . ' ' . ($required ? 'required' : '') . '>' . (isset($_SESSION["register_data"][$inputName]) ? $_SESSION["register_data"][$inputName] : '') . '</textarea>';
        echo '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="' . $infoTooltip . '"></i>';
        if (isset($_SESSION["register_errors"][$errorKey])) {
            echo '<div class="error-msg">' . $_SESSION["register_errors"][$errorKey] . '</div>';
        }
        echo '</div></div></div>';
    }

    renderFormGroup('title-label', 'Title*', 'title', 'text', 'title', '', true, 'Title should not pass 50 characters', 'title_invalid');

    renderFormGroup('description-label', 'Description*', 'description', 'textarea', 'description', '3', true, 'Description should be bigger than 20 characters and smaller than 250', 'description_invalid');

    renderFormGroup('text-label', 'Text*', 'text', 'textarea', 'text', '15', true, 'The Text should contain at least 10 lines (800 characters)', 'text');
}


function registred_categs()
{
    //HANDLE NEW CATEGS
    if (isset($_SESSION["register_data"]['categs2'])) {
        echo '<div class="mt-2" id="new-categs-list">';
        foreach ($_SESSION["register_data"]['categs2'] as $index => $categ) {
            echo '<div class="d-flex mb-2" id="new-categs-list" data-index="' . $index . '">';
            echo '<input value="' . $categ . '" class="form-control" name="new_categ_value[]" type="text" autocomplete="text" required readonly>';
            echo '<button class="btn btn-outline-danger ml-2"><i class="fas fa-trash-alt"></i></button>';
            echo '</div>';
        }
        if (isset($_SESSION["register_errors"]['categs_error'])) {
            echo '<div class="error-msg">' . $_SESSION["register_errors"]['categs_error'] . '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="mt-2" id="new-categs-list"></div>';
    }
}
function returnImageErr()
{
    //HANDLE NEW CATEGS
    if (isset($_SESSION["register_errors"]['img_invalid'])) {
        echo '<div class="error-msg">' . $_SESSION["register_errors"]['img_invalid'] . '</div>';
    } else {
        echo '';
    }
}
function returnRegCategs1()
{
    if (isset($_SESSION["register_data"]['categs1'])) {
        echo json_encode($_SESSION["register_data"]['categs1']);
    } else {
        echo '[]';
    }
}

function unsetDataSess()
{

    if (isset($_SESSION["register_errors"])) {
        unset($_SESSION["register_errors"]);
    }
    if (isset($_SESSION["register_data"])) {
        unset($_SESSION["register_data"]);
    }
}
