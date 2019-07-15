<!-- Data Base Handler -->

<!-- file.inc.php is a convention for files that are being imported -->

<?php

//$connect = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);


    $dbServerName = "localhost";
    $dbUsername = "root";
    // TODO insert your connection data:
    $dbPassword = "bRoWcSkAzahov98";
    $dbName = "cce_mag2";

    $conn = mysqli_connect($dbServerName , $dbUsername, $dbPassword, $dbName);

?>