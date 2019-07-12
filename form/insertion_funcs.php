<?php

    function articles_insert($title, $abstract, $url, $connection){
    /**
      * Inserts title, abstract and url to the MySQL database
      *
      * @param string $title
      * @param string $abstract Overview of an article
      * @param string $url Download URL for an article
    */

        $sql_art = "INSERT INTO articles 
                            (title, abstract, url)
                    VALUES  ('$title', '$abstract', '$url');";

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
        
        // Getting the id of the last article
        $first_id = get_last_id($first_table, $connection);


        $existing_ids = [];

        // Adding all keywords' ids that are already in our table, to the $existing_ids
        foreach($keywords as $word){
            $keyword_available = "SELECT id, word FROM $second_table"."s ".
                                 "WHERE word = '$word';";

            $curr_match = mysqli_query($connection, $keyword_available);
            $curr_row = mysqli_fetch_assoc($curr_match);
            
            // Add id to the array of already used ids
            if($curr_match){
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