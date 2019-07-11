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
        $last_article_query = "SELECT id FROM". " $table_name ".
                               "ORDER BY id DESC
                               LIMIT 1;";

        $query_matches = mysqli_query($connection, $last_article_query);

        $last_id = mysqli_fetch_assoc($query_matches)["id"];

        return $last_id;
    }


    function articles_keywords_insert($keywords_len, $connection){
        
        // Getting the id of the last article
        $last_article_id = get_last_id('articles', $connection);

        // Getting the id of the last keyword
        $last_keyword_id = get_last_id('keywords', $connection);

        while($keywords_len > 0){
            $query = "INSERT INTO article_keywords
                             (article_id, keyword_id)
                      VALUES ('$last_article_id', '$last_keyword_id');";
            $connection -> query($query);

            $last_keyword_id--;
            $keywords_len--;
        }

    }