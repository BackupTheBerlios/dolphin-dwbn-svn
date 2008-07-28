<?php
class PermissionsDAO
{
    public function findAllAsMap()
    {
        return array(
            777 => array_merge(
                $this->dolphinWritableDirectories,
                $this->rayWritableDirectories,
                $this->rayExecutableFiles),
            666 => array_merge(
                $this->dolphinWritableFiles,
                $this->rayWritableFiles)
            );
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

    private $dolphinWritableFiles = array(
        'inc/db_cached/MenuContent.inc',
        'inc/db_cached/PageView.inc',
        'inc/db_cached/ProfileFields.inc',
        'inc/db_cached/SiteStat.inc',
        'inc/params.inc.php',
        'inc/prof.inc.php',
        'periodic/cmd.php',
        'periodic/cupid.php',
        'periodic/notifies.php'
    );

    private $rayWritableDirectories = array(
        'ray/modules/board/files',
        'ray/modules/chat/files',
        'ray/modules/im/files',
        'ray/modules/movie/files',
        'ray/modules/mp3/files',
        'ray/modules/music/files'
    );

    private $rayWritableFiles = array(
        'ray/modules/global/data/integration.dat',
        'ray/modules/board/xml/config.xml',
        'ray/modules/board/xml/langs.xml',
        'ray/modules/board/xml/main.xml',
        'ray/modules/board/xml/skins.xml',
        'ray/modules/chat/xml/config.xml',
        'ray/modules/chat/xml/langs.xml',
        'ray/modules/chat/xml/main.xml',
        'ray/modules/chat/xml/skins.xml',
        'ray/modules/desktop/xml/config.xml',
        'ray/modules/desktop/xml/langs.xml',
        'ray/modules/desktop/xml/main.xml',
        'ray/modules/desktop/xml/skins.xml',
        'ray/modules/global/inc/cron.inc.php',
        'ray/modules/global/inc/header.inc.php',
        'ray/modules/global/xml/config.xml',
        'ray/modules/global/xml/main.xml',
        'ray/modules/im/xml/config.xml',
        'ray/modules/im/xml/langs.xml',
        'ray/modules/im/xml/main.xml',
        'ray/modules/im/xml/skins.xml',
        'ray/modules/movie/xml/config.xml',
        'ray/modules/movie/xml/langs.xml',
        'ray/modules/movie/xml/main.xml',
        'ray/modules/movie/xml/skins.xml',
        'ray/modules/mp3/xml/config.xml',
        'ray/modules/mp3/xml/langs.xml',
        'ray/modules/mp3/xml/main.xml',
        'ray/modules/mp3/xml/skins.xml',
        'ray/modules/music/xml/config.xml',
        'ray/modules/music/xml/langs.xml',
        'ray/modules/music/xml/main.xml',
        'ray/modules/music/xml/skins.xml',
        'ray/modules/presence/xml/config.xml',
        'ray/modules/presence/xml/langs.xml',
        'ray/modules/presence/xml/main.xml',
        'ray/modules/presence/xml/skins.xml',
        'ray/modules/shoutbox/xml/config.xml',
        'ray/modules/shoutbox/xml/langs.xml',
        'ray/modules/shoutbox/xml/main.xml',
        'ray/modules/shoutbox/xml/skins.xml',
        'ray/modules/video/xml/config.xml',
        'ray/modules/video/xml/langs.xml',
        'ray/modules/video/xml/main.xml',
        'ray/modules/video/xml/skins.xml'
    );

    private $rayExecutableFiles = array(
        'ray/modules/global/app/ffmpeg.exe'
    );
}