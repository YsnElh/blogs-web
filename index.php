<?php
    require_once "./includes/config_session.inc.php";
    require_once "includes/posts/posts_view.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs WebSite</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <!-- https://fonts.google.com/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
</head>

<body>
    <?php
        include('components/header.php');
    ?>

    <div class="container-fluid">
        <main class="tm-main">

            <!-- Search form -->
            <div class="row tm-row">
                <div class="col-12">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET"
                        class="form-inline tm-mb-80 tm-search-form">
                        <input class="form-control tm-search-input" name="search-value" type="text"
                            value="<?php data_search() ?>" placeholder="Search..." aria-label="Search">
                        <input type="hidden" name="page-nr-s" value="1">
                        <button class="tm-search-button" type="submit">
                            <i class="fas fa-search tm-search-icon" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php 
                not_found_search();
                not_found_posts();
            ?>
            <div class="row tm-row">
                <?php
                    print_posts();
                ?>
            </div>
            <div class="row tm-row tm-mt-100 tm-mb-75">
                <?php
                    paginate_btns();
                ?>
            </div>
            <footer class="row tm-row">
                <hr class="col-12">
                <div class="col-md-6 col-12 tm-color-gray">
                    Design: <a rel="nofollow" target="_parent" href="https://templatemo.com"
                        class="tm-external-link">TemplateMo</a>
                </div>
                <div class="col-md-6 col-12 tm-color-gray tm-copyright">
                    Copyright 2020 Xtra Blog Company Co. Ltd.
                </div>
            </footer>
        </main>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>