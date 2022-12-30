<?php

if (!$user->isLoggedIn()) {
  Redirect::to(URL::build('/'));
  die();
}

// Always define page name for navbar
define('PAGE', 'resources_user_gateway');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new TimeAgo(TIMEZONE);

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {

    if (isset($_POST['gateway_status'])) {
      $cache->setCache('setting_gateway');
      $cache->store('user_gateway_status_' . $user->data()->id, $_POST['gateway_status']);
    }

    if (isset($_POST['publishable_key']) and isset($_POST['secret_key'])) {
      $cache->setCache('stripe_user_data');

      $cache->store('stripe_pub_' . $user->data()->id, $_POST['publishable_key']);
      $cache->store('stripe_pri_' . $user->data()->id, $_POST['secret_key']);
    }

    if (isset($_POST['centapp_key']) and isset($_POST['centapp_shop'])) {
      $cache->setCache('centapp_user_data');

      $cache->store('centapp_key_' . $user->data()->id, $_POST['centapp_key']);
      $cache->store('centapp_shop_' . $user->data()->id, $_POST['centapp_shop']);
    }
  }
}

$cache->setCache('setting_gateway');
if (!$cache->isCached('user_gateway_status_' . $user->data()->id)) {
  $user_gateway_status = '';
} else {
  $user_gateway_status = $cache->retrieve('user_gateway_status_' . $user->data()->id);
}

$cache->setCache('stripe_user_data');
if (!$cache->isCached('stripe_pub_' . $user->data()->id)) {
  $publishable_key = '';
  $secret_key = '';
} else {
  $publishable_key = $cache->retrieve('stripe_pub_' . $user->data()->id);
  $secret_key = $cache->retrieve('stripe_pri_' . $user->data()->id);
}

$cache->setCache('centapp_user_data');
if (!$cache->isCached('centapp_key_' . $user->data()->id)) {
  $centapp_key = '';
  $centapp_shop = '';
} else {
  $centapp_key = $cache->retrieve('centapp_key_' . $user->data()->id);
  $centapp_shop = $cache->retrieve('centapp_shop_' . $user->data()->id);
}

// Language values
$smarty->assign(array(
  'TOKEN' => Token::get(),
  'SUBMIT' => $language->get('general', 'submit'),
  'PUBLISHABLE_KEY' => $publishable_key,
  'SECRET_KEY' => $secret_key,
  'CENTAPP_KEY' => $centapp_key,
  'CENTAPP_SHOP' => $centapp_shop,
  'GATEWAY_STATUS' => $user_gateway_status
));

// API Links
$smarty->assign(array(
  'CENT_SUCCESS_URL' => Util::getSelfURL() .  URL::build('cent-app/success'),
  'CENT_LISTENER_URL' => Util::getSelfURL() .  URL::build('cent-app/listener'),
  'CENT_FAIL_URL' => Util::getSelfURL() . URL::build('cent-app/fail')
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('resources-gateway/user/gateway.tpl', $smarty);
