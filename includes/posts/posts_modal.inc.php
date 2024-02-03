<?php

declare(strict_types=1);

function get_all_posts(object $pdo, int $nbr_off, int $nbr_limit, string $searchValue)
{
    $query = "CALL GetPostsIndexSearch(:offset, :limit, :searchValue)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':offset', $nbr_off, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $nbr_limit, PDO::PARAM_INT);
    $stmt->bindParam(':searchValue', $searchValue, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_posts_number(object $pdo)
{
    $query = "SELECT COUNT(*) as nbr_posts FROM posts WHERE !isarchived";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['nbr_posts'];
}

function get_posts_number_search(object $pdo, string $searchValue)
{
    $query = "SELECT COUNT(*) as nbr_posts FROM posts WHERE !isarchived AND title LIKE :searchValue";
    $stmt = $pdo->prepare($query);
    $newSeaVal = '%' . $searchValue . '%';
    $stmt->bindParam(':searchValue', $newSeaVal, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['nbr_posts'];
}
function get_posts_json(object $pdo)
{
    $query = "CALL GetPostsJson()";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

//SQL INJECTION TEST :/
//$searchValue = "'; DROP TABLE test; --";
//CRAETE POST

function id_notexist(object $pdo, string $id)
{
    $query = "SELECT COUNT(*) FROM categories WHERE id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    return $count === 0;
}

function get_categsids(object $pdo)
{
    $query = "SELECT id,name FROM categories WHERE status = 'accepted' ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function set_post(object $pdo, string $title, string $description, string $post_img, string $text, string $user_id)
{
    $query = "INSERT INTO posts(title, description, post_img, text, user_id, created_at, updated_at) VALUES(:title, :description, :post_img, :text, :user_id, NOW(), NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":post_img", $post_img);
    $stmt->bindParam(":text", $text);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();

    $postId = $pdo->lastInsertId();
    return $postId;
}


function set_categ(object $pdo, string $name)
{
    $query = "INSERT INTO categories(name,created_at,updated_at) VALUES(:name,NOW(),NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    $categID = $pdo->lastInsertId();
    return $categID;
}

function set_post_categ(object $pdo, $newPostID, $categID)
{
    $query = "INSERT INTO post_categories(post_id ,category_id ) VALUES(:post_id,:categ_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":post_id", $newPostID);
    $stmt->bindParam(":categ_id", $categID);
    $stmt->execute();
}

//HANDLE EDIT OOST

function checkPostHaveImg(object $pdo, string $img_name, string $postid)
{
    $query = "SELECT post_img FROM posts WHERE id=:post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":post_id", $postid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['post_img'])) {
        $db_img_name = $result['post_img'];
        return ($db_img_name === $img_name);
    }

    return false;
}

function getUserPostsIDS(object $pdo, $user_id)
{
    $query = "SELECT id FROM posts WHERE user_id = :userid";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":userid", $user_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_post(object $pdo, int $post_id)
{
    $query = "SELECT * FROM posts WHERE id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_categs_by_id(object $pdo, int $post_id)
{
    $query = "SELECT category_id as id FROM post_categories WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function updatepost(object $pdo, string $title, string $desc, string $img_name, string $text, $user_id, $post_id)
{
    $query = "UPDATE posts
              SET title=:title, description=:desc,
              post_img=:imgname, text=:txt, status = 'pending' ,updated_at = NOW()
              WHERE id = :postid AND user_id = :userid";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":desc", $desc);
    $stmt->bindParam(":imgname", $img_name);
    $stmt->bindParam(":txt", $text);
    $stmt->bindParam(":postid", $post_id);
    $stmt->bindParam(":userid", $user_id);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        return "Row updated successfully";
    } else {
        return "Something went wrong";
    }
}

function delete_assign_ids(object $pdo, string $post_id)
{
    $query = "DELETE FROM post_categories WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->execute();
}
