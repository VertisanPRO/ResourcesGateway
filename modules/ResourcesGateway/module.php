<?php

class ResourcesGateway extends Module
{

    public function __construct($language, $pages, $INFO_MODULE, $res_gateway_language)
    {
        $this->_language = $language;
        $this->res_gateway_language = $res_gateway_language;
        $this->module_name = $INFO_MODULE['name'];
        $author = $INFO_MODULE['author'];
        $module_version = $INFO_MODULE['module_ver'];
        $nameless_version = $INFO_MODULE['nml_ver'];
        parent::__construct($this, $this->module_name, $author, $module_version, $nameless_version);

        $pages->add($this->module_name, '/user/resources/gateway', 'pages/user/gateway.php');
        $pages->add($this->module_name, '/resource/gateway', 'pages/gateway.php');

        // CENT-APP
        $pages->add($this->module_name, '/cent-app/success', 'pages/cent-app/success.php');
        $pages->add($this->module_name, '/cent-app/fail', 'pages/cent-app/fail.php');
        $pages->add($this->module_name, '/cent-app/listener', 'pages/cent-app/listener.php');
        $pages->add($this->module_name, '/cent-app/process', 'pages/cent-app/process.php');

        // Stripe
        $pages->add($this->module_name, '/resource/stripe', 'pages/stripe/form.php');
        $pages->add($this->module_name, '/resource/stripe/process', 'pages/stripe/process.php');
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


        $navs[1]->add('resources_user_gateway', 'Resources Gateway', URL::build('/user/resources/gateway'), 'top', null, 11);

        if (defined('FRONT_END')) {
            if (defined('RESOURCE_PAGE') and RESOURCE_PAGE == 'view_resource') {
                if ($smarty->getTemplateVars('AUTHOR_NAME')) {
                    $res_id = $smarty->getTemplateVars('RESOURCE_ID');

                    $payments = DB::getInstance()->query('SELECT * FROM `nl2_resources_payments` WHERE `resource_id` = ? AND `status` = 1', array($res_id))->results();
                    $payments_count = count($payments);
                    $template->addJSScript('
                        var payments_count = \'' . $payments_count . '\';
                        ');

                    $resource = DB::getInstance()->get('resources', ['id', '=', $res_id])->first();
                    $creator_id = $resource->creator_id;

                    $cache->setCache('setting_gateway');
                    if ($cache->isCached('user_gateway_status_' . $creator_id) and !empty($cache->retrieve('user_gateway_status_' . $creator_id))) {

                        if (!empty($smarty->getTemplateVars('PURCHASE_FOR_PRICE'))) {

                            if ($cache->retrieve('user_gateway_status_' . $creator_id) == 'enable') {
                                $res_gateway_button = $this->res_gateway_language->get('general', 'more_ways');
                                $gateway_url = URL::build('/resource/gateway', 'res_id=' . $res_id);

                                $template->addJSScript('
                                        var res_id = \'' . $res_id . '\';
                                        var res_gateway_button = \'' . $res_gateway_button . '\';
                                        var gateway_url = \'' . $gateway_url . '\';
                                    ');
                            } else {
                                $template->addJSScript('
                                        var res_id = 0;
                                    ');
                            }
                        }
                    } else {
                        $template->addJSScript('
                                var res_id = 0;
                            ');
                    }
                    $template->addJSFiles(
                        array(
                            (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/modules/' . $this->module_name . '/js/' . $template->getName() . '.js' => array()
                        )
                    );
                }
            }
        }
    }

    public function getDebugInfo(): array
    {
        return [];
    }
}
