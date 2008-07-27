<?php

class Admin
{
    private $currentDirectory;
    private $baseDirectory;

    public function __construct()
    {
        $this->currentDirectory = getcwd();
        $this->assertScriptIsNotRunFromUpperDirectory();
        $this->baseDirectory = preg_replace('/tools/', '', $this->currentDirectory).'virtualsangha';
    }

    public function main()
    {
        $opts = getopt("i");

        if ($opts['i'] === false)
        {
            $this->install();
        } else {
            $this->usage();
        }
    }

    private function install()
    {
        $this->grantPermissions();
    }

    private function assertScriptIsNotRunFromUpperDirectory()
    {
        // this is really silly check to make sure the relative paths will be correct
        // any ideas to do it in a better way? --Irek

        if (!preg_match('/^.*virtualsangha\/tools$/', $this->currentDirectory)) {
            throw new Exception("You must run this script from the directory it is in.");
        }
    }

    private function grantPermissions()
    {
        $this->grant('755', $this->dolphinWritableDirectories);
    }

    private function grant() {
    }

    private function usage()
    {
        echo "To install Virtual Sangha: php admin.php -i\n";
    }

    private $dolphinWritableDirectories = array(
        'backup',
        'cache',
        'groups/gallery',
        'groups/orca/cachejs',
        'groups/orca/classes',
        'groups/orca/js',
        'groups/orca/layout',
        'groups/orca/log',
        'inc',
        'langs',
        'media/images',
        'media/images/banners',
        'media/images/blog',
        'media/images/classifieds',
        'media/images/gallery',
        'media/images/profile',
        'media/images/profile_bg',
        'media/images/promo',
        'media/images/promo/original',
        'media/images/sdating',
        'media/images/sharingImages',
        'media/sound',
        'media/video',
        'orca/cachejs',
        'orca/classes',
        'orca/conf',
        'orca/js',
        'orca/layout',
        'orca/log',
        'periodic',
        'tmp');
}

$admin = new Admin();
$admin->main();

?>