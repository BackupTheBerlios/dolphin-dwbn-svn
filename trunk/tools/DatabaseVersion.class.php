<?php

class DatabaseVersion
{
    
    public function isLessThan($givenVersion)
    {
        return $this->currentVersion() < $givenVersion;
    }

    public function currentVersion()
    {
        db_res('select max(number) from versions');
        return 0;
    }
}

?>