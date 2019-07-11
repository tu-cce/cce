<?php
    include_once 'D:\tu-cce\includes\dbh.inc.php';
    include_once 'insertion_funcs.php';

    // Taking the variables from the Form
    $first = $_POST["first"];
    $last = $_POST["last"];
    $keywords =  explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords'])));
    $title = $_POST["title"];
    $abstract = $_POST["abstract"];
    $url = $_POST["url"];
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));


    // Inserting to Articles table
    if($title and $abstract and $url and $conn){ 
        articles_insert($title, $abstract, $url, $conn);
    }

    // Inserting into Keywords table 
    if(!empty($keywords) and $keywords[0] != ""){
        keywords_insert($keywords, $conn); 
    }
