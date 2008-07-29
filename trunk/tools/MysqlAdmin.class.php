<?php

class MysqlAdmin
{
    private $dbPassword;
    private $dbName;

    public function __construct(Configuration $configuration)
    {
        $this->dbPassword = $configuration->dbPassword();
        $this->dbName = $configuration->dbName();
    }

    public function drop()
    {
        system('mysqladmin -p'.$this->dbPassword.' --force drop '.$this->dbName);
    }

    public function create()
    {
        system('mysqladmin -p'.$this->dbPassword.' --force create '.$this->dbName);
    }

}

?>