<?php
require_once('helpers.php');
date_default_timezone_set('UTC');

$authKey    = 'YOUR-TRANSLOADIT-AUTH-KEY';
$authSecret = 'YOUR-TRANSLOADIT-AUTH-SECRET';

$params = array(
  'auth' => array(
    "expires" => gmdate('Y/m/d H:i:s+00:00', strtotime('+1 hour')),
    "key"     => $authKey,
    "referer" => $_SERVER['HTTP_HOST']
  ),
  "steps" => array(
    // We need a fake step here as Transloadit does not allow submitting
    // zero steps. We only need the upload.url, though.
    "filter" => array(
      "robot" => "/file/filter",
      "use" => ":original",
      "accepts" => array(
        array("\${file.mime}", "regex", "image")
      )
    )
  )
);

$cropSelection = isset($_POST['crop']) ? $_POST['crop'] : array();
$importUrl     = isset($_POST['url']) ? $_POST['url'] : array();

if (!empty($cropSelection)) {
  $params['steps'] = array(
    "imported" => array(
      "robot" => "/http/import",
      "url"   => $importUrl
    ),
    "crop" => array(
      "robot" => "/image/resize",
      "use"   => "imported",
      "crop"  => $cropSelection
    )
  );
}

$signature = calcSignature($authSecret, $params);

header('Content-type: application/json');
echo json_encode(compact('signature', 'params'));
?>
