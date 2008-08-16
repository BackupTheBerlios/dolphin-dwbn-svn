<?php

class VsConfiguration implements Configuration
{
    public function dbName()
    {
        return 'test2';
    }

    public function dbUsername()
    {
        return 'irek';
    }

    public function dbPassword()
    {
        return 'macbook4ir';
    }

    public function dbPort()
    {
        return '';
    }

    public function dbSocket()
    {
        return '/tmp/mysql.sock';
    }

    public function dbHostname()
    {
        return 'localhost';
    }

    public function siteUrl()
    {
        return 'http://localhost/';
    }

    public function siteRoot()
    {
        return '/Library/WebServer/Documents/';
    }

    public function pathToPhp()
    {
        return '/usr/local/php5/bin/php';
    }

    public function pathToComposite()
    {
        return '/sw/bin/composite';
    }

    public function pathToConvert()
    {
        return '/sw/bin/convert';
    }

    public function pathToMogrify()
    {
        return '/sw/bin/mogrify';
    }

    public function siteEmail()
    {
        return 'emaho@irekjozwiak.com';
    }

    public function notifyEmail()
    {
        return 'emaho@irekjozwiak.com';
    }

    public function bugReportEmail()
    {
        return 'emaho@irekjozwiak.com';
    }
}

?>