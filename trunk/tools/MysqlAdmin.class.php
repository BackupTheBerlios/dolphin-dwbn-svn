<?php

class MysqlAdmin
{
    private $dbName;
    private $dbUsername;
    private $dbPassword;

    public function __construct(Configuration $configuration)
    {
        $this->dbName = $configuration->dbName();
        $this->dbUsername = $configuration->dbUsername();
        $this->dbPassword = $configuration->dbPassword();
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
        system('mysql -u'.$this->dbUsername.' -p'.$this->dbPassword.' '.$this->dbName.' < ../virtualsangha/database/baseline/dolphin-dump.sql');
    }
}

?>