<?php

class MysqlAdmin
{
    private $dbName;
    private $dbUsername;
    private $dbPassword;
    private $dbVersion;
    private $deltaDirectory;

    public function __construct(
        Configuration $configuration,
        DatabaseVersion $dbVersion,
        DeltaDirectory $deltaDirectory)
    {
        $this->dbName = $configuration->dbName();
        $this->dbUsername = $configuration->dbUsername();
        $this->dbPassword = $configuration->dbPassword();
        $this->dbVersion = $dbVersion;
        $this->deltaDirectory = $deltaDirectory;
    }

    public function drop()
    {
        system('mysqladmin -u'.$this->dbUsername.' -p'.$this->dbPassword.' --force drop '.$this->dbName);
    }

    public function create()
    {
        system('mysqladmin -u'.$this->dbUsername.' -p'.$this->dbPassword.' --force create '.$this->dbName);
    }

    public function importBaseline()
    {
        $this->runSqlFile('baseline/dolphin-dump.sql');
        $this->runSqlFile('baseline/virtualsangha-specific.sql');
    }

    public function applyDeltas()
    {
        if ($this->dbVersion->isNotLessThan($this->deltaDirectory->latestVersion())) {
            echo "The database is up to date\n";
            return;
        }        
    }

    private function runSqlFile($filename)
    {
        system('mysql -u'.$this->dbUsername.' -p'.$this->dbPassword.' '.$this->dbName.' < ../virtualsangha/database/'.$filename);
    }
}

?>