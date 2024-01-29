<?php

declare(strict_types=1);

function get_post(object $pdo, int $id)
{
    $query = "SELECT posts.*, users.name AS author_name, GROUP_CONCAT(categories.name) AS category_names
              FROM posts
              LEFT JOIN post_categories ON posts.id = post_categories.post_id
              LEFT JOIN categories ON post_categories.category_id = categories.id
              LEFT JOIN users ON posts.user_id = users.id
              WHERE posts.id = :id AND posts.isarchived = 0 AND posts.status = 'accepted'
              GROUP BY posts.id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_post_comments(object $pdo, int $id)
{
    $query = "SELECT C.*,U.id AS user_id, U.name, U.username, U.profile_photo_path
              FROM comments AS C
              INNER JOIN posts AS P
              ON C.post_id = P.id
              INNER JOIN users AS U
              ON C.user_id = U.id
              WHERE post_id = :id
              ORDER BY created_at DESC";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_postsIDS(object $pdo, int $id_check)
{
    $query = "SELECT id FROM posts WHERE id = :id_check AND !isarchived AND status='accepted'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_check', $id_check, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result !== false;
}

function get_6categories(object $pdo)
{
    $query = "SELECT * FROM categories WHERE status = 'accepted' ORDER BY RAND() DESC LIMIT 6";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_3related_posts(object $pdo, int $id)
{
    $query = "CALL GetRelatedPosts(:id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function insert_comment(object $pdo, int $userID, int $postID, string $cmnt)
{
    $query = "INSERT INTO comments(post_id,user_id,content,created_at)
              VALUES (:postid,:userid,:content,NOW())";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":postid", $postID);
    $stmt->bindParam(":userid", $userID);
    $stmt->bindParam(":content", $cmnt);
    $stmt->execute();
}
