<?php

    // Including the DB connection
    include_once 'includes/dbh.inc.php';
    // Including the SQL Queries
    // include_once 'includes/queries.inc.php';

    // Taking the variables from the Form
    $first = strtolower(preg_replace('/\s+/', '', $_POST['first']));
    $last = strtolower(preg_replace('/\s+/', '', $_POST['last']));
    $keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
    $title = strtolower(trim($_POST['title']));
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));
    

    $sql_names =    "SELECT authors.f_name, authors.l_name, articles.title, articles.abstract FROM article_authors
                    JOIN articles ON articles.id = article_authors.article_id
                    JOIN authors ON authors.id = article_authors.author_id
                    WHERE LOWER(authors.f_name) = '$first' OR LOWER(authors.l_name) = '$last';";
    
    // Sending the Query to the DB
    $results = mysqli_query($conn, $sql_names); // $conn is imported from the /includes/ folder
    
    // Getting the count of the rows, given by the result of the Query
    $resultCheck = mysqli_num_rows($results);

    if($resultCheck > 0){
        while($row = mysqli_fetch_assoc($results)){
            // Here $row becomes an associative array
            echo '<strong>Author</strong>: ' . $row['f_name'] . " " . $row['l_name'] . "<br>" . '<strong>Title</strong>: ' . $row['title'] . "<br>" . '<strong>Abstract</strong>: ' . $row['abstract'] ."<br><br>";
        }
    }
    else{ echo 'No articles were found.'; }


?>