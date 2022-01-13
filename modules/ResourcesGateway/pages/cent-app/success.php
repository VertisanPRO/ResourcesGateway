<?php
require_once(ROOT_PATH . '/core/templates/frontend_init.php');
Redirect::to(URL::build('/resources/resource/' . $_POST['InvId']));
die();