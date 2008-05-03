<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/
	if(!defined("TEMP_FILE_NAME")) define("TEMP_FILE_NAME", "_temp");
	if(!defined("THUMB_FILE_NAME")) define("THUMB_FILE_NAME", "_small");
	if(!defined("SAVE_EXTENSION")) define("SAVE_EXTENSION", ".mpg");
	if(!defined("PLAY_EXTENSION")) define("PLAY_EXTENSION", ".flv");
	if(!defined("IMAGE_EXTENSION")) define("IMAGE_EXTENSION", ".jpg");
	if(!defined("VIDEO_SIZE")) define("VIDEO_SIZE", "350x250");
	if(!defined("THUMB_SIZE")) define("THUMB_SIZE", "110x80");
	
	$aInfo = array(
		'mode' => "free",
		'title' => "Video Player",
		'version' => "3.5.0000",
		'code' => "movie_3.5.0000_free",
		'author' => "Boonex",
		'authorUrl' => "http://www.boonex.com"
	);
	$aModules = array(
		'player' => array(
			'caption' => 'Video Player',
			'parameters' => array('id', 'file'),
			'js' => array(),
			'inline' => true,
			'vResizable' => false,
			'hResizable' => false,
			'reloadable' => true,
			'layout' => array('top' => 0, 'left' => 0, 'width' => "100%", 'height' => 435),
									'minSize' => array('width' => 350, 'height' => 435),
			'div' => array()
		),
		'editor' => array(
			'caption' => 'Video Uploader',
			'parameters' => array('id', 'password'),
			'js' => array(),
			'inline' => true,
			'vResizable' => false,
			'hResizable' => false,
			'reloadable' => true,
			'layout' => array('top' => 0, 'left' => 0, 'width' => 440, 'height' => 640),
									'minSize' => array('width' => 440, 'height' => 640),
			'div' => array()
		)
	);
?>
