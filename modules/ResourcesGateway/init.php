<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/tree/v2/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  ResourcesGateway By xGIGABAITx
 */

$INFO_MODULE = array(
	'name' => 'ResourcesGateway',
	'author' => '<a href="https://tensa.co.ua" target="_blank" rel="nofollow noopener">xGIGABAITx</a>',
	'module_ver' => '1.0.0',
	'nml_ver' => '2.0.0-pr12',
);

$res_gateway_language = new Language(ROOT_PATH . '/modules/'.$INFO_MODULE['name'].'/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new ResourcesGateway($language, $pages, $INFO_MODULE, $res_gateway_language);
