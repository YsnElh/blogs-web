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
    <link href="css/account2.css" rel="stylesheet">
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
            msg_operation();
            ?>
            <?php
            if (isset($_GET['search']) && $_GET['search'] == 'pending' && return_nbr_Pend_posts() > 0) {
                echo '<div class="display-6">Wait For moderators to check your pending posts</div>';
            }
            ?>
            <div id="posts">
                <?php print_posts() ?>

            </div>
            <?php include_once('components/footer.php') ?>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        function showMsg(typemsg, msg) {
            let currentURL = window.location.href;
            let newURL = currentURL + (currentURL.includes('?') ? '&' : '?') + `${typemsg}=${msg}`;
            window.location.href = newURL;
        }

        $(document).ready(function() {
            $("#form-verify-email").submit(function(event) {
                event.preventDefault();
                $("#loading-img-email").show();
                $.ajax({
                    type: $(this).attr("method"),
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#loading-img-email").hide();
                        showMsg('success', 'Email Sent Successfully');
                    },
                    error: function(error) {
                        $("#loading-img-email").hide();
                        showMsg('error', 'Failed, Try Later');
                    }
                });
            });
        });
    </script>
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