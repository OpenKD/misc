<?php
// disable error reporting
//error_reporting(0);

// includes
include "funcs.php";

// variables
$vars = array('system' => $_GET['i'],
              'dist' => $_GET['d'],
              'arch' => $_GET['pa'],
              'vers' => $_GET['v']);

$unixtime = time();
$country = geoip_country_name_by_name($_SERVER['REMOTE_ADDR']);

$dbhost = "localhost";
$dbuser = "coreelec";
$dbpass = "23yZx0FW2j6R";
$dbname = "coreelec";

$supported = array('S905.arm', 'S912.arm', 'LePotato.arm', 'Odroid_C2.arm', 'KVIM2.arm');

// don't continue if any of the variables are not empty
foreach ($vars as $key => $value) {
  if (empty($value)) die("DIED: $key is empty!");
}

// don't continue if unsupported device
if (!in_array($vars['arch'], $supported, true)) {
  die("DIED: Unsupported device");
}

// connect to mysql server
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
  die("DIED: Failed to connect to MySQL: ($mysqli->connect_errno) $mysqli->connect_error");
}

// look to see if system is in the database first
$res = $mysqli->query("SELECT system FROM coreelec WHERE system = '" . $vars['system'] . "'");

// if system is in database then update else insert
if ($res->num_rows > 0) {
  $mysqli->query("UPDATE coreelec SET arch='" . $vars['arch'] . "', version='" . $vars['vers'] . "', unixtime='$unixtime', country='$country' WHERE system='" . $vars['system'] . "'");
} else {
  $mysqli->query("INSERT INTO coreelec (system, arch, version, unixtime, country) VALUES ('" . $vars['system'] . "', '" . $vars['arch'] . "', '" . $vars['vers'] . "', '$unixtime', '$country')");
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
      'host' => $parse_url['host'],
      'MD5' => ''
    )
  );

  echo json_encode($json, JSON_UNESCAPED_SLASHES);
}

?>
