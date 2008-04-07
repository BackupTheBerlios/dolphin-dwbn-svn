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
				'mode' => "paid",
                'title' => "Music Player",
                'version' => "3.1.0001",
                'code' => "mp3_3.1.0000",
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
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 350, 'height' => 310),
                                                'minSize' => array('width' => 350, 'height' => 310),
                        'div' => array()
                ),
                'editor' => array(
                        'caption' => 'Music Uploader',
                        'parameters' => array('id', 'password'),
                        'js' => array( $sModulesUrl . 'mp3/js/inline_editor.js'),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 440, 'height' => 570),
                                                'minSize' => array('width' => 440, 'height' => 570),
                        'div' => array()
                )
        );
?>