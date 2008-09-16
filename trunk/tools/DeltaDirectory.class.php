<?php

class DeltaDirectory
{
    public function latestVersion()
    {
        return max($this->deltas());
    }

    private function deltas()
    {
        $deltas = array();

        $handle=opendir("../virtualsangha/database/deltas");
        while (($entry = readdir($handle))!==false) {
            if (is_numeric($entry))
            {
                $deltas[] = (int)$entry;
            }

        }
        closedir($handle);

        return $deltas;
    }
}