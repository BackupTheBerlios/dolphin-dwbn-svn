<?php

require_once('inc/db.inc.php');

class DatabaseVersion
{
    
    public function isLessThan($givenVersion)
    {
        return $this->currentVersion() < $givenVersion;
    }

    public function currentVersion()
    {
        $res = db_res('select max(number) as latest from versions');
        $values = mysql_fetch_array($res);
        return (int) $values['latest'];
    }
}

?>