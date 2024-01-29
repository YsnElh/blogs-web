<?php

declare(strict_types=1);

function set_msg(object $pdo, string $name, string $email, string $subject, string $message)
{
    $query = "INSERT INTO messages (name, email, subject, message, created_at)
              VALUES (:name, :email, :subject, :message, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
}
