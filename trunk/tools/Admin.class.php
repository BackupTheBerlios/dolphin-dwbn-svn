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
        $this->baseDirectory     = preg_replace('/tools/', '', $this->currentDirectory).'virtualsangha';
    }

    public function main()
    {
        $this->assertScriptIsNotRunFromUpperDirectory();
        $opts = getopt("iu");

        if ($opts['i'] === false)
        {
            $this->install();
        }
        elseif ($opts['u'] === false)
        {
            $this->update();
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
        echo "=> Setting permissions\n";
        $this->permissionGranter->grant($this->permissionsDAO->findAllAsMap());
        echo "\n";

        echo "=> Dropping database\n";
        $this->mysqlAdmin->drop();
        echo "\n";

        echo "=> Recreating database\n";
        $this->mysqlAdmin->create();
        echo "\n";

        echo "=> Importing baseline\n";
        $this->mysqlAdmin->importBaseline();
        echo "\n";

		$this->update();
    }
    
    private function update() {
    	echo "=> Applying database deltas\n";
        $this->mysqlAdmin->applyDeltas();
        echo "\n";
    }

    private function assertScriptIsNotRunFromUpperDirectory()
    {
        // this is a really silly check to make sure relative paths will be correct
        // any ideas how to do it in a better way? --Irek

        if (!preg_match('/^.*tools$/', $this->currentDirectory))
        {
            throw new Exception("You must run this script from the directory it is in.");
        }
    }
}

?>