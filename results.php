<?php

    // Including the DB connection
    include_once 'includes/dbh.inc.php';
    // Including the SQL Queries
    // include_once 'includes/queries.inc.php';

    // Taking the variables from the Form
    $first = mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['first'])));
    $last = mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['last'])));
    $keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
    $title = mysqli_escape_string($conn, strtolower(trim($_POST['title'])));
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));


    $sql_names = "SELECT authors.f_name, authors.l_name, articles.title, articles.abstract, articles.num, editions.year, editions.number FROM article_authors
                     JOIN articles ON articles.id = article_authors.article_id
                     JOIN authors ON authors.id = article_authors.author_id
                     LEFT JOIN editions ON articles.id = editions.article_id
                     WHERE LOWER(authors.f_name) = '$first' OR LOWER(authors.l_name) = '$last';";
    
    // Sending the Query to the DB
    $results = mysqli_query($conn, $sql_names); // $conn is imported from the /includes/ folder
    
    // Getting the count of the rows, given by the result of the Query
    $result_check = mysqli_num_rows($results);

    if($result_check > 0){
        while($row = mysqli_fetch_assoc($results)){
            // Here $row becomes an associative array
            echo '<strong>Author</strong>: ' . $row['f_name'] . " " . $row['l_name'] . "<br>"
                . '<strong>Title</strong>: ' . $row['title'] . "<br>"
                . '<strong>Abstract</strong>: ' . $row['abstract'] . "<br>"
                . '<a href="includes/downloads.inc.php?article_number=' . urlencode($row['num'])
                . "&edition_year=" . urlencode($row['year'])
                . "&edition_number=" . urlencode($row['number'])
                . '">View complete article</a> (#' . $row['num'] . " from " . $row['year'] . "/" . $row['number'] . ")<br><br>";

        }
    }
    else{ echo 'No articles were found.'; }


?>