<?php

require_once('PermissionGranter.class.php');
require_once('PermissionsDAO.class.php');

class Admin
{
    private $currentDirectory;
    private $baseDirectory;
    private $permissionsDAO;
    private $permissionGranter;

    public function __construct()
    {
        $this->permissionsDAO = new PermissionsDAO();
        $this->permissionGranter = new PermissionGranter();
        $this->currentDirectory = getcwd();
        $this->assertScriptIsNotRunFromUpperDirectory();
        $this->baseDirectory = preg_replace('/tools/', '', $this->currentDirectory).'virtualsangha';
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
        echo "Setting permissions...\n";
        $this->permissionGranter->grant($this->permissionsDAO->findAllAsMap());
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