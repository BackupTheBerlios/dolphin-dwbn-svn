<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/
        $aInfo = array(
				'mode' => "free",
                'title' => "Web Presence",
                'version' => "3.5.0000",
                'code' => "presence_3.5.0000_free",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'user' => array(
                        'caption' => 'Web Presence',
                        'parameters' => array('id', 'password'),
                        'js' => array( $sModulesUrl . 'presence/js/inline_user.js'),
                        'inline' => false,
                        'vResizable' => true,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 224, 'height' => 605),
                                                'minSize' => array('width' => 224, 'height' => 605),
                        'div' => array()
                )
        );
?>
