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
        'virtualsangha/backup',
        'virtualsangha/cache',
        'virtualsangha/groups/gallery',
        'virtualsangha/groups/orca/cachejs',
        'virtualsangha/groups/orca/classes',
        'virtualsangha/groups/orca/js',
        'virtualsangha/groups/orca/layout',
        'virtualsangha/groups/orca/log',
        'virtualsangha/inc',
        'virtualsangha/langs',
        'virtualsangha/media/images',
        'virtualsangha/media/images/banners',
        'virtualsangha/media/images/blog',
        'virtualsangha/media/images/classifieds',
        'virtualsangha/media/images/gallery',
        'virtualsangha/media/images/profile',
        'virtualsangha/media/images/profile_bg',
        'virtualsangha/media/images/promo',
        'virtualsangha/media/images/promo/original',
        'virtualsangha/media/images/sdating',
        'virtualsangha/media/images/sharingImages',
        'virtualsangha/media/sound',
        'virtualsangha/media/video',
        'virtualsangha/orca/cachejs',
        'virtualsangha/orca/classes',
        'virtualsangha/orca/conf',
        'virtualsangha/orca/js',
        'virtualsangha/orca/layout',
        'virtualsangha/orca/log',
        'virtualsangha/periodic',
        'virtualsangha/tmp');

    private $dolphinWritableFiles = array(
        'virtualsangha/inc/db_cached/MenuContent.inc',
        'virtualsangha/inc/db_cached/PageView.inc',
        'virtualsangha/inc/db_cached/ProfileFields.inc',
        'virtualsangha/inc/db_cached/SiteStat.inc',
        'virtualsangha/inc/params.inc.php',
        'virtualsangha/inc/prof.inc.php',
        'virtualsangha/periodic/cmd.php',
        'virtualsangha/periodic/cupid.php',
        'virtualsangha/periodic/notifies.php'
    );

    private $rayWritableDirectories = array(
        'virtualsangha/ray/modules/board/files',
        'virtualsangha/ray/modules/chat/files',
        'virtualsangha/ray/modules/im/files',
        'virtualsangha/ray/modules/movie/files',
        'virtualsangha/ray/modules/mp3/files',
        'virtualsangha/ray/modules/music/files'
    );

    private $rayWritableFiles = array(
        'virtualsangha/ray/modules/global/data/integration.dat',
        'virtualsangha/ray/modules/board/xml/config.xml',
        'virtualsangha/ray/modules/board/xml/langs.xml',
        'virtualsangha/ray/modules/board/xml/main.xml',
        'virtualsangha/ray/modules/board/xml/skins.xml',
        'virtualsangha/ray/modules/chat/xml/config.xml',
        'virtualsangha/ray/modules/chat/xml/langs.xml',
        'virtualsangha/ray/modules/chat/xml/main.xml',
        'virtualsangha/ray/modules/chat/xml/skins.xml',
        'virtualsangha/ray/modules/desktop/xml/config.xml',
        'virtualsangha/ray/modules/desktop/xml/langs.xml',
        'virtualsangha/ray/modules/desktop/xml/main.xml',
        'virtualsangha/ray/modules/desktop/xml/skins.xml',
        'virtualsangha/ray/modules/global/inc/cron.inc.php',
        'virtualsangha/ray/modules/global/inc/header.inc.php',
        'virtualsangha/ray/modules/global/xml/config.xml',
        'virtualsangha/ray/modules/global/xml/main.xml',
        'virtualsangha/ray/modules/im/xml/config.xml',
        'virtualsangha/ray/modules/im/xml/langs.xml',
        'virtualsangha/ray/modules/im/xml/main.xml',
        'virtualsangha/ray/modules/im/xml/skins.xml',
        'virtualsangha/ray/modules/movie/xml/config.xml',
        'virtualsangha/ray/modules/movie/xml/langs.xml',
        'virtualsangha/ray/modules/movie/xml/main.xml',
        'virtualsangha/ray/modules/movie/xml/skins.xml',
        'virtualsangha/ray/modules/mp3/xml/config.xml',
        'virtualsangha/ray/modules/mp3/xml/langs.xml',
        'virtualsangha/ray/modules/mp3/xml/main.xml',
        'virtualsangha/ray/modules/mp3/xml/skins.xml',
        'virtualsangha/ray/modules/music/xml/config.xml',
        'virtualsangha/ray/modules/music/xml/langs.xml',
        'virtualsangha/ray/modules/music/xml/main.xml',
        'virtualsangha/ray/modules/music/xml/skins.xml',
        'virtualsangha/ray/modules/presence/xml/config.xml',
        'virtualsangha/ray/modules/presence/xml/langs.xml',
        'virtualsangha/ray/modules/presence/xml/main.xml',
        'virtualsangha/ray/modules/presence/xml/skins.xml',
        'virtualsangha/ray/modules/shoutbox/xml/config.xml',
        'virtualsangha/ray/modules/shoutbox/xml/langs.xml',
        'virtualsangha/ray/modules/shoutbox/xml/main.xml',
        'virtualsangha/ray/modules/shoutbox/xml/skins.xml',
        'virtualsangha/ray/modules/video/xml/config.xml',
        'virtualsangha/ray/modules/video/xml/langs.xml',
        'virtualsangha/ray/modules/video/xml/main.xml',
        'virtualsangha/ray/modules/video/xml/skins.xml'
    );

    private $rayExecutableFiles = array(
        'virtualsangha/ray/modules/global/app/ffmpeg.exe'
    );
}