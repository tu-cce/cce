<!-- Data Base Handler -->

<!-- file.inc.php is a convention for files that are being imported -->

<?php

    $dbServerName = "localhost";
    $dbUsername = "root";
    $dbPassword = "root";
    $dbName = "cce_mag";

    $conn = mysqli_connect($dbServerName , $dbUsername, $dbPassword, $dbName);

?>