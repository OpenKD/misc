<?php
include "config.php";

$p = $_GET['p'];
if (empty($p)) die("DIED: No webhook specified!");

include "_includes/mysqlconnect.php";
$time = strtotime("-1 day");

if ($p == "checkin") {
  $res = $mysqli->query("SELECT system FROM coreelec WHERE unixtime > '$time'");
  $cnt = $res->num_rows;
  $json = array(
    'text' => "$cnt devices have checked in within the last 24hrs."
  );
  echo json_encode($json, JSON_PRETTY_PRINT);
}

?>
