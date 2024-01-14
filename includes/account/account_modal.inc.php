<?php

declare(strict_types=1);


function get_posts(object $pdo,int $id, string $status, bool $isArchived){
    $archivedCondition = $isArchived ? 'isarchived' : '!isarchived';

    $query = "SELECT * FROM posts 
              WHERE $archivedCondition 
              AND status = :status 
              AND user_id = :id
              ORDER BY title";
              
    if ($status === 'pending') {
        $query = "SELECT * FROM posts 
                  WHERE status = :status 
                  AND user_id = :id
                  ORDER BY title";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":status", $status);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function get_nbr_penposts(object $pdo, int $id){
    $query = "SELECT COUNT(*) AS nbr_pen_posts FROM posts
              WHERE status = 'pending' 
              AND user_id = :id
              ORDER BY title";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_isarchived(object $pdo, int $post_id,int $isArchived,int $user_id){
    $query = "UPDATE posts SET isarchived = :isarchived 
              WHERE posts.id = :post_id AND posts.user_id = :userid";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":isarchived", $isArchived);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->bindParam(":userid", $user_id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Update successful'];
    } else {
        return ['success' => false, 'message' => 'Update failed'];
    }
}