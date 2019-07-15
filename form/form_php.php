<?php
    include_once 'D:\tu-cce\includes\dbh.inc.php';
    include_once 'insertion_funcs.php';
    include_once 'validations.php';


    // Taking the variables from the Form in index.php
    $authors =      explode(",", $_POST['authors']);
    $keywords =     explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords'])));
    $title =        mysqli_escape_string($conn, $_POST["title"]);
    $abstract =     mysqli_escape_string($conn, $_POST["abstract"]);
    $number =       mysqli_escape_string($conn, $_POST["number"]);
    $edition =      explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));
    $edition[0] =   mysqli_escape_string($conn, $edition[0]);
    $edition[1] =   mysqli_escape_string($conn, $edition[1]);


    // If you don't insert an article you cant insert keywords
    // Inserting to Articles table
    if($title and $abstract and $number and $conn and validate_edition($edition[0], $edition[1])){

        $insertion_success = articles_insert($title, $abstract, $number, $conn);

        if($insertion_success){
            // Inserting into Keywords table 
            if(!empty($keywords) and $keywords[0] != ""){
                keywords_insert($keywords, $conn);
                
                // Inserting into the connecting Many-To-Many table
                articles_keywords_insert("article", "keyword", $keywords, $conn);
            }

            $existing_ids = authors_insert($authors, $conn);

            articles_authors_insert("article", "author", $authors, $existing_ids, $conn);
            edition_insert($edition[0], $edition[1], $conn);
        }
    }else{
        echo "Your article creation failed.";
    }
