<?php

declare(strict_types=1);

$token = null;
if (isset($_GET['uuid']) && !empty($_GET['uuid'])) {
    $token = $_GET['uuid'];
}
if (!$token || !isset($_SESSION['user_id'])) {
    header("Location: ../../account");
    die();
}
$user_id = $_SESSION['user_id'];
require_once './includes/dbh.inc.php';
require_once 'account_modal.inc.php';
require_once 'account_contr.inc.php';

$datetime_verf_sent = get_verf_sent($pdo, strval($user_id));

if (time() > strtotime($datetime_verf_sent) + (24 * 3600)) {
    echo '<h2>Link Expired!!</h2>';
    closeWindow();
    die();
}

$token_match = is_token_match($pdo, $token, strval($user_id));

if ($token_match) {
    $_SESSION['email_verified_at'] = verify_email($pdo, strval($user_id));
    echo '<h2>Email Verified</h2>';
    closeWindow();
    die();
} else {
    echo '<h2>Token mismatch, email verify failed. Try Again Later</h2>';
    closeWindow();
    die();
}

echo '<h1 class="display-3">Email Verification in process...</h1>';
echo '<img src="./img/loading.gif" width="200px" alt="">';

echo '';

function closeWindow()
{
    echo '<div id="countdown">This window will close in <span id="timer">5</span> seconds.</div>';
    echo '<script>
            let timer = 5;
            function updateCountdown() {
                document.getElementById("timer").innerText = timer;
                if (timer === 0) {
                    window.close();
                } else {
                    timer--;
                    setTimeout(updateCountdown, 1000);
                }
            }
            setTimeout(updateCountdown, 1000);
          </script>';
}

$pdo = null;
$stmt = null;
die();
