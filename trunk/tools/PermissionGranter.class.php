<?php

class PermissionGranter
{
    public function grant($permissions)
    {
        foreach ($permissions as $level => $paths)
        {
            $this->chmodAll($level, $paths);
        }
    }

    private function chmodAll($permissionLevel, $paths)
    {
        foreach ($paths as $path)
        {
            system('chmod '.$permissionLevel.' '.$this->rootDirectory($path));
        }
    }

    private function rootDirectory($path)
    {
        return '../'.$path;
    }
}