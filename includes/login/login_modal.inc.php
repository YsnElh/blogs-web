<?php

declare(strict_types=1);

function get_user(object $pdo, string $login_idn){
    $query = "SELECT * FROM users WHERE email = :login_idn OR username = :login_idn";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":login_idn" , $login_idn);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}