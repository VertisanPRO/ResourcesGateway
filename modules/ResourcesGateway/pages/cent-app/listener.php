<?php

require_once(ROOT_PATH . '/modules/ResourcesGateway/pages/cent-app/CentApp.php');
require_once(ROOT_PATH . '/modules/Resources/classes/Resources.php');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$logs_path = ROOT_PATH . '/modules/ResourcesGateway/pages/cent-app/logs/' . $_POST['TrsId'] . '.json';

if (!isset($_POST['Status']) or $_POST['Status'] != 'SUCCESS') {
  $_POST['error_log'] = 'status failed';
  file_put_contents($logs_path, json_encode($_POST));
  exit;
}

$data = json_decode($_POST['custom'], true);
$resource = end($queries->getWhere('resources', array('id', '=', $_POST['InvId'])));


if (empty($resource)) {
  $_POST['error_log'] = 'resourse not exist';
  file_put_contents($logs_path, json_encode($_POST));
  exit;
}

$cache->setCache('centapp_user_data');
if($cache->isCached('centapp_key_' . $resource->creator_id) and !empty($cache->retrieve('centapp_key_' . $resource->creator_id))){
  $centapp_key = $cache->retrieve('centapp_key_' . $resource->creator_id);
  $centapp_shop = $cache->retrieve('centapp_shop_' . $resource->creator_id);
} else {
  $_POST['error_log'] = 'resourse creator API no exist';
  file_put_contents($logs_path, json_encode($_POST));
  exit;
}

$cent_api = new CentApp($centapp_key, $centapp_shop);
$signature = $cent_api->getSignature($_POST['InvId'], $_POST['OutSum']);

if ($signature == $_POST['SignatureValue']) {

  $user = new User($data['user_id']);

  $existing_license = DB::getInstance()->query('SELECT id FROM nl2_resources_payments WHERE resource_id = ? AND user_id = ?', array($resource->id, $user->data()->id));
  if (!$existing_license->count()) {

    // Add license
    $queries->create('resources_payments', array(
        'user_id' => (int) $user->data()->id,
        'resource_id' => (int) $resource->id,
        'transaction_id' => $_POST['TrsId'],
        'created' => date('U'),
        'status' => 1
    ));
    
    // Alert
    Alert::create($user->data()->id, 'resource_purchased', array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchased'), array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchased_full', 'replace' => '{x}', 'replace_with' => $resource->name), Resources::buildURL($resource->id, $resource->name));

    Alert::create($resource->creator_id, 'resource_purchase', array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchase'), array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchase_full', 'replace' => array('{x}', '{y}'), 'replace_with' => array($user->getDisplayName(), $resource->name)), $user->getProfileURL());


    $_POST['error_log'] = 'no errors';
    file_put_contents($logs_path, json_encode($_POST));
    exit;

  } else {
    $_POST['error_log'] = 'buyer license exist';
    file_put_contents($logs_path, json_encode($_POST));
    exit;
  }

} else {
  $_POST['error_log'] = 'signature error';
  file_put_contents($logs_path, json_encode($_POST));
  exit;
}

$_POST['error_log'] = 'unknown error';
file_put_contents($logs_path, json_encode($_POST));