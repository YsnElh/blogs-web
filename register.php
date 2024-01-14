<?php
    require_once "./includes/config_session.inc.php";
    require_once "./includes/config_session.inc.php";
    require_once "./includes/register/register_view.inc.php";
    if (isset($_SESSION['user_id'])) {
        header("Location: /account");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/register.css" rel="stylesheet">
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div>
        <main class="tm-main main-register">
            <?php
                check_register_errors();
            ?>
            <h1 class="tm-color-primary">SignUp Page</h1>
            <div>
                <h2 class="tm-color-primary">Welcome to Our Blog!</h2>
                <p>SignUp to access exclusive content, Create posts, write comments, and engage with our <br /> vibrant
                    community.</p>
            </div>
            <div class="container">
                <form action="./includes/register/register.inc.php" method="post" enctype="multipart/form-data"
                    class="mb-5 ml-auto mr-0 login-form">
                    <input type="hidden" name="csrf-token" value="<?php generateCSRFToken() ?>">
                    <?php
                        registeredInputs();
                    ?>

                    <div class="form-group row mb-4">
                        <label for="profile-pic" id="profile-pic-label"
                            class="col-sm-3 col-form-label tm-color-primary">Profile Picture</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <input class="form-control mr-0 ml-auto" name="profile-pic" id="profile-pic" type="file"
                                    accept=".jpg, .jpeg, .png" onchange="previewImagePlusValid(event)">
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
                                    •The image is optional.
                                    •The image must be square (width equal to height).
                                    •The maximum allowed dimensions are 3000x3000 pixels.
                                    •The file size must not exceed 2 megabytes (2MB).
                                    "></i>
                            </div>
                            <div class="error" id="error-profile-pic-message"></div>
                            <div class="image-container">
                                <img src="#" id="image-preview" alt="Preview" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 150px; margin-top: 10px;border-radius:100px;display: none;">
                                <i onclick="removeImage()" class="fas fa-trash-alt del-btn" id="del-btn-i"></i>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label id="password-label" for="password"
                            class="col-sm-3 col-form-label tm-color-primary">Password*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <input class="form-control mr-0 ml-auto" name="password" id="password" type="password"
                                    autocomplete="new-password" required>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
                                    •It must be at least 8 characters long.
                                    •It must contain at least one alphabetical character.
                                    •It must contain at least one numeric digit.
                                    "></i>
                            </div>
                            <div class="error" id="error-pass-message"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label id="cnfm-password-label" for="cnfm-password"
                            class="col-sm-3 col-form-label tm-color-primary">Confirm Password*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <input class="form-control mr-0 ml-auto" name="cnfm-password" id="cnfm-password"
                                    type="password" autocomplete="new-password" required>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top"
                                    title="The Confirm Password must be the same as the Password"></i>
                            </div>
                            <div class="error" id="error-cnfm-pass-message"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <button class="tm-btn tm-btn-primary tm-btn-small" id="register-btn"
                                type="submit">Register</button>
                        </div>
                    </div>
                    <p>Already have an account ? <a href="./login" class="tm-color-primary">Sign in here</a> !</p>
                </form>

            </div>

        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <!-- <script src="js/bootstrap.bundle.min.js"></script> -->
    <script src="js/main.js"></script>

    <script src="js/register-script.js"></script>
    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    let alertDivs = document.querySelectorAll('.alert');
    alertDivs.forEach((elm) => {
        setTimeout(function() {
            elm.style.opacity = '0';
        }, 3000);
    })
    </script>
    <script>
    const usernameRegex = /^(?!\.)(?!.*\.$)(?=.*[a-z].*[a-z])[a-z0-9._]{5,20}$/;
    let usernameInput = document.getElementById("username");
    let usernameLabel = document.getElementById("username-label");
    let usernameErr = document.getElementById("error-username-message");

    $.ajax({
        url: './includes/register/api/regiter_usernames.inc.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            let usernamesjson = data;
            let usernamesObj = Object.values(usernamesjson);
            let usernames = [];
            usernamesObj.map(e => {
                usernames.push(e.username);
            })
            usernameInput.addEventListener("input", () => {
                if (usernameInput.value.length > 0 &&
                    (usernames.includes(usernameInput.value) || !usernameRegex.test(usernameInput
                        .value))) {
                    if (usernames.includes(usernameInput.value)) {
                        usernameErr.style.color = "#ff7373"
                        usernameErr.innerHTML = "username already taken!"
                    } else {
                        usernameErr.innerHTML = ""
                    }
                    usernameInput.style.border = "1px solid #ff7373";
                    usernameLabel.style.color = "#ff7373";
                    usernameInput.style.outline = "solid #ff7373";
                } else if (usernameInput.value.length === 0) {
                    usernameErr.innerHTML = ""
                    usernameInput.style.border = "";
                    usernameLabel.style.color = "";
                    usernameInput.style.outline = "";
                } else {
                    usernameErr.innerHTML = ""
                    usernameInput.style.border = "";
                    usernameLabel.style.color = "";
                    usernameInput.style.outline = "";

                }
            })
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    })
    </script>

</body>

</html>