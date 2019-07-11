<?php
    include_once 'D:\tu-cce\includes\dbh.inc.php';

    // Taking the variables from the Form
    $first = $_POST["first"];
    $last = $_POST["last"];
    $keywords =  explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords'])));
    $title = $_POST["title"];
    $abstract = $_POST["abstract"];
    $url = $_POST["url"];
    $edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));


    // Inserting to Articles table
    $sql_art = "INSERT INTO articles 
                        (title, abstract, url)
                VALUES  ('$title', '$abstract', '$url');";

    $conn -> query($sql_art);


    // Inserting into Keywords table 
    foreach($keywords as $word ){
        $word_search = "SELECT word FROM keywords
                        WHERE word = '$word';";

        $query_matches = mysqli_query($conn, $word_search);

        $row_count = mysqli_num_rows($query_matches);

        if($row_count == 0){
            echo $word . " I AM IN <br>";
        
            $sql_keyword = "INSERT INTO keywords 
                                (word)
                       VALUES   ('$word');";

            $conn -> query($sql_keyword);
        }
    }