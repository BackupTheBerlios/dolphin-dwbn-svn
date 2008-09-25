<?php


require_once('../tools/Admin.class.php');
require_once('../tools/DatabaseVersion.class.php');
require_once('../tools/DeltaDirectory.class.php');
require_once('../tools/MysqlAdmin.class.php');
require_once('../tools/PermissionGranter.class.php');
require_once('../tools/PermissionsDAO.class.php');
require_once('configuration/Configuration.class.php');
require_once('configuration/VsConfiguration.class.php');

class IocContainer
{
    private $immutableSingletons = array();

    public static function getInstance()
    {
        return new IocContainer();
    }

    public function __construct()
    {
        $this->initialiseImmutableSingletonInstances();
    }

    public function getInstanceOf($className)
    {
        return $this->immutableSingletons[$className];
    }

    private function initialiseImmutableSingletonInstances()
    {
        $this->immutableSingletons['PermissionsDAO'] = new PermissionsDAO();
        $this->immutableSingletons['PermissionGranter'] = new PermissionGranter();
        $this->immutableSingletons['Configuration'] = new VsConfiguration();
        $this->immutableSingletons['DatabaseVersion'] = new DatabaseVersion();
        $this->immutableSingletons['DeltaDirectory'] = new DeltaDirectory();
        $this->immutableSingletons['MysqlAdmin'] = new MysqlAdmin(
            $this->getInstanceOf('Configuration'),
            $this->getInstanceOf('DatabaseVersion'),
            $this->getInstanceOf('DeltaDirectory'));
        $this->immutableSingletons['Admin'] = new Admin(
            $this->getInstanceOf('PermissionsDAO'),
            $this->getInstanceOf('PermissionGranter'),
            $this->getInstanceOf('MysqlAdmin')
        );
    }

}

?>