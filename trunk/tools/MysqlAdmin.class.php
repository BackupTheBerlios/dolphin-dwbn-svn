<?php

require_once('Delta.class.php');

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
        system( 'mysqladmin -u'.$this->dbUsername.' -p'.$this->dbPassword.' --force drop '.$this->dbName );
    }

    public function create()
    {
        system( 'mysqladmin -u'.$this->dbUsername.' -p'.$this->dbPassword.' --force create '.$this->dbName );
    }

    public function importBaseline()
    {
        $this->runSqlFile( 'baseline/dolphin-dump.sql' );
        $this->runSqlFile( 'baseline/virtualsangha-specific.sql' );
    }

    public function applyDeltas()
    {
        while ( $this->dbVersion->isLessThan( $this->deltaDirectory->latestVersion() ) ) 
        {
            $delta = new Delta( $this->dbVersion->currentVersion() + 1 );
            $delta->apply( $this );
        }
    }

    public function runSqlFile( $filename )
    {
        system('mysql -u'.$this->dbUsername.' -p'.$this->dbPassword.' '.$this->dbName.' < ../virtualsangha/database/'.$filename, &$returnValue);
    	if ((int)$returnValue != 0) 
    	{
    		throw new Exception("Could not run SQL file: " . $filename);
    	}
    }
}

?>