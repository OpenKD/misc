<?php

// includes
include "config.php";
include "_includes/funcs.php";
include "_includes/mysqlconnect.php";

// variables
$vars = array('system' => $mysqli->real_escape_string($_GET['i']),
              'dist' => $mysqli->real_escape_string($_GET['d']),
              'arch' => $mysqli->real_escape_string($_GET['pa']),
              'vers' => $mysqli->real_escape_string($_GET['v']));

$unixtime = time();
$country = geoip_country_name_by_name($_SERVER['REMOTE_ADDR']);

// don't continue if any of the variables are empty
foreach ($vars as $key => $value) {
  if (empty($value)) die("DIED: $key is empty!");
}

// don't continue if unsupported device
if (!in_array($vars['arch'], $supported, true)) {
  die("DIED: Unsupported device");
}

// skip database entry if user does not want to be tracked
if ($vars['system'] !== "DONOTTRACK") {
  // look to see if system is in the database first
  $stmt = $mysqli->prepare("SELECT system FROM coreelec WHERE system = ?");
  $stmt->bind_param("s", $vars['system']);
  $stmt->execute();
  $res = $stmt->get_result();

  // if system is in database then update else insert
  if ($res->num_rows > 0) {
    $stmt->close();
    $stmt = $mysqli->prepare("UPDATE coreelec SET arch=?, version=?, unixtime='$unixtime', country='$country' WHERE system=?");
    $stmt->bind_param("sss", $vars['arch'], $vars['vers'], $vars['system']);
    $stmt->execute();
  } else {
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO coreelec (system, arch, version, unixtime, country) VALUES (?, ?, ?, '$unixtime', '$country')");
    $stmt->bind_param("sss", $vars['system'], $vars['arch'], $vars['vers']);
    $stmt->execute();
  }
}

// update check code
$latestrel = github_request("https://api.github.com/repos/CoreELEC/CoreELEC/releases/latest");
$latestver = $latestrel['tag_name'];

if(version_compare($vars['vers'], $latestver, '<')) {
  $array_urls = array_filter(array_column($latestrel['assets'], 'browser_download_url'), "filterimg");
  $array_url = array_filter($array_urls, "filterarch");
  $parse_url = parse_url(current($array_url));

  $json = array(
  'data' =>
    array(
      'update' => basename($parse_url['path']),
      'folder' => substr(dirname($parse_url['path']), 1),
      'host' => $parse_url['host']
    )
  );

  echo json_encode($json, JSON_UNESCAPED_SLASHES);
}

?>
