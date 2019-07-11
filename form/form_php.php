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


    $sql_art = "INSERT INTO articles 
                        (title, abstract, url)
                VALUES  ('$title', '$abstract', '$url');";

    $conn -> query($sql_art);
    
    foreach($keywords as $word ){
        $if_stmnt = "SELECT word FROM keywords
                     WHERE word IS LIKE $word; ";

        if(!$if_stmnt){
            $results = mysqli_query($conn, $if_stmnt);

            $row = mysqli_fetch_assoc($results);
        
            $fetched_word = $row["word"];
            $sql_kw = "INSERT INTO keywords 
                                (word)
                       VALUES   ($fetched_word);";

            $conn -> query($sql_kw);
        }
    }