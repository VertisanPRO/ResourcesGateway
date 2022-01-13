<?php


if (!$user->isLoggedIn()) {
  Redirect::to(URL::build('/login'));
  die();
}

define('PAGE', 'resources');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
require(ROOT_PATH . '/modules/Resources/classes/Resources.php');

require(ROOT_PATH . '/modules/ResourcesGateway/pages/stripe/stripe-php/init.php');
use Stripe\Stripe;
use Stripe\Charge;

// Get resource
$resource = end($queries->getWhere('resources', array('id', '=', $_POST['res_id'])));

$cache->setCache('stripe_user_data');
if(!$cache->isCached('stripe_pub_' . $resource->creator_id)){
    Redirect::to(URL::build('/resources/resource/' . $resource->id));
    die();
} else {
    $publishable_key = $cache->retrieve('stripe_pub_' . $resource->creator_id);
    $secret_key = $cache->retrieve('stripe_pri_' . $resource->creator_id);
}

// Get currency
$currency = end($queries->getWhere('settings', array('name', '=', 'resources_currency')));
$currency = $currency->value;

$back_url = URL::build('/resource/stripe', 'res_id='.$resource->id);


if (Token::check(Input::get('token'))) {

  $existing_license = DB::getInstance()->query('SELECT id FROM nl2_resources_payments WHERE resource_id = ? AND user_id = ?', array($resource->id, $user->data()->id));
  if (!$existing_license->count()) {

    if(isset($_POST['stripeToken'])){
        Stripe::setApiKey($secret_key);
        $description     = "card #". $user->data()->id . rand(99999,999999999);
        $amount_cents     = $resource->price * 100;
        $tokenid        = $_POST['stripeToken'];
      try {
        $charge = Charge::create(array(
          "amount" => $amount_cents,
          "currency" => $currency,
          "source" => $tokenid,
          "description" => $description)              
        );
              
        $id            = $charge['id'];
        $amount     = $charge['amount'];
        $balance_transaction = $charge['balance_transaction'];
        $status     = $charge['status'];
        $date     = date("Y-m-d H:i:s");
              
        // Add license
        $queries->create('resources_payments', array(
            'user_id' => $user->data()->id,
            'resource_id' => $resource->id,
            'transaction_id' => $description,
            'created' => date('U'),
            'status' => 1
        ));
        
        // Alert
        Alert::create($user->data()->id, 'resource_purchased', array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchased'), array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchased_full', 'replace' => '{x}', 'replace_with' => $resource->name), Resources::buildURL($resource->id, $resource->name));

        Alert::create($resource->creator_id, 'resource_purchase', array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchase'), array('path' => ROOT_PATH . '/modules/Resources/language', 'file' => 'resources', 'term' => 'resource_purchase_full', 'replace' => array('{x}', '{y}'), 'replace_with' => array($user->getDisplayName(), $resource->name)), $user->getProfileURL());

        Session::flash('stripe_success', 'Status url: ' . $charge['receipt_url']);
        Redirect::to($back_url );
        die();

      } catch(\Stripe\Exception\CardException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (\Stripe\Exception\RateLimitException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (\Stripe\Exception\InvalidRequestException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (\Stripe\Exception\AuthenticationException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (\Stripe\Exception\ApiConnectionException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      } catch (Exception $e) {
        $error = $e->getMessage();
        Session::flash('stripe_error', $error);
        Redirect::to($back_url);
        die();
      }
    }

  } else {
    $error = $resource_language->get('resources', 'user_already_has_license');
    Session::flash('stripe_error', $error);
    Redirect::to($back_url);
    die();
  }

} else {
    $error = $language->get('general', 'invalid_token');
    Session::flash('stripe_error', $error);
    Redirect::to($back_url);
    die();
}
Redirect::to($back_url);
die();