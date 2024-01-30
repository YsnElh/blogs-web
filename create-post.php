<?php
require_once "./includes/dbh.inc.php";
require_once "./includes/config_session.inc.php";
require_once "./includes/posts/createpost_view.inc.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /login?error-createpost");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">

    <link href="css/register.css" rel="stylesheet">
    <style>
    #img-preview-infos {
        position: absolute;
        bottom: -16px;
        display: none;
        font-size: 15px;
    }

    .input-i {
        align-items: baseline;
    }
    </style>
</head>

<body>
    <?php
    include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main main-register">
            <?php check_register_errors() ?>
            <h1 class="tm-color-primary">CREATE NEW POST</h1>
            <div class="contanair">
                <form action="./includes/posts/posts.inc.php" method="post" enctype="multipart/form-data"
                    class="mb-5 ml-auto mr-0 login-form">
                    <?php registeredInputs() ?>
                    <div class="form-group row mb-4">
                        <label for="post_img" id="post-img-label" class="col-sm-3 col-form-label tm-color-primary">Post
                            Image*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <input class="form-control mr-0 ml-auto" name="post-img" id="post_img" type="file"
                                    accept=".jpg, .jpeg, .png" required onchange="previewImage(event)">
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
                                    •The image is required.
                                    •The image must be (jpg or jpeg or png).
                                    •The Width must be double the height => width=2height.
                                    •The maximum allowed dimensions are 5000x2500 pixels.
                                    •The file size must not exceed 3 megabytes (3MB).
                                    "></i>
                            </div>
                            <?php returnImageErr() ?>
                            <div class="image-container">
                                <img src="#" id="image-preview" alt="Preview" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 150px; margin-top: 10px;display: none;">
                                <i onclick="removeImage()" class="fas fa-trash-alt del-btn" id="del-btn-i"></i>
                            </div>
                            <span id="img-preview-infos" style="font-size: 15px;display:none"></span>
                        </div>
                    </div>
                    <div class="form-group row mb-4" id="categs-checkboxs">
                        <label class="col-sm-3 col-form-label tm-color-primary" for="categs">Choose
                            Categories*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <select class="form-control" name="categs[]" id="categs-choices" multiple></select>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top"
                                    title="Max categories allowed for a post is 4, if you choose more we will assigned the new categories you add, and the rest will be randomly picked from what you choose here"></i>
                            </div>
                            <span style="font-size: 15px;">Use (ctrl + left click mouse) to select multiple
                                categories</span><br>
                            <label for="new-categ" class="tm-color-primary" for="categs">Other
                                Categorie</label>
                            <div class="d-flex">
                                <input class="form-control" placeholder="Add new categories, only 3 allowed" type="text"
                                    name="new-categ" id="new-categ">
                                <button class="btn btn-outline-info ml-2" onclick="addNewCategory(event)">ADD</button>
                            </div>
                            <?php registred_categs() ?>


                        </div>
                    </div>
                    <button class="tm-btn tm-btn-primary tm-btn-small" id="register-btn" type="submit">Submit</button>
                </form>
            </div>
            <?php include_once('components/footer.php') ?>
        </main>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/create-post.js"></script>
    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    let categsRegistered = <?php returnRegCategs1() ?>;
    </script>
</body>

</html>
<?php unsetDataSess() ?>