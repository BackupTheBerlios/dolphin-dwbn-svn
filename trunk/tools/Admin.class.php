<?php

class Admin
{
    private $currentDirectory;
    private $baseDirectory;
    private $permissionsDAO;
    private $permissionGranter;
    private $mysqlAdmin;

    public function __construct(
        PermissionsDAO    $permissionsDAO,
        PermissionGranter $permissionGranter,
        MysqlAdmin        $mysqlAdmin)
    {
        $this->permissionsDAO    = $permissionsDAO;
        $this->permissionGranter = $permissionGranter;
        $this->mysqlAdmin        = $mysqlAdmin;
        $this->currentDirectory  = getcwd();
        $this->assertScriptIsNotRunFromUpperDirectory();
        $this->baseDirectory     = preg_replace('/tools/', '', $this->currentDirectory).'virtualsangha';
    }

    public function main()
    {
        $opts = getopt("i");

        if ($opts['i'] === false)
        {
            $this->install();
        } else {
            $this->usage();
        }
    }

    private function usage()
    {
        echo "To install Virtual Sangha: php admin.php -i\n";
    }

    private function install()
    {
        echo 'Setting permissions...';
        $this->permissionGranter->grant($this->permissionsDAO->findAllAsMap());
        echo " done\n";

        echo 'Dropping database...';
        $this->mysqlAdmin->drop('test2');
        echo " done\n";

        echo 'Recreating database...';
        $this->mysqlAdmin->create('test2');
        echo " done\n";
    }

    private function assertScriptIsNotRunFromUpperDirectory()
    {
        // this is a really silly check to make sure relative paths will be correct
        // any ideas how to do it in a better way? --Irek

        if (!preg_match('/^.*virtualsangha\/tools$/', $this->currentDirectory)) {
            throw new Exception("You must run this script from the directory it is in.");
        }
    }
}