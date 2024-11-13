<?php

// Gets an array of all the categories in the database.
function get_all_categories($db)
{
    $categories = [];

    $query = "SELECT * FROM Categories";

    $statement = $db->prepare($query);

    $statement->execute();

    while ($row = $statement->fetch()) {
        array_push($categories, $row["name"]);
    }

    return $categories;
}

// Gets all of the posts in a given category.
function get_category_posts($db, $categories)
{
    $query = "SELECT p.post_id, first_name, last_name, written_content, image_content, post_date 
              FROM posts p
              JOIN users u
              ON p.author = u.user_id
              JOIN posts_categories pc
              ON p.post_id = pc.post_id
              JOIN categories c
              ON pc.category_id = c.category_id
              WHERE c.name = :category_name ;";

    $statement = $db->prepare($query);

    $statement->bindValue(":category_name", $categories);

    $statement->execute();

    return $statement->fetchAll();
}

function get_post_categories($db, $post)
{
    $query = "SELECT c.name
              FROM posts p
              JOIN users u ON
                  p.author = u.user_id
              JOIN posts_categories pc ON
                  p.post_id = pc.post_id
              JOIN categories c ON
                  pc.category_id = c.category_id
              WHERE
                  p.post_id = :post_id ;";

    $statement = $db->prepare($query);

    $statement->bindValue(":post_id", $post);

    $statement->execute();

    return $statement->fetchAll();
}

function post_search($db, $keyword)
{
    $found_posts = [];

    $first_name_search = "SELECT *
                          FROM posts p
                          JOIN users u
                          ON p.author = u.user_id
                          WHERE u.first_name LIKE :fn;";

    $first_name_statement = $db->prepare($first_name_search);

    $first_name_statement->bindValue(":fn", $keyword);

    $first_name_statement->execute();

    $first_name_matches = $first_name_statement->fetchAll();

    for ($post = 0; $post < count($first_name_matches); $post++) {
        array_push($found_posts, $first_name_matches[$post]);
    }

    $last_name_search = "SELECT *
                          FROM posts p
                          JOIN users u
                          ON p.author = u.user_id
                          WHERE u.last_name LIKE :ln;";


    $last_name_statement = $db->prepare($last_name_search);

    $last_name_statement->bindValue(":ln", $keyword);

    $last_name_statement->execute();

    $last_name_matches = $first_name_statement->fetchAll();

    for ($post = 0; $post < count($last_name_matches); $post++) {
        array_push($found_posts, $last_name_matches[$post]);
    }

    $category_name_search = "SELECT *
                             FROM posts p
                             JOIN users u
                             ON p.author = u.user_id
                             JOIN posts_categories pc
                             ON pc.post_id = p.post_id
                             JOIN categories c
                             ON c.category_id = pc.category_id
                             WHERE c.name LIKE :cat;";

    $category_name_statement = $db->prepare($category_name_search);

    $category_name_statement->bindValue(":cat", $keyword);

    $category_name_statement->execute();

    $category_name_matches = $first_name_statement->fetchAll();

    for ($post = 0; $post < count($category_name_matches); $post++) {
        array_push($found_posts, $category_name_matches[$post]);
    }

    return $found_posts;
}
