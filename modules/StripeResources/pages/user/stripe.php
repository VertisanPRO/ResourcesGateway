<?php

if(!$user->isLoggedIn()){
  Redirect::to(URL::build('/'));
  die();
}

// Always define page name for navbar
define('PAGE', 'resources_settings_stripe');
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$timeago = new Timeago(TIMEZONE);

if(Input::exists()){
  if(Token::check(Input::get('token'))){

    if(isset($_POST['publishable_key']) and isset($_POST['secret_key'])){
      $cache->setCache('stripe_user_data');

      $cache->store('stripe_pub_' . $user->data()->id, $_POST['publishable_key']);
      $cache->store('stripe_pri_' . $user->data()->id, $_POST['secret_key']);
    }

  }

}

$cache->setCache('stripe_user_data');
if(!$cache->isCached('stripe_pub_' . $user->data()->id)){
    $publishable_key = '';
    $secret_key = '';
} else {
    $publishable_key = $cache->retrieve('stripe_pub_' . $user->data()->id);
    $secret_key = $cache->retrieve('stripe_pri_' . $user->data()->id);
}




// Language values
$smarty->assign(array(
  'TOKEN' => Token::get(),
  'SUBMIT' => $language->get('general', 'submit'),
  'PUBLISHABLE_KEY' => $publishable_key,
  'SECRET_KEY' => $secret_key
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('resources/user/stripe.tpl', $smarty);