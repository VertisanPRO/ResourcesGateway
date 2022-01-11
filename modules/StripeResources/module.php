<?php

class StripeResources extends Module
{

  public function __construct($language, $pages, $INFO_MODULE, $stripe_language)
  {
    $this->_language = $language;
    $this->module_name = $INFO_MODULE['name'];
    $author = $INFO_MODULE['author'];
    $module_version = $INFO_MODULE['module_ver'];
    $nameless_version = $INFO_MODULE['nml_ver'];
    parent::__construct($this, $this->module_name, $author, $module_version, $nameless_version);

    $pages->add($this->module_name, '/resource/stripe', 'pages/form.php');
    $pages->add($this->module_name, '/resource/stripe/process', 'pages/process.php');
    $pages->add($this->module_name, '/user/resources/stripe', 'pages/user/stripe.php');
  }

  public function onInstall()
  {

  }

  public function onUninstall()
  {
  }

  public function onEnable()
  {

  }

  public function onDisable()
  {
  }

  public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template)
  {

    $navs[1]->add('resources_settings_stripe', 'Stripe', URL::build('/user/resources/stripe'), 'top', null, 11);

    if (defined('FRONT_END')) {
      if (RESOURCE_PAGE == 'view_resource') {
        $payments = DB::getInstance()->query('SELECT * FROM `nl2_resources_payments` WHERE `resource_id` = ? AND `status` = 1', array($smarty->getTemplateVars('RESOURCE_ID')))->results();
        $payments_count = count($payments);
        // if ($payments_count > 0) {
          $template->addJSScript('
          var payments_count = \'' . $payments_count .'\';
          ');
        // }


        $cache->setCache('stripe_user_data');
        $creator_id = $user->nameToId($smarty->getTemplateVars('AUTHOR_NAME'));
        if($cache->isCached('stripe_pub_' . $creator_id) and !empty($cache->retrieve('stripe_pub_' . $creator_id))){

          if(!empty($smarty->getTemplateVars('PURCHASE_FOR_PRICE'))){
            $res_id = $smarty->getTemplateVars('RESOURCE_ID');
            $res_purchase_for_price = $smarty->getTemplateVars('PURCHASE_FOR_PRICE');
            $form_url = URL::build('/resource/stripe', 'res_id='.$res_id);
            
            $template->addJSScript('
            var res_id = \'' . $res_id .'\';
            var res_purchase_for_price = \'' . $res_purchase_for_price .'\';
            var stripe_form_url = \'' . $form_url .'\';
            ');
          }
        } else {
          $template->addJSScript('
          var res_id = 0;
          ');
        }
        $template->addJSFiles(array(
          (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/modules/'. $this->module_name .'/js/'. $template->getName() .'.js' => array()
        ));
      }
    
    }
    
  }
}
