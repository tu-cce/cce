<?php
    //TODO: change path
    include_once 'E:\Projects\CCE\includes\dbh.inc.php';
    include_once 'insertion_funcs.php';


    // Taking the variables from the Form in index.php
    $first = $_POST["first"];
    $last = $_POST["last"];
    $keywords =  explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords'])));
    $title = $_POST["title"];
    $abstract = $_POST["abstract"];
    $url = $_POST["url"];
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));


    // If you don't insert and article you cant insert keywords
    // Inserting to Articles table
    if($title and $abstract and $url and $conn){ 
        articles_insert($title, $abstract, $url, $conn);

        // Inserting into Keywords table 
        if(!empty($keywords) and $keywords[0] != ""){
            keywords_insert($keywords, $conn);
            
            // Inserting into the connecting Many-To-Many table
            articles_keywords_insert(sizeof($keywords), $conn);
        }
    }


