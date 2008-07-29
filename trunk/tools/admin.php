<?php

require_once('../virtualsangha/IocContainer.class.php');

$ioc = new IocContainer();
$admin = $ioc->getInstanceOf('Admin');
$admin->main();

?>