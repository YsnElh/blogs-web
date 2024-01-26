<?php
    require_once "./includes/config_session.inc.php";
    require_once "./includes/posts/editpost_view.inc.php";
    checkRoute($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
            <?php printCheck() ?>
            <?php printForm() ?>
            <h1><?php //echo $_SESSION['_token'] ?></h1>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/edit-post.js"></script>
    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    let categsRegistered = <?php categsInitial() ?>;
    document.addEventListener("DOMContentLoaded", () => {
        fillDivCategs(categsRegistered);
    });
    </script>
</body>

</html>
<?php unsetDataSession() ?>