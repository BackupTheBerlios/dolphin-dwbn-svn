<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

        //--- User statuses ---//
        if(!defined("USER_STATUS_NEW")) define("USER_STATUS_NEW", "new");
        if(!defined("USER_STATUS_OLD")) define("USER_STATUS_OLD", "old");
        if(!defined("USER_STATUS_ROOM")) define("USER_STATUS_ROOM", "room");
        if(!defined("USER_STATUS_KICK")) define("USER_STATUS_KICK", "kick");
        if(!defined("USER_STATUS_IDLE")) define("USER_STATUS_IDLE", "idle");
        if(!defined("USER_STATUS_TYPE")) define("USER_STATUS_TYPE", "type");

        //--- Room statuses ---//
        if(!defined("ROOM_STATUS_NORMAL")) define("ROOM_STATUS_NORMAL", "normal");
        if(!defined("ROOM_STATUS_DELETE")) define("ROOM_STATUS_DELETE", "delete");

        //--- Chat user types ---//
        if(!defined("CHAT_TYPE_MODER")) define("CHAT_TYPE_MODER", "moder");
        if(!defined("CHAT_TYPE_FULL")) define("CHAT_TYPE_FULL", "full");
        if(!defined("CHAT_TYPE_TEXT")) define("CHAT_TYPE_TEXT", "text");
        if(!defined("CHAT_TYPE_VIEW")) define("CHAT_TYPE_VIEW", "view");

        $aInfo = array(
				'mode' => "free",
                'title' => "A/V Chat",
                'version' => "3.1.0000",
                'code' => "chat_3.1.0000_free",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'user' => array(
                        'caption' => 'Ray Chat',
                        'parameters' => array('id', 'password'),
                        'js' => array(),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 755, 'height' => 650),
                                                'minSize' => array('width' => 755, 'height' => 650),
                        'div' => array()
                )
        );
?>