<?php

declare(strict_types=1);

require_once "account_modal.inc.php";

function get_posts_contr(object $pdo, int $id, string $status = 'accepted', bool $isArchived = false)
{
    return get_posts($pdo, $id, $status, $isArchived);
}
function get_nbr_pen_posts(object $pdo, int $id)
{
    return get_nbr_penposts($pdo, $id);
}

function update_isarchived(object $pdo, int $post_id, int $isArchived, int $user_id)
{
    return set_isarchived($pdo, intval($post_id), $isArchived, $user_id);
}

function is_inputs_invalid(string $email, string $token, string $user_id, string $name)
{
    if (empty($email) || empty($token) || empty($name) || empty($user_id) || !is_numeric($user_id) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function update_sent_vef_at(object $pdo, string $user_id, bool $type = true)
{
    set_sent_vef($pdo, $user_id, $type);
}
function get_verf_sent(object $pdo, string $user_id)
{
    return get_verf_sent_at($pdo, $user_id);
}
function is_token_match(object $pdo, string $token, string $user_id)
{
    $tokenDb =  get_token($pdo, $user_id);
    return $tokenDb == $token;
}
function verify_email(object $pdo, string $user_id)
{
    return email_verify($pdo, $user_id);
}
