<?php

declare(strict_types=1);

function get_all_pending_posts(object $pdo)
{
    return get_pending_posts($pdo);
}

function get_all_users(object $pdo, string $orderby)
{
    return get_users($pdo, $orderby);
}

//HANDLE CHANGE ROLE DATA

function is_inputs_invalid(string $user_id, string $role, array $roles_allowed)
{
    if (empty($user_id) || empty($role)) {
        return true;
    }

    if (!in_array($role, $roles_allowed)) {
        return true;
    }
    return false;
}

function change_role(object $pdo, string $user_id, string $role)
{
    $roleBool = 0;
    if ($role == "admin") {
        $roleBool = 1;
    }
    update_user_role($pdo, $user_id, $roleBool);
}

//HANDLE SINGLE PENDING POST
function get_single_pen_post(object $pdo, int $id)
{
    return get_pen_post($pdo, $id);
}

function get_categs(object $pdo)
{
    return get_pen_categs($pdo);
}

//HANDLE CATEGS
function change_categ(object $pdo, int $id, string $action)
{
    update_categ($pdo, $id, $action);
}

function change_post_adm(object $pdo, int $id, string $action)
{
    update_post_adm($pdo, $id, $action);
}

//Hndle Users Messages

function getUsersMsgs($pdo)
{
    return get_users_msgs($pdo);
}

function deleteUserMsg($pdo, $id_msg)
{
    delete_user_msg($pdo, $id_msg);
}
function makeAllSeen($pdo)
{
    make_msgs_seen($pdo);
}
