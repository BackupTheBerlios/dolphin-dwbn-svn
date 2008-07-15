<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

        if(!defined("APPROVE_VAL")) define("APPROVE_VAL", "approval");
        if(!defined("ACTIVE_VAL")) define("ACTIVE_VAL", "active");

        $aInfo = array(
				'mode' => "free",
                'title' => "Video Recorder",
                'version' => "3.5.0002",
                'code' => "video_3.5.0000_free",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'recorder' => array(
                        'caption' => 'Video Recorder',
                        'parameters' => array('id', 'password'),
                        'js' => array(),
                        'inline' => false,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 350, 'height' => 435),
                                                'minSize' => array('width' => 350, 'height' => 435),
                        'div' => array()
                ),
                'player' => array(
                        'caption' => 'Video Player',
                        'parameters' => array('id'),
                        'js' => array(),
                        'inline' => false,
                        'vResizable' => true,
                        'hResizable' => true,
                        'reloadable' => false,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 350, 'height' => 435),
                                                'minSize' => array('width' => 350, 'height' => 435),
                        'div' => array()
                ),
                'admin' => array(
                        'caption' => 'Video Player Admin',
                        'parameters' => array('nick', 'password'),
                        'js' => array(),
                        'inline' => false,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => false,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 700, 'height' => 330),
                                                'minSize' => array('width' => 700, 'height' => 330),
                        'div' => array()
                ),
				'stat' => array(
                        'caption' => '',
                        'parameters' => array(),
                        'js' => array($sModulesUrl . $sModule . "/js/stat.js"),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => false,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 1, 'height' => 1),
                                                'minSize' => array('width' => 1, 'height' => 1),
                        'div' => array()
                )
        );
?>