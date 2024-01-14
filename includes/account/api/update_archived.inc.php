<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../../env.inc.php";
    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];

    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'Forbidden - Unauthorized origin']);
        die();
    }

    if (
        isset($_POST['post_id']) && isset($_POST['isarchived']) &&
        !empty($_POST['post_id']) && isset($_POST['isarchived'])
    ) {
        $post_id = $_POST['post_id'];
        $isarchived = $_POST['isarchived'];
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid or missing parameters']);
        die();
    }

    require_once "../../dbh.inc.php";
    require_once '../../config_session.inc.php';

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        die();
    }

    $user_id = $_SESSION['user_id'];
    require_once '../account_contr.inc.php';

    try {
        $result = update_isarchived($pdo, intval($post_id), $isarchived, $user_id);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }

} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    die();
}