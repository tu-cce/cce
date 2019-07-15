<?php

    include_once 'D:\tu-cce\includes\dbh.inc.php';
    include_once 'validations.php';


    ////////////////////////////////////////////////////////////////////////////////////////////
    function articles_insert($title, $abstract, $number, $connection){
    /**
      * Inserts title, abstract and number of an article to the database
      *
      * @param string $title
      * @param string $abstract Overview of an article
      * @param string $number   Download number for an article
    */
        if(!validate_article($title, $abstract, $number, $connection)){
            return False;
        }

        $sql_art = "INSERT INTO articles 
                            (title, abstract, num)
                    VALUES  ('$title', '$abstract', '$number');";

        $connection -> query($sql_art);

        return True;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////
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

    ////////////////////////////////////////////////////////////////////////////////////////////
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

    ////////////////////////////////////////////////////////////////////////////////////////////
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

        echo $query . "<br>";
        $connection -> query($query);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////
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
            $word = mysqli_escape_string($connection, $word);

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

    ////////////////////////////////////////////////////////////////////////////////////////////
    function authors_insert($authors, $connection){
    /**
      * Inserts the elements of the $authors array into the authors table
      *
      * @param array $authors  Array with the names of all authors for a certain article
    */  

        $existing_ids = [];
        
        foreach($authors as $author){
            $author = mysqli_escape_string($connection, $author);

            $author = validate_author_name($author);
            if($author == null){ return null; }

            $first_name = ucfirst(strtolower(preg_replace('/\s+/', '', $author[0])));
            $last_name = ucfirst(strtolower(preg_replace('/\s+/', '', $author[1])));
            
            $collision_query = "SELECT id, f_name, l_name FROM authors
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
                $existing_ids[] = mysqli_fetch_assoc($query_matches)["id"];
                echo "$first_name $last_name already exists in our database.<br>";
            }
        }

        return $existing_ids;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////
    function articles_authors_insert($articles_table, $authors_table, $authors, $existing_ids, $connection){
    /**
      * Links an article with its authors
      *
      * @param string $articles_table  String representing the articles table
      * @param string $authors_table   String representing the authors table
      * @param array  $authors         All authors that are being inserted
      * @param array  $existing_ids    All authors that are being inserted who are already in the db
    */
        
        // Get the last id of articles table
        $last_article_id = get_last_id($articles_table, $connection);

        if(!empty($existing_ids)){
            $new_authors_count = sizeof($authors) - sizeof($existing_ids);
        }else{ 
            $new_authors_count = sizeof($authors); 
        }

        $last_author_id = get_last_id($authors_table, $connection);

        while($new_authors_count > 0){
            many_to_many_insert($articles_table, $authors_table, $last_article_id, $last_author_id, $connection);
            $last_author_id--;
            $new_authors_count--;
        }

        $existing_authors_count = sizeof($existing_ids) - 1;

        // Inserting all already existing keywords to the Many-To-Many table
        while($existing_authors_count >= 0){
            many_to_many_insert($articles_table, $authors_table, $last_article_id, $existing_ids[$existing_authors_count], $connection);
            
            $existing_authors_count--;
        }
        
    }

    ////////////////////////////////////////////////////////////////////////////////////////////
    function edition_insert($year, $number, $connection){
    /**
      * Inserts the edition of an article
      *
      * @param string $year
      * @param string $number
    */
        
        $last_art_id = get_last_id('article', $connection);
        
        $query = "INSERT INTO editions
                         (year, number, article_id)
                  VALUES ('$year', '$number', '$last_art_id');";

        echo $query;
        
        $connection -> query($query);
    }