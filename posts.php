<?php
require_once "./includes/config_session.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/cards_posts.css" rel="stylesheet">
</head>

<body>
    <?php
    include('components/header.php');
    ?>
    <div>
        <main class="tm-main">
            <h1 id="error"></h1>
            <div class="mb-3">
                <div>
                    <label for="sort-by">Sort By:</label>
                    <select class="form-control" name="sort-by" id="sort-by">
                        <option value="1">Last Created</option>
                        <option value="2">First Created</option>
                        <option value="3">Title</option>
                    </select>
                </div>
                <div>
                    <label for="categorySelect">Categories names:</label>
                    <select class="form-control" name="categorySelect" id="categorySelect">
                    </select>
                </div>
                <div>
                    <label for="author-name">Authors names:</label>
                    <select class="form-control" name="author-name" id="author-name">
                    </select>
                </div>
                <div id="print-json"></div>

            </div>
            <div id="all-posts"></div>
            <button class="btn btn-outline-info" id="load-more-button">Load More</button>
            <div id="loading" style="display: none; text-align: center;">
                <img src="./img/loading.gif" width="200px" alt="">
            </div>
            <div id="not-found"></div>
            <?php include_once('components/footer.php') ?>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/posts.js"></script>
</body>

</html>