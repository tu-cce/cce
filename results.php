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

// When we have at least one successful query $row_found = True
    $row_found = False;


    // Taking the variables from the Form
$first = mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['first'])));
$last = mysqli_escape_string($conn, strtolower(preg_replace('/\s+/', '', $_POST['last'])));
$keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
$title = mysqli_escape_string($conn, strtolower(trim($_POST['title'])));
$edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));

    if($first or $last or $keywords or $title or $edition){

        // $SQL_QUERY is imported from 'includes/queries.inc.php'
        foreach($keywords as $word){

            $word = mysqli_escape_string($conn, $word);

            $inputs_entered = get_where_queries($inputs_entered, $first, $last, $word, $title, $edition);

            $inputs_entered = array_filter($inputs_entered);

            $search_query .= " " . $SQL_QUERY . join(" AND \n", $inputs_entered) . ";";

            $total_rows = mysqli_num_rows($conn->query($search_query));

            echo "<h2>" . $total_rows . " articles were found.</h2>";

        }

        // echo $search_query;

        if(mysqli_multi_query($conn, $search_query)){
            $unique_queries = [];

            do{
                //Echo how many articles were found

                // Store the first result
                if($result = mysqli_store_result($conn)){
                    // Fetch one and one row

                    while($row = mysqli_fetch_assoc($result)){
                        $row_found = True;

                        $authors_partial = explode(",", $row['authors']);
                        $authors_spacer = implode(', ', $authors_partial);

                        $keywords_partial = explode(",", $row['keywords']);
                        $keywords_spacer = implode(', ', $keywords_partial);

                        $unique_queries[] =
                            "<article>" .
                            "<header>" .
                            "<h2>" . $row['title'] . "</h2> " .
                            "<h3>" . $authors_spacer . "</h3>" .
                            "</header>" .
                            "<section>" .
                            "<p><strong>Abstract: </strong>" . $row['abstract'] . "</p>" .
                            "<p><strong>Keywords: </strong>" . $keywords_spacer . "</p>" .
                            "<p><strong>Edition: </strong>" . $row['EditionYear'] . "/" . $row['EditionNumber'] . "</p>" .
                            '<a href="includes/downloads.inc.php?article_number='
                            . urlencode($row['num'])
                            . "&edition_year=" . urlencode($row['EditionYear'])
                            . "&edition_number=" . urlencode($row['EditionNumber'])
                            . '" class="download-PDF" target="_blank">View complete article</a>' .
                            "</section>" .
                            "</article>";
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
