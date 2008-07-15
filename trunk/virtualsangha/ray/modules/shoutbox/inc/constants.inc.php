<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$aInfo = array(
	'mode' => "as3",
	'title' => "Shoutbox",
	'version' => "3.5.0002",
	'code' => "shoutbox_3.5.0000_free",
	'author' => "Boonex",
	'authorUrl' => "http://www.boonex.com"
);
$aModules = array(
	'user' => array(
		'caption' => 'Shoutbox',
		'parameters' => array('id', 'password'),
		'js' => array(),
		'inline' => true,
		'vResizable' => false,
		'hResizable' => false,
		'reloadable' => true,
		'layout' => array('top' => 0, 'left' => 0, 'width' => "100%", 'height' => 400),
								'minSize' => array('width' => 300, 'height' => 400),
		'div' => array(
			'style' => array('width' => '100%', 'height' => '400px', 'margin-left' => 'auto', 'margin-right' => 'auto')
		)
	)
);
?>
