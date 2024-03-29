<?php
require_once "./includes/config_session.inc.php";
require_once "./includes/contact/contact_view.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xtra Blog</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <style>
    .single-alert {
        position: fixed;
        top: 1%;
        right: 1%;
    }
    </style>
</head>

<body>
    <?php
    include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main">
            <div class="row tm-row tm-mb-45">
                <div class="col-12">
                    <div class="gmap_canvas">
                        <!-- Google Map -->
                        <iframe width="100%" height="477" id="gmap_canvas"
                            src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=k&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </iframe>
                    </div>
                </div>
            </div>
            <?php check_register_errors() ?>
            <div class="row tm-row tm-mb-120">
                <div class="col-12">
                    <h2 class="tm-color-primary tm-post-title tm-mb-60">Contact Us</h2>
                </div>
                <div class="col-lg-7 tm-contact-left">
                    <form method="POST" action="./includes/contact/contact.inc.php"
                        class="mb-5 ml-auto mr-0 tm-contact-form">
                        <div class="form-group row mb-4">
                            <label for="name" class="col-sm-3 col-form-label text-right tm-color-primary">Name<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control mr-0 ml-auto" name="name" id="name" required type="text"
                                    value="<?php registeredName() ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="email" class="col-sm-3 col-form-label text-right tm-color-primary">Email<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control mr-0 ml-auto" name="email" id="email" required type="email"
                                    value="<?php registeredEmail() ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label for="subject"
                                class="col-sm-3 col-form-label text-right tm-color-primary">Subject<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control mr-0 ml-auto" name="subject" id="subject" required
                                    type="text" value="<?php registeredSub() ?>">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <label for="message"
                                class="col-sm-3 col-form-label text-right tm-color-primary">Message<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control mr-0 ml-auto" name="message" id="message" rows="8"
                                    required><?php registeredMsg() ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row text-right">
                            <div class="col-12">
                                <button class="tm-btn tm-btn-primary tm-btn-small">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 tm-contact-right">
                    <address class="mb-4 tm-color-gray">
                        120 Adress 10550
                    </address>
                    <span class="d-block">
                        Tel:
                        <a href="tel:060-000-0000" class="tm-color-gray">060-000-0000</a>
                    </span>
                    <span class="mb-4 d-block">
                        Email:
                        <a href="mailto:contact@company.com<" class="tm-color-gray">contact@company.com</a>
                    </span>
                </div>
            </div>
            <?php include_once('components/footer.php') ?>
        </main>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    let alertDivs = document.querySelectorAll('.alert');
    alertDivs.forEach((elm) => {
        setTimeout(function() {
            elm.style.opacity = '0';
        }, 3000);
    })
    history.replaceState({}, document.title, window.location.pathname);
    </script>
</body>

</html>
<?php unsetSessVars() ?>