<?php

declare(strict_types=1);

function delete_user_comment(object $pdo, int $cmntID, int $user_id, int $post_id){
    delete_comment($pdo, $cmntID, $user_id, $post_id);
}