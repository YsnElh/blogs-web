<?php

declare(strict_types=1);

function get_username(object $pdo, string $username)
{
    $query = "SELECT username FROM users WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_email(object $pdo, string $email)
{
    $query = "SELECT email FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_user(object $pdo, string $name, string $username, string $email, string $pwd, $img)
{
    // Generate a unique token
    do {
        $token = bin2hex(random_bytes(32));
        $tokenExistsQuery = "SELECT COUNT(*) FROM users WHERE remember_token = :token";
        $tokenExistsStmt = $pdo->prepare($tokenExistsQuery);
        $tokenExistsStmt->bindParam(':token', $token);
        $tokenExistsStmt->execute();
        $tokenExists = $tokenExistsStmt->fetchColumn();
    } while ($tokenExists);

    // Hash the password
    $options = ['cost' => 12];
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

    // Insert user data into the database
    $query = "INSERT INTO 
              users(name, username, email, profile_photo_path, password, remember_token, created_at, updated_at) 
              VALUES(:name, :username, :email, :profile_photo_path, :password, :token, NOW(), NOW())";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":profile_photo_path", $img);
    $stmt->bindParam(":password", $hashedPwd);
    $stmt->bindParam(":token", $token);

    $stmt->execute();

    // Get the new user's information
    $userId = $pdo->lastInsertId();
    $selectQuery = "SELECT * FROM users WHERE id = :id";
    $selectStmt = $pdo->prepare($selectQuery);
    $selectStmt->bindParam(":id", $userId);
    $selectStmt->execute();

    return $selectStmt->fetch(PDO::FETCH_ASSOC);
}


function get_usernames(object $pdo)
{
    $query = "SELECT username FROM users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}
