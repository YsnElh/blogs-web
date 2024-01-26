<?php

declare(strict_types=1);

function get_pending_posts(object $pdo){
    $query = "SELECT id,title,post_img,created_at,updated_at FROM posts WHERE status = 'pending' order by updated_at";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_users(object $pdo, string $orderBy) {
    $allowedColumns = ['name', 'created_at', 'username'];
    if (!in_array($orderBy, $allowedColumns)) {
        $orderBy = 'name';
    }
    $query = "SELECT * FROM users ORDER BY $orderBy";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
//Change Role

function update_user_role(object $pdo, string $user_id, int $role){
    $query = "UPDATE users SET isadmin = $role WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
}

//SINGLE POST
function get_pen_post(object $pdo, int $id) {
    $query = "SELECT P.*, U.name as user_name, U.id as user_id, U.email as user_email FROM posts as P
          INNER JOIN users as U
          ON P.user_id = U.id
          WHERE P.id = :id AND P.status = 'pending'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_pen_categs(object $pdo) {
    $query = "SELECT * from categories WHERE status = 'pending'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

//HANDLE CATEGS

function update_categ(object $pdo, int $id, string $action){
    if ($action == "update") {
        $query = "UPDATE categories SET status = 'accepted' WHERE id = :id";
    }else if ($action == "del") {
        $query = "DELETE FROM categories WHERE id = :id";
    }else{
        return;
    }
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
function update_post_adm(object $pdo, int $id, string $action){
    if ($action == "update") {
        $query = "UPDATE posts SET status = 'accepted' WHERE id = :id";
    }else if ($action == "del") {
        $query = "DELETE FROM posts WHERE id = :id";
    }else{
        return;
    }
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}