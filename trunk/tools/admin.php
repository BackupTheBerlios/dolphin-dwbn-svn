<?php

set_include_path( '../virtualsangha' . PATH_SEPARATOR . get_include_path() );


require_once('IocContainer.class.php');
require_once('inc/header.inc.php');

$ioc = new IocContainer();
$admin = $ioc->getInstanceOf('Admin');
$admin->main();

?>