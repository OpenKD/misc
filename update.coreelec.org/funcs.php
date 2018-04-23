<?php

function github_request($url)
{
  $ch = curl_init();
  $access = 'adamg88:50b6723216e7541538832ade4931c0369b12e405';
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_USERPWD, $access);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $output = curl_exec($ch);
  curl_close($ch);
  $result = json_decode(trim($output), true);
  return $result;
}

function filterimg($var) {
  if (strpos($var, "img.gz") === false)
    return $var;
}

function filterarch($var) {
  global $vars;
  if (strpos($var, $vars['arch']) !== false)
    return $var;
}

?>
