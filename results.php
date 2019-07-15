<?php

    // Including the DB connection
    include_once 'includes/dbh.inc.php';
    // Including the SQL Queries
    include_once 'includes/queries.inc.php';

    $inputs_entered['first'] = "";
    $inputs_entered['last'] = "";
    $inputs_entered['keywords'] = "";
    $inputs_entered['title'] = "";
    $inputs_entered['year'] = "";
    $inputs_entered['number'] = "";


    // Taking the variables from the Form
    $first =    mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['first'])));
    $last =     mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['last'])));
    $keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
    $title =    mysqli_escape_string($conn, strtolower(trim($_POST['title'])));
    $edition =  explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));

    $inputs_entered = get_where_queries($inputs_entered, $first, $last, $keywords, $title, $edition);

    $inputs_entered = array_filter($inputs_entered);

    // $SQL_QUERY is imported from 'includes/queries.inc.php'
    $search_query = $SQL_QUERY . join(" AND \n" ,$inputs_entered) . ";";
    
    // echo $search_query;

    // Sending the Query to the DB
    $results = mysqli_query($conn, $search_query); // $conn is imported from the /includes/ folder


    // Getting the count of the rows, given by the result of the Query
    $result_check = mysqli_num_rows($results);

    if($result_check > 0){
        while($row = mysqli_fetch_assoc($results)){
            // Here $row becomes an associative array
            echo '<br><strong>Authors</strong>: ' . $row['authors'] . "<br>" . 
                 '<strong>Title</strong>: ' . $row['title'] . "<br>" . 
                 '<strong>Abstract</strong>: ' . $row['abstract'] . "<br>" .
                 '<strong>Keywords</strong>: ' . $row['keywords'] ."<br><br>";;
        }
    }
    else{ echo 'No articles were found.'; }


?>