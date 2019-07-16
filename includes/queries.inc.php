<!-- Queries -->

<?php

$SQL_QUERY =    "SELECT distinct art.title, art.abstract , 
                    (SELECT group_concat(keywords.word) 
                    FROM keywords 
                    JOIN article_keywords ON keywords.id = article_keywords.keyword_id
                    WHERE article_keywords.article_id = art_aut.article_id 
                    GROUP BY article_keywords.article_id
                    ) AS keywords, 
                                
                    (SELECT group_concat(authors.f_name,' ',authors.l_name)
                    FROM authors 
                    JOIN article_authors ON authors.id = article_authors.author_id
                    WHERE article_authors.article_id = art_aut.article_id
                    GROUP BY article_authors.article_id
                    ) AS authors,

                    e.year AS 'EditionYear',
                    e.number AS 'EditionNumber'
                    FROM article_authors art_aut
                    JOIN articles art ON art.id = art_aut.article_id
                    JOIN authors aut  ON aut.id = art_aut.author_id
                    JOIN article_keywords art_key ON art.id = art_key.article_id
                    JOIN keywords k on art_key.keyword_id = k.id
                    JOIN editions e on art.id = e.article_id" . "\n WHERE ";


function get_where_queries($inputs, $first, $last, $keyword, $title, $edition){
    /**
      * Returns an associative array with all parts of the WHERE statement that is used to search.
      *
      * @param array    $inputs
      * @param string   $first
      * @param string   $last
      * @param string    $keywords
      * @param string   $title
      * @param array    $edition
    */

    foreach($inputs as $key => $input){
        switch($key){
            case "first":
                if($first != ''){
                    $inputs[$key] = "LOWER(aut.f_name) = '$first'";
                }
                break;
            case "last":
                if($last != ''){
                    $inputs[$key] = "LOWER(aut.l_name) = '$last'";
                }
                break;
            case "keywords":
                if($keyword != ''){
                    $inputs[$key] = "LOWER(k.word) = '$keyword' OR LOWER(art.abstract) LIKE '$keyword'";
                }
                break;
            case "title":
                if($title != ''){
                    $inputs[$key] = "LOWER(art.title) like ('$title')";
                }
                break;
                
            case "year":
                if($edition[0] != ''){
                    $inputs[$key] = " e.year = '$edition[0]' ";
                }
                break;
            case "number":
                if(isset($edition[1])){
                    if($edition[1] != ''){
                        $inputs[$key] = " e.number = '$edition[1]' ";
                    }
                }
        }
    }

    return $inputs;
}
