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

    $search_query = "";
    
    // When we have atleast one successful query $row_found = True
    $row_found = False;


    // Taking the variables from the Form
    $first =    mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['first'])));
    $last =     mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['last'])));
    $keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
    $title =    mysqli_escape_string($conn, strtolower(trim($_POST['title'])));
    $edition =  explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));

    if($first or $last or $keywords or $title or $edition){

        // $SQL_QUERY is imported from 'includes/queries.inc.php'
        foreach($keywords as $word){

            $word = mysqli_escape_string($conn, $word);
            
            $inputs_entered = get_where_queries($inputs_entered, $first, $last, $word, $title, $edition);

            $inputs_entered = array_filter($inputs_entered);
            
            $search_query .= " " . $SQL_QUERY . join(" AND \n" ,$inputs_entered) . "; ";
        }

        // echo $search_query;

        if(mysqli_multi_query($conn, $search_query)){
            $unique_queries = [];
            
            do{
                // Store the first result
                if($result = mysqli_store_result($conn)){
                    // Fetch one and one row

                    while($row = mysqli_fetch_assoc($result)){
                        $row_found = True;

                        $unique_queries[] = "<br><strong>Authors</strong>: " . $row['authors'] . "<br>" . 
                                            "<strong>Title</strong>: " .       $row['title'] . "<br>" . 
                                            "<strong>Abstract</strong>: " .    $row['abstract'] . "<br>" .
                                            "<strong>Keywords</strong>: " .    $row['keywords'] ."<br>" .
                                            "<strong>Edition</strong>: " .     $row['EditionYear'] . "/" . $row['EditionNumber'] . "<br>" .
                                            '<a href="includes/downloads.inc.php?article_number=' . urlencode($row['num'])
                                                . "&edition_year=" . urlencode($row['year'])
                                                . "&edition_number=" . urlencode($row['number'])
                                                . '">View complete article</a> (#' . $row['num'] . " from " . $row['EditionYear'] . "/" . $row['EditionNumber'] . ")<br>";
                    }
                    // Free the result
                    mysqli_free_result($result);
                }
            
            // While there is a next result
            }while(mysqli_more_results($conn) and mysqli_next_result($conn));

            // Make all queries unique
            $unique_queries = array_unique($unique_queries);

            // print_r($unique_queries);

            // Display them on the browser
            foreach($unique_queries as $query){
                echo $query;
            }
        }
    }

    if(!$row_found){ echo 'No articles were found.'; }
