<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../../env.inc.php";

    $allowedOrigins = array(APP_URL, APP_URL);
    $origin = $_SERVER['HTTP_ORIGIN'];

    if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(array('error' => 'Forbidden - Unauthorized origin'));
        exit;
    }

    require_once '../../dbh.inc.php';
    require_once '../../config_session.inc.php';
    require_once '../posts_modal.inc.php';
    require_once '../posts_contr.inc.php';

    try {

        $categs_ids = get_categs_ids($pdo);
        header('Content-Type: application/json');
        echo json_encode($categs_ids);
    } catch (PDOException $e) {
        echo $e->getMessage();
        die();
    }
} else {
    header("Location: ../../index");
    die();
}
