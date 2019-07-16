<?php


    function validate_article($title, $abstract, $number, $connection){

        $query = "SELECT title, abstract, num FROM articles
                  WHERE title = '$title' AND
                    abstract = '$abstract' AND
                    num = '$number';";

        echo $query . "<br><br>";
        $query_successful = mysqli_query($connection, $query);

        $row_count = mysqli_num_rows($query_successful);

        if($row_count){
            return False;
        }

        return True;
        
    }


    function validate_title_input($title){
        
        if(!$title){
            echo "You have to enter a title!";
            return False;
        }

        return True;

    }

    function validate_authors_input($authors){

        if(!$authors){ echo "<br>You have to enter an author!<br>"; return False; }

        foreach($authors as $author){

            $author = explode(" ", trim($author));
            $fname_match = preg_match('/^[a-zA-Z]{3,100}$/', $author[0]); 
            $lname_match = preg_match('/^[a-zA-Z]{3,100}$/', $author[1]);

            if( !$fname_match or
                !$lname_match){ echo "<br>The way you entered the authors is wrong!<br>The right way is [George Peterson,Peter Jameson,...etc]<br>"; 
                                return False; }
        }
        
        return True;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////
    function validate_author_name($author){

        $author = explode(" ", trim($author));
        if(sizeof($author) < 2){
            echo "<br>The way you entered the authors is wrong!<br>The right way is [George Peterson,Peter Jameson,...etc]<br>";
            return null;
        }

        return $author;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////
    function validate_edition($year, $number){

        if(is_numeric($year) and is_numeric($number)){
            if(strlen($year) == 4 and ((int)$number == 1 or (int)$number == 2) ){
                return True;
            }
        }

        return False;
    }
