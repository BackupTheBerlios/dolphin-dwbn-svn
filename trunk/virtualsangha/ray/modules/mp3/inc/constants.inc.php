<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

	if(!defined("TEMP_FILE_NAME")) define("TEMP_FILE_NAME", "_temp");
	if(!defined("MP3_EXTENSION")) define("MP3_EXTENSION", ".mp3");
	
        $aInfo = array(
				'mode' => "free",
                'title' => "pMusic Player",
                'version' => "3.5.0002",
                'code' => "mp3_3.5.0000_free",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'player' => array(
                        'caption' => 'Music Player',
                        'parameters' => array('id', 'password', 'vId', 'song'),
                        'js' => array( $sModulesUrl . 'mp3/js/inline_player.js'),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => "100%", 'height' => 380),
                                                'minSize' => array('width' => 350, 'height' => 380),
                        'div' => array()
                ),
                'editor' => array(
                        'caption' => 'Music Uploader',
                        'parameters' => array('id', 'password'),
                        'js' => array( $sModulesUrl . 'mp3/js/inline_editor.js'),
                        'inline' => false,
                        'vResizable' => true,
                        'hResizable' => true,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 480, 'height' => 640),
                                                'minSize' => array('width' => 480, 'height' => 640),
                        'div' => array()
                )
        );
?>