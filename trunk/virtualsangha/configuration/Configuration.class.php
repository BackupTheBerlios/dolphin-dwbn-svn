<?php

interface Configuration
{
    function dbName();
    function dbUsername();
    function dbPassword();
    function dbPort();
    function dbSocket();
    function dbHostname();

    function siteUrl();
    function siteRoot();

    function pathToPhp();
    function pathToComposite();
    function pathToConvert();
    function pathToMogrify();

    function siteEmail();
    function notifyEmail();
    function bugReportEmail();
}

?>