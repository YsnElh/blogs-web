<?php

declare(strict_types=1);

function delete_comment(object $pdo, int $cmntID, int $userid, int $post_id){
    $query = "DELETE FROM comments WHERE id = :id AND user_id = :iduser AND post_id = :postid";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $cmntID, PDO::PARAM_INT);
    $stmt->bindParam(':iduser', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':postid', $post_id, PDO::PARAM_INT);
    $stmt->execute();
}