<?php
    require_once "./includes/config_session.inc.php";
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        die();
    }
    require_once "./includes/account/account_view.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">
    <style>
    .alert-success {
        position: fixed;
        top: 10px;
        right: 10px;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }

    #posts {
        margin-top: 20px;
        display: flex;
        justify-content: space-around;
        width: 100%;
        height: auto;
        flex-wrap: wrap;
    }

    #posts .post-container img {
        border-radius: 6px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    #posts .post-container {
        position: relative;
        overflow: hidden;
        margin-bottom: 10px;
        cursor: pointer;
    }

    #posts .post-container .post-content {
        color: #fff;
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
    }

    #posts .post-container .post-content a {
        color: #fff;
        text-decoration: none;
    }

    #posts .post-container .post-content a:hover {
        color: #fff;
        transition: 0.3s;
    }

    #posts .post-container .post-content .buttons-post {
        position: relative;
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    #posts .post-container .post-content .buttons-post .switch {
        position: relative;
        top: 16%;
        margin: 0;
    }

    #posts .post-container .post-content .buttons-post>* {
        margin-right: 20px;
    }

    #posts .post-container .post-content .input-check-container {
        position: relative;
    }

    #posts .post-container .post-content .input-check-container .info-check {
        margin-left: 5px;
        color: #fff;
        font-size: 10px;
    }

    #posts .post-container:hover .post-content {
        display: block;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
        height: 100%;
        width: 100%;
    }

    #posts .post-container:hover img {
        transform: scale(1.05);
        filter: blur(4px);
    }

    /* <reset-style> ============================ */
    a {
        text-decoration: none;
    }

    /* <main-style> ============================ */
    .menu__link {
        color: #fff;
        line-height: 2;
        position: relative;
    }

    .menu__link::before {
        content: '';
        width: 0;
        height: 2px;
        border-radius: 2px;
        background-color: #fff;
        position: absolute;
        bottom: -.25rem;
        left: 50%;
        transition: width .4s, left .4s;
    }

    .menu__link:hover::before {
        width: 100%;
        left: 0;
    }

    /* The switch - the box around the slider */
    .switch {
        font-size: 10px;
        position: relative;
        display: inline-block;
        width: 3.5em;
        height: 2em;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgb(182, 182, 182);
        transition: .4s;
        border-radius: 10px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 1.4em;
        width: 1.4em;
        border-radius: 8px;
        left: 0.3em;
        bottom: 0.3em;
        transform: rotate(270deg);
        background-color: rgb(255, 255, 255);
        transition: .4s;
    }

    .switch input:checked+.slider {
        background-color: #21cc4c;
    }

    .switch input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    .switch input:checked+.slider:before {
        transform: translateX(1.5em);
    }

    /* ------------------ */
    @media (min-width: 991px) {
        .tm-main {
            padding: 10px 10px 0px 40px;
        }
    }

    @media (max-width: 991px) {
        .tm-main {
            margin-left: 0;
            padding: 10px;
            width: 100%;
        }
    }
    </style>
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main">
            <?php print_navbar() ?>

            <div><?php outputName() ?></div>
            <?php mini_navbar_posts() ?>
            <?php
                success_msg();
            ?>
            <?php 
                if (isset($_GET['search']) && $_GET['search'] == 'pending' && return_nbr_Pend_posts() > 0) {
                    echo '<div class="display-6">Wait For moderators to check your pending posts</div>';
                }
            ?>
            <div id="posts">
                <?php print_posts() ?>
            </div>

        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
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
    history.replaceState({}, document.title, window.location.pathname);
    </script>
    <script>
    function updateStatus(checkbox) {
        let postID = checkbox.value;
        let isChecked = checkbox.checked;
        let infoCheckSpan = checkbox.closest('.input-check-container').querySelector('.info-check');

        fetch('/includes/account/api/update_archived.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    isarchived: isChecked ? 0 : 1,
                    post_id: postID,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success === false) {
                    alert(data.message);
                    checkbox.checked = isChecked;
                } else {
                    infoCheckSpan.textContent = isChecked ? 'Active' : 'Archived';
                }
            })
            .catch(error => {
                checkbox.checked = isChecked;
                alert('Error in POST request: ' + error.message);
            });
    }
    </script>

</body>

</html>