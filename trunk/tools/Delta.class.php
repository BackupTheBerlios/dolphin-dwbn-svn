<?php

class Delta
{
    private $version;

    public function __construct( $version )
    {
        $this->version = $version;
    }

    public function apply( $mysql )
    {
        echo 'Upgrading to version '.$this->version."\n";
        
        $mysql->runSqlFile('deltas/'.$this->format($this->version).'/upgrade.sql');
    }

    private function format($version)
    {
        return str_pad($version, 6, '0', STR_PAD_LEFT);
    }
}

?>