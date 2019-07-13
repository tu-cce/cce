<?php
    include_once 'D:\tu-cce\includes\dbh.inc.php';
    include_once 'insertion_funcs.php';


    // Taking the variables from the Form in index.php
    $authors = explode(",", $_POST['authors']);
    $keywords =  explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords'])));
    $title = $_POST["title"];
    $abstract = $_POST["abstract"];
    $number = $_POST["number"];
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));

    // If you don't insert an article you cant insert keywords
    // Inserting to Articles table
    if($title and $abstract and $number and $conn){

        articles_insert($title, $abstract, $number, $conn);

        // Inserting into Keywords table 
        if(!empty($keywords) and $keywords[0] != ""){
            keywords_insert($keywords, $conn);
            
            // Inserting into the connecting Many-To-Many table
            articles_keywords_insert("article", "keyword", $keywords, $conn);
        }

        $existing_ids = authors_insert($authors, $conn);

        articles_authors_insert("article", "author", $authors, $existing_ids, $conn);
    }
