<?php

if (!$user->isLoggedIn()) {
  Redirect::to(URL::build('/login'));
  die();
}

if (!isset($_GET['res_id'])) {
  Redirect::to(URL::build('/resources'));
  die();
}

define('PAGE', 'resources');
define('RESOURCE_PAGE', 'gateway_resource');
$page_title = $res_gateway_language->get('general', 'title');

require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$resource = DB::getInstance()->get('resources', ['id', '=', $_GET['res_id']])->first();

if (empty($resource)) {
  Redirect::to(URL::build('/resources'));
  die();
}

$cache->setCache('stripe_user_data');
if ($cache->isCached('stripe_pub_' . $resource->creator_id) and !empty($cache->retrieve('stripe_pub_' . $resource->creator_id))) {
  $smarty->assign(array(
    'STRIPE' => true,
    'STRIPE_FORM_URL' => URL::build('/resource/stripe', 'res_id=' . $_GET['res_id']),
    'STRIPE_LABEL' => $res_gateway_language->get('general', 'stripe')
  ));
} else {
  $smarty->assign(array(
    'STRIPE' => false
  ));
}

$cache->setCache('centapp_user_data');
if ($cache->isCached('centapp_key_' . $resource->creator_id) and !empty($cache->retrieve('centapp_key_' . $resource->creator_id))) {
  $smarty->assign(array(
    'CENT_APP' => true,
    'CENT_APP_PROCESS_URL' => URL::build('/cent-app/process', 'res_id=' . $_GET['res_id']),
    'CENT_APP_LABEL' => $res_gateway_language->get('general', 'cent_app')
  ));
} else {
  $smarty->assign(array(
    'CENT_APP' => false
  ));
}


$smarty->assign(array(
  'TOKEN' => Token::get(),
  'RESOURCE' => $resource
));


$template_file = 'resources-gateway/gateways.tpl';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

$smarty->assign('WIDGETS', $widgets->getWidgets());

if (isset($success))
  $smarty->assign(array(
    'SUCCESS' => $success,
  ));

if (isset($errors) && count($errors))
  $smarty->assign(array(
    'ERROR' => $errors,
  ));

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$template->displayTemplate($template_file, $smarty);
