<?php

require_once "./includes/dbh.inc.php";
require_once 'panel_modal.inc.php';
require_once 'panel_contr.inc.php';

function handleUrlPage()
{
    if (!isset($_GET['manage-posts']) && !isset($_GET['manage-users']) && !isset($_GET['users-msgs'])) {
        header(("Location: ../../panel?manage-users"));
    }
}

function formatDateTime($dt)
{
    if (!$dt) {
        return "Not Set";
    }
    $dateTime  = new DateTime($dt);
    return $dateTime->format('j F Y, g:i A');
}

function toSeen()
{
    global $pdo;
    makeAllSeen($pdo);
}

$users = get_all_users($pdo, "name");
$pending_posts = get_all_pending_posts($pdo);
$categs = get_categs($pdo);
$users_msgs = getUsersMsgs($pdo);

function print_navbar()
{
    global $users_msgs;
    echo '<nav class="navbar navbar-expand-sm  navbar-dark mt-2" style="background-color: #0cc;border-radius:6px">';
    echo '<div class="navbar-collapse">';
    echo '<ul class="navbar-nav mr-auto">';
    if (isset($_GET['manage-users'])) {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="/panel?manage-users">Manage Users</a>';
    echo '</li>';
    if (isset($_GET['manage-posts'])) {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="/panel?manage-posts">Manage Posts</a>';
    echo '</li>';
    if (isset($_GET['users-msgs'])) {
        echo '<li class="nav-item active">';
    } else {
        echo '<li class="nav-item">';
    }
    echo '<a class="nav-link" href="/panel?users-msgs">Users Messages <span class="badge badge-info">' . ($users_msgs['unseen_msgs'] > 0 ? $users_msgs['unseen_msgs'] : "") . '</span></a>';
    echo '</li>';
    echo '</ul></div></nav>';
}

function print_page_content()
{
    global $users;
    global $pending_posts;
    global $categs;
    global $users_msgs;
    if (isset($_GET['manage-posts'])) {
        echo '<h2 class="tm-color-primary">Manage Pending Categories</h2>';
        print_pen_categs($categs);
        echo '<h2 class="tm-color-primary">Manage Pending Posts</h2>';
        print_pen_posts($pending_posts);
    } elseif (isset($_GET['manage-users'])) {
        print_users($users);
    } elseif (isset($_GET['users-msgs'])) {
        print_users_messages($users_msgs['users_msgs']);
    }
}
function print_users($users_data)
{
    echo '<h2 class="tm-color-primary">Users Management</h2>';
    echo '<h6>Order by name</h6>';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">id</th>';
    echo '<th scope="col">picture</th>';
    echo '<th scope="col">name</th>';
    echo '<th scope="col">email</th>';
    echo '<th scope="col">username</th>';
    echo '<th scope="col">creation date</th>';
    echo '<th scope="col">role</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($users_data as $user) {
        echo '<tr>';
        echo '<th scope="row">' . $user['id'] . '</th>';
        echo '<td><img src="./profile_images/' . $user['profile_photo_path'] . '" width="50px" alt="' . $user['name'] . ' profile image"></td>';
        echo '<td>' . $user['name'] . '</td>';
        echo '<td>' . $user['email'] . '</td>';
        echo '<td>' . $user['username'] . '</td>';
        echo '<td>' . formatDateTime($user['created_at']) . '</td>';
        echo '<td>';
        echo '<form action="./includes/panel/panel.inc.php" method="post">';
        echo '<input type="hidden" name="user-id" value="' . $user['id'] . '">';
        echo '<input type="hidden" name="form-name" value="update-role">';
        echo '<select name="user-role" id="user-role">';
        echo '<option value="user">user</option>';
        echo '<option value="admin" ' . ($user['isadmin'] ? 'selected' : '') . '>admin</option>';
        echo '</select>';
        echo '<button type="submit" class="btn btn-outline-primary btn-sm ml-1">Change</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function print_pen_posts($posts)
{
    if (count($posts) > 0) {
        echo '<div class="d-flex flex-row justify-content-start flex-wrap">';
        foreach ($posts as $p) {
            echo '<div class="card m-1">';
            echo '<img src="./post_imgs/' . $p['post_img'] . '" class="card-img-top" alt="Image of the post: ' . $p['title'] . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $p['title'] . '</h5>';
            echo '<p style="font-size:15px">';
            echo 'Update Date: ' . formatDateTime($p['updated_at']) . '<br>';
            echo '</p>';
            echo '<a href="/validate-post?id=' . $p['id'] . '" class="btn btn-outline-info btn-sm m-1">View</a>';
            echo '</div></div>';
        }
        echo '</div>';
    } else {
        echo '<h6>There is no pending posts</h6>';
    }
}

function print_pen_categs($pen_categs)
{
    if (count($pen_categs) > 0) {
        echo '<div class="d-flex flex-row justify-content-start flex-wrap"">';
        foreach ($pen_categs as $c) {
            echo '<div class="border d-inline-block p-1 m-1">';
            echo '<h6><b>' . $c['name'] . '</b></h6>';
            echo '<form action="./includes/panel/panel.inc.php" method="post">';
            echo '<input type="hidden" name="categ-id" value="' . $c['id'] . '">';
            echo '<input type="hidden" name="form-name" value="approve-categ">';
            echo '<button class="btn btn-outline-info btn-sm">Approve</button>';
            echo '</form>';
            echo '<form action="./includes/panel/panel.inc.php" method="post">';
            echo '<input type="hidden" name="categ-id" value="' . $c['id'] . '">';
            echo '<input type="hidden" name="form-name" value="del-categ">';
            echo '<button class="btn btn-outline-danger btn-sm">Delete</button>';
            echo '</form>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<h6>There is no pending categories</h6>';
    }
}

function print_users_messages($msgs)
{
    echo '<h2 class="tm-color-primary">Users Messages</h2>';
    if (count($msgs) > 0) {
        echo '<div class="d-flex flex-wrap">';
        foreach ($msgs as $msg) {
            echo '<div class="card m-1 ' . ($msg['seen'] ? "" : "border_highlighted") . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Name: ' . $msg['name'] . '</h5>';
            echo '<h6>Email: ' . $msg['email'] . '</h6>';
            echo '<h6>Subject: ' . $msg['subject'] . '</h6>';
            echo '<p style="font-size:15px">' . $msg['message'] . '</p>';
            echo '<form action="/includes/panel/panel.inc.php" method="post">';
            echo '<input type="hidden" name="form-name" value="del-msg">';
            echo '<input type="hidden" name="id_msg" value="' . $msg['id'] . '">';
            echo '<button class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>';
            echo '</form>';
            echo '</div></div>';
            //form-name
        }
        echo '</div>';
    }
    toSeen();
}


function unsetSessVars()
{
    unset($_SESSION["register_errors"]);
}
