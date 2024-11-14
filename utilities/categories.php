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

    $keyword = '%' . $keyword . '%';

    $category_name_search = "SELECT *
                             FROM
                                 posts p
                             JOIN users u ON
                                 p.author = u.user_id
                             WHERE
                                 u.first_name LIKE :fn
                             OR
                                 u.last_name LIKE :ln";

    $statement = $db->prepare($category_name_search);

    $statement->bindValue(":fn", $keyword);
    $statement->bindValue(":ln", $keyword);

    $statement->execute();

    return $statement->fetchAll();
}
