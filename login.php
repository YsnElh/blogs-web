<?php
    require_once "./includes/dbh.inc.php";
    require_once "./includes/config_session.inc.php";
    require_once "./includes/login/login_view.inc.php";
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
    <title>Login</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <style>
    .alert-msg-container {
        position: fixed;
        top: 10px;
        right: 10px;
    }

    .alert-msg-container div {
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }
    </style>
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main main-login">
            <?php
                check_login_errors();
            ?>
            <h1 class="tm-color-primary">Login Page</h1>
            <div>
                <h2 class="tm-color-primary">Welcome to Our Blog!</h2>
                <p>Sign in to your account</p>
            </div>
            <div class="container">
                <form method="POST" action="./includes/login/login.inc.php" class="mb-5 ml-auto mr-0 login-form">
                    <div class="form-group row mb-4">
                        <label for="login-idn" id="login-idn-label"
                            class="col-sm-3 col-form-label tm-color-primary">EMAIL OR USERNAME*</label>
                        <div class="col-sm-9">
                            <input class="form-control mr-0 ml-auto" name="login-idn" id="login-idn" type="login-idn"
                                required>
                            <div class="error" id="error-login-idn-message"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label id="password-label" for="password"
                            class="col-sm-3 col-form-label tm-color-primary">PASSWORD*</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control mr-0 ml-auto" name="password" id="password"
                                required>
                            <div class="error" id="error-pass-message"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <button class="tm-btn tm-btn-primary tm-btn-small" id="sbmt-lgn-btn">Login</button>
                        </div>
                    </div>
                    <p>New to our blog? <a href="./register" class="tm-color-primary">Sign up here</a> and join us!
                    </p>
                </form>
            </div>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>

    <script>
    function validateInputs(input, regex, label) {
        input.addEventListener("input", () => {
            let isValid = false;
            if (regex.test(input.value)) {

                input.style.border = "";
                label.style.color = "";
                input.style.outline = "";
                isValid = true;

            } else if (input.value.length === 0) {

                input.style.border = "";
                label.style.color = "";
                input.style.outline = "";
                isValid = true;

            } else {

                input.style.border = "1px solid #ff7373";
                label.style.color = "#ff7373";
                input.style.outline = "solid #ff7373";
            }

            if (input.id === "email") {
                emailValidated = isValid;
            } else if (input.id === "password") {
                passValidated = isValid;
            }
            updateSubmitButton();

        });
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailInput = document.getElementById("email");
    const emailLabel = document.getElementById("email-label");
    validateInputs(emailInput, emailRegex, emailLabel);
    </script>
    <script>
    let alertDivs = document.querySelectorAll('.alert');
    alertDivs.forEach((elm) => {
        setTimeout(function() {
            elm.style.opacity = '0';
        }, 3000);
    })
    </script>
</body>

</html>