<?php

if (!$user->isLoggedIn()) {
  Redirect::to(URL::build('/login'));
  die();
}

if (!isset($_GET['res_id'])) {
  Redirect::to(URL::build('/'));
  die();
}

require(ROOT_PATH . '/modules/ResourcesGateway/pages/cent-app/CentApp.php');
require(ROOT_PATH . '/modules/Resources/classes/Resources.php');
require(ROOT_PATH . '/core/templates/frontend_init.php');

$resource = end($queries->getWhere('resources', array('id', '=', $_GET['res_id'])));

$cache->setCache('centapp_user_data');
if(!$cache->isCached('centapp_key_' . $resource->creator_id)){
    Redirect::to(URL::build('/resources/resource/' . $resource->id));
    die();
} else {
    $centapp_key = $cache->retrieve('centapp_key_' . $resource->creator_id);
    $centapp_shop = $cache->retrieve('centapp_shop_' . $resource->creator_id);
}

$currency = end($queries->getWhere('settings', array('name', '=', 'resources_currency')));
$currency = $currency->value;

$back_url = URL::build('/resource/' . $resource->id);

$custom = array(
  'user_id' => $user->data()->id,
  'res_id' => $resource->id,
  'creator_id' => $resource->creator_id
);

$cent_api = new CentApp($centapp_key, $centapp_shop);
$options = array(
  'order_id' => $resource->id,
  'amount' => $resource->price,
  'currency_in' => $currency,
  'name' => $resource->name,
  'description' => '',
  'custom' => json_encode($custom)
);
$resp = $cent_api->createBill($options);
if ($resp['success']) {
  Redirect::to($resp['link_page_url']);
} else {
  Redirect::to($back_url);
}

