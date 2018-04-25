<?php

// connect to mysql server
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
  die("DIED: Failed to connect to MySQL: ($mysqli->connect_errno) $mysqli->connect_error");
}

?>
