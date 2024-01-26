<?php
    require_once "./includes/config_session.inc.php";
    require_once "./includes/panel/panel_view.inc.php";
    
    if (!isset($_SESSION['user_id']) || $_SESSION['isadmin'] == 0) {
        header("Location: /account");
        die();
    }
    handleUrlPage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">
</head>

<body>
    <?php
        include('components/header.php');
    ?>

    <div class="container-fluid">
        <main class="tm-main">
            <?php 
                print_navbar();
                print_page_content();
            ?>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
<?php unsetSessVars() ?>