<?php
function calcSignature($authSecret, $params) {
  // json_encode escapes slashes by default, which would result in a wrong signature
  // If you run PHP 5.4, you could also use the JSON_UNESCAPED_SLASHES bitmask in
  // the second argument to json_encode
  $encodedParams = str_replace('\/','/', json_encode($params));
  $signature     = hash_hmac('sha1', $encodedParams, $authSecret);

  return $signature;
}

function prepareTransloaditResults($transloaditData) {
  $results = array();

  if (ini_get('magic_quotes_gpc') === '1') {
    $transloaditData = stripslashes($transloaditData);
  }
  $transloaditData = json_decode($transloaditData, true);

  foreach ($transloaditData['results'] as $step => $stepResults) {
    $results[$step] = array();
    foreach ($stepResults as $result) {
      $results[$step][] = $result['url'];
    }
  }
  return $results;
}

function displayThumbnails($thumbs) {
  $out = '<div class="row">';
  foreach ($thumbs as $url) {
    $out .= <<<HTML
      <div class="col-sm-6 col-md-2">
        <a href="https://transloadit.com" class="thumbnail">
          <img src="{$url}">
        </a>
      </div>
HTML;
  }
  $out .= '</div>';
  return $out;
}

function pr($a) {
  echo '<pre>';
  print_r($a);
  echo '</pre>';
}

function prd($a) {
  pr($a);
  die();
}
