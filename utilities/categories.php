<?php

// Splits a posts categories based on "," (comma) delimitation.
function split_categories($categories)
{
    return explode(",", $categories);
}

// Gets an array of all the categories in the database.
function get_all_categories($db)
{
    $categories = [];

    $query = "SELECT categories FROM posts";

    $statement = $db->prepare($query);

    $statement->execute();

    while ($row = $statement->fetch()) {
        $split_items = split_categories($row["categories"]);
        for ($category = 0; $category < count($split_items); $category++) {
            array_push($categories, $split_items[$category]);
        }
    }

    return $categories;
}
