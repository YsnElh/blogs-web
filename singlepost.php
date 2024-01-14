<?php
    require_once "includes/singlepost/singlepost_view.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php print_title() ?></title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <!-- https://fonts.google.com/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <style>
    .alert-msg {
        position: fixed;
        top: 10px;
        right: 10px;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
        z-index: 100;
    }

    .alert-msg-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 100;
    }

    .alert-msg-container div {
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }

    .alert-success {
        position: fixed;
        top: 10px;
        right: 10px;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
        z-index: 100;
    }

    /* Comments load more */
    .loader-div {
        display: none;
        position: fixed;
        margin: 0px;
        padding: 0px;
        right: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background-color: #fff;
        z-index: 30001;
        opacity: 0.8;
    }

    .loader-img {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }
    </style>
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main">
            <?php 
                check_register_errors(); 
                check_cmnt_delError();
            ?>
            <!-- Search form -->
            <!-- <div class="row tm-row">
                <div class="col-12">
                    <form method="GET" class="form-inline tm-mb-80 tm-search-form">
                        <input class="form-control tm-search-input" name="query" type="text" placeholder="Search..."
                            aria-label="Search">
                        <button class="tm-search-button" type="submit">
                            <i class="fas fa-search tm-search-icon" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div> -->
            <div class="row tm-row">
                <div class="col-12">
                    <?php print_img() ?>
                </div>
            </div>
            <div class="row tm-row">
                <div class="col-lg-8 tm-post-col">
                    <div class="tm-post-full">
                        <?php print_post() ?>

                        <!-- Comments -->
                        <div>
                            <h2 class="tm-color-primary tm-post-title">Comments</h2>
                            <hr class="tm-hr-primary tm-mb-45" id="comments-sec">
                            <?php print_comments() ?>
                            <form action="includes/singlepost/singlepost.inc.php" method="post"
                                class="mb-5 tm-comment-form">
                                <h2 class="tm-color-primary tm-post-title mb-4">Your comment</h2>
                                <div class="mb-4">
                                    <?php registred_cmnt() ?>
                                    <input type="hidden" name="post-id" value="<?php return_id() ?>">
                                </div>
                                <div class="text-right">
                                    <button class="tm-btn tm-btn-primary tm-btn-small">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <aside class="col-lg-4 tm-aside-col">
                    <div class="tm-post-sidebar">
                        <hr class="mb-3 tm-hr-primary">
                        <h2 class="mb-4 tm-post-title tm-color-primary">Categories</h2>
                        <ul class="tm-mb-75 pl-5 tm-category-list">
                            <?php print_categs() ?>
                        </ul>
                        <hr class="mb-3 tm-hr-primary">
                        <h2 class="tm-mb-40 tm-post-title tm-color-primary">Related Posts</h2>
                        <?php print_realted_posts() ?>
                    </div>
                </aside>
            </div>
            <footer class="row tm-row">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    // This Code get parametrs from url    
    // const queryString = window.location.search;
    // const urlParams = new URLSearchParams(queryString);
    // const id = urlParams.get('id')
    // console.log(id);
    let alertDivs = document.querySelectorAll('.alert');
    alertDivs.forEach((elm) => {
        setTimeout(function() {
            elm.style.opacity = '0';
        }, 3000);
    })
    </script>
    <script>
    function cleanURL() {
        var currentURL = window.location.href;
        var url = new URL(currentURL);
        var idParam = url.searchParams.get('id');
        var newURL = window.location.origin + window.location.pathname + '?id=' + idParam;
        history.replaceState(null, null, newURL);
    }
    window.onload = cleanURL;
    </script>

</body>

</html>