<?php

// Including the DB connection
include_once 'includes/dbh.inc.php';
// Including the SQL Queries
// include_once 'includes/queries.inc.php';

// Taking the variables from the Form
$first = strtolower(preg_replace('/\s+/', '', $_POST['first']));
$last = strtolower(preg_replace('/\s+/', '', $_POST['last']));
//$keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
$keywords = explode(",", trim(preg_replace('/\s+/', '', $_POST['keywords']))); // Splitting the keywords
$title = strtolower(trim($_POST['title']));
$edition = explode("/", trim(preg_replace('/\s+/', '', $_POST['edition'])));
//$new_edition = ['0','0'];

$keyword = $keywords[0];
//echo ($title == '').'<br>';

echo "<b>Debug:<br> First: </b>" . $first . ' is '. $isFirstOk = (preg_match('/^[a-zA-Z]{3,100}$/', $first));
echo " <b> Last:</b> " . $last . ' is '. $isLastOk = (preg_match('/^[a-zA-Z]{3,100}$/', $last));
echo " <b>Title:</b> " . $title . ' is '. $isTitleOk = (preg_match('/^[a-zA-Z]{3,100}$/', $title));
echo " <b>Keyword:</b> ". $keyword . ' is '. $isKeywordOk = (preg_match('/^[a-zA-Z\-]{3,100}$/', $keyword));
if ($edition != '') {
    $new_edition = preg_split('/\//', $_POST['edition']);
    $editionNum = $new_edition[0];
    if (isSet($new_edition[1])) {
        $editionYear = $new_edition[1];
    }else {
        $isEditionYearOk = false;
        $editionYear = 0;
    }
    echo " <b>Year</b> " . $editionYear . ' is '. $isEditionYearOk = (preg_match('/^(20|19){1}[\d]{2}$/', $editionYear));
    echo " <b>Num:</b> " . $editionNum  . ' is ' . $isEditionNumOk= (preg_match('/^[1|2]{1}$/', $editionNum));
} else {
    $isEditionNumOk = false;
    $isEditionYearOk = false;
}
echo "<br>";

$sql_names = "SELECT distinct art.title, art.abstract , 
	(SELECT group_concat(keywords.word) 
	from keywords 
	join article_keywords on keywords.id = article_keywords.keyword_id
	where article_keywords.article_id = art_aut.article_id 
    group by article_keywords.article_id
	) as keywords, 
                
    (SELECT group_concat(authors.f_name,' ',authors.l_name)
	from authors 
	join article_authors on authors.id = article_authors.author_id
	where article_authors.article_id = art_aut.article_id
    group by article_authors.article_id
	) as authors, 
                
    e.year as 'EditionYear',
    e.number as 'EditionNumber'
FROM article_authors art_aut
         JOIN articles art ON art.id = art_aut.article_id
         JOIN authors aut  ON aut.id = art_aut.author_id
         JOIN article_keywords art_key ON art.id = art_key.article_id
         JOIN keywords k on art_key.keyword_id = k.id
         JOIN editions e on art.id = e.article_id
/*TODO make possible some fields to be missed*/                     /* Test Data*/
WHERE  ((!$isFirstOk)       OR (LOWER(aut.f_name) = '$first'))      /* Dragi    */
   AND ((!$isLastOk)        OR (LOWER(aut.l_name) = '$last'))       /* Kimovski */
   AND ((!$isKeywordOk)     OR (LOWER(k.word)    like('%$keyword%'))) /* trusted  */
   AND ((!$isTitleOk)       OR (LOWER(art.title) like ('%$title%')))/* Powerful */
   AND ((!$isEditionYearOk) OR (e.year   = '$editionYear'))         /* 2016     */ 
   AND ((!$isEditionNumOk)  OR (e.number = '$editionNum'))          /* 1        */
;";

// Sending the Query to the DB
$results = mysqli_query($conn, $sql_names); // $conn is imported from the /includes/ folder
//echo $results;
// Getting the count of the rows, given by the result of the Query
$result_check = mysqli_num_rows($results);

if ($result_check > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
        // Here $row becomes an associative array
        echo '<strong>Authors List</strong>: ' . $row['authors']
            . "<br>" . '<strong>Title</strong>: ' . $row['title']
            . "<br>" . '<strong>Abstract</strong>: ' . $row['abstract'] . ""
            . "<br>" . '<strong>Keywords</strong>: ' . $row['keywords'] . ""
            . "<br>" . '<strong>Edition Year</strong>: ' . $row['EditionYear'] . ""
            . "<br>" . '<strong>Edition Number</strong>: ' . $row['EditionNumber'] . "<br><br>";
    }
} else {
    echo '<b>No articles were found for this search: </b>' . $first . ' ,' . $last . ' ,' . $title . ' ,' . $keyword. ' ,' . $editionYear . ' ,' . $editionNum;
}


?>