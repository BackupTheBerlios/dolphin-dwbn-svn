<?php

class DatabaseVersion
{
    public function isNotLessThan($givenVersion)
    {
        return !($this->currentVersion() < $givenVersion);
    }

    private function currentVersion()
    {
        return 0;   
    }
}

?>