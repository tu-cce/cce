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

        $connection ->query($sql_art);
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
                echo $word . " I AM IN <br>";
            
                $sql_keyword = "INSERT INTO keywords 
                                    (word)
                           VALUES   ('$word');";
    
                $connection -> query($sql_keyword);
            }
        }
    }