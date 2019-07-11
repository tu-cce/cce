<!-- Queries -->

<?php

function queryByName($first, $last){
    $sql_names =    "SELECT authors.f_name, authors.l_name, articles.title, articles.abstract FROM article_authors
                    JOIN articles ON articles.id = article_authors.article_id
                    JOIN authors ON authors.id = article_authors.author_id
                    WHERE authors.f_name = '$first' AND authors.l_name = '$last';";

    return $sql_names;
}

?>