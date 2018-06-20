<?php
// includes
include "config.php";
include "_includes/mysqlconnect.php";

// delete all records older than 14 days
$sql = "DELETE FROM coreelec WHERE unixtime < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 14 DAY))";
mysqli_query($mysqli, $sql);

?>
