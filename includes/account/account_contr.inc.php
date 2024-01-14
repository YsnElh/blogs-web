<?php

declare(strict_types=1);

require_once "account_modal.inc.php";

function get_posts_contr(object $pdo, int $id, string $status = 'accepted', bool $isArchived = false){
    return get_posts($pdo,$id,$status,$isArchived);
}
function get_nbr_pen_posts(object $pdo, int $id){
    return get_nbr_penposts($pdo,$id);
    
}

function update_isarchived(object $pdo, int $post_id,int $isArchived,int $user_id){
    return set_isarchived($pdo,intval($post_id),$isArchived,$user_id);
}