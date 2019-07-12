<?php

    include_once 'D:\tu-cce\includes\dbh.inc.php';

    function articles_insert($title, $abstract, $number, $connection){
    /**
      * Inserts title, abstract and number of an article to the database
      *
      * @param string $title
      * @param string $abstract Overview of an article
      * @param string $number   Download number for an article
    */

        $sql_art = "INSERT INTO articles 
                            (title, abstract, number)
                    VALUES  ('$title', '$abstract', '$number');";

        $connection -> query($sql_art);
    }



    function keywords_insert($keywords, $connection){
    /**
      * Inserts keywords to the MySQL database
      *
      * @param array  $keywords
    */    

        foreach($keywords as $word ){
            $word_search = "SELECT word FROM keywords
                             WHERE word = '$word';";
    
            $query_matches = mysqli_query($connection, $word_search);
    
            $row_count = mysqli_num_rows($query_matches);
    
            if($row_count == 0){
            
                $sql_keyword = "INSERT INTO keywords 
                                        (word)
                                VALUES  ('$word');";
    
                $connection -> query($sql_keyword);
            }
        }
    }


    function get_last_id($table_name, $connection){
    /**
      * Gets the last id of a table, based on $table_name
      *
      * @param string  $table_name
    */    

        $last_article_query = "SELECT id FROM " . $table_name . "s " .
                              "ORDER BY id DESC
                               LIMIT 1;";

        $query_matches = mysqli_query($connection, $last_article_query);

        $last_id = mysqli_fetch_assoc($query_matches)["id"];

        return $last_id;
    }


    function many_to_many_insert($first_table, $second_table, $first_id, $second_id, $connection){
    /**
      * Inserts ids of two tables into a Many-To-Many table
      *
      * @param string $first_table  The name of the first table
      * @param string $second_table The name of the second table
      * @param string $first_id     The id that belongs to the first table
      * @param string $second_id    The id that belongs to the second table
    */    

        $query = "INSERT INTO " . $first_table . "_" . $second_table . "s " .
                        "(".$first_table . "_id". ", " . $second_table . "_id ".")".
                        "VALUES ('$first_id', '$second_id');";

        echo $query;
        $connection -> query($query);
    }


    function articles_keywords_insert($first_table, $second_table, $keywords, $connection){
    /**
      * Inserts ids of two tables into a Many-To-Many table
      *
      * @param string $first_table  The name of the first table
      * @param string $second_table The name of the second table
      * @param array  $keywords     Array of all keywords coming from the form
    */  
            
        // Getting the id of the last article
        $first_id = get_last_id($first_table, $connection);


        $existing_ids = [];

        // Adding all keywords' ids that are already in our table, to the $existing_ids
        foreach($keywords as $word){
            $keyword_available = "SELECT id, word FROM $second_table"."s ".
                                 "WHERE word = '$word';";

            $curr_match = mysqli_query($connection, $keyword_available);
            
            // Add id to the array of already used ids
            if($curr_match){
                $curr_row = mysqli_fetch_assoc($curr_match);
                $existing_ids[] = $curr_row["id"];
            }
        }

        // If none of those keywords exists in our table $new_kws_count is not defined
        if(!empty($existing_ids)){
            $new_kws_count = sizeof($keywords) - sizeof($existing_ids);
            echo $new_kws_count;
        }

        // Getting the id of the last keyword in the keywords table
        $second_id = get_last_id($second_table, $connection);


        // Inserting all new keywords to the Many-To-Many table
        while($new_kws_count > 0){
            many_to_many_insert($first_table, $second_table, $first_id, $second_id, $connection);
            $second_id--;
            $new_kws_count--;
        }

        $existing_kws_count = sizeof($existing_ids) - 1;

        // Inserting all already existing keywords to the Many-To-Many table
        while($existing_kws_count >= 0){
            many_to_many_insert($first_table, $second_table, $first_id, $existing_ids[$existing_kws_count], $connection);
            
            $existing_kws_count--;
        }

    }


    function authors_insert($authors, $connection){
    /**
      * Inserts the elements of the $authors array into the authors table
      *
      * @param array $authors  Array with the names of all authors for a certain article
    */  

        foreach($authors as $author){

            $author = explode(" ", trim($author));
            if(sizeof($author) < 2){ 
                echo "The way you entered the authors is wrong!<br>The right way is [George Peterson,Peter Jameson,...etc]";
                return null;
            }

            $first_name = preg_replace('/\s+/', '', $author[0]);
            $last_name = preg_replace('/\s+/', '', $author[1]);
            
            $collision_query = "SELECT f_name, l_name FROM authors
                                WHERE f_name = '$first_name' AND 
                                      l_name = '$last_name';";
            
            $query_matches = mysqli_query($connection, $collision_query);

            $row_count = mysqli_num_rows($query_matches);

            if($row_count == 0){
                $insert_query = "INSERT INTO authors
                                        (f_name, l_name)
                                VALUES ('$first_name', '$last_name');";
                
                $connection -> query($insert_query);
            }else{
                echo "This author already exists in our database.";
            }
        }
    }