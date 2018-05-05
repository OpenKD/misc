<?php
include "config.php";
include "_includes/funcs.php";

$p = $_GET['p'];
if (empty($p)) die("DIED: No webhook specified!");

include "_includes/mysqlconnect.php";
$time = strtotime("-2 day");

if ($p == "checkin") {
  $res = $mysqli->query("SELECT system FROM coreelec WHERE unixtime > '$time'");
  $cnt = $res->num_rows;
  $json = array(
    'text' => "$cnt devices have checked in within the last 48hrs."
  );
} elseif ($p == "latest") {
  $latestrel = github_request("https://api.github.com/repos/CoreELEC/CoreELEC/releases/latest");
  $latestver = $latestrel['tag_name'];
  $res = $mysqli->query("SELECT system FROM coreelec WHERE unixtime > '$time' AND version = '$latestver'");
  $cnt = $res->num_rows;
  $json = array(
    'text' => "$latestver is the latest version of which $cnt devices have checked in within the last 48hrs with it installed."
  );
}

echo json_encode($json, JSON_PRETTY_PRINT);

?>
