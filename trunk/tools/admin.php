<?php

require_once('../virtualsangha/inc/header.inc.php');

$ioc = new IocContainer();
$admin = $ioc->getInstanceOf('Admin');
$admin->main();

?>