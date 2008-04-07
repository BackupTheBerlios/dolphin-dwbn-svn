<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/
        $aInfo = array(
				'mode' => "paid",
                'title' => "Messenger",
                'version' => "3.1.0000",
                'code' => "im_3.1.0000",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'user' => array(
                        'caption' => 'Messenger',
                        'parameters' => array('sndId', 'password', 'rspId'),
                        'js' => array(),
                        'inline' => false,
                        'vResizable' => true,
                        'hResizable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 350, 'height' => 590),
                                                'minSize' => array('width' => 350, 'height' => 590),
                        'reloadable' => true,
                        'div' => array()
                ),
                'invite' => array(
                        'caption' => 'invite',
                        'parameters' => array('id', 'password'),
                        'js' => array($sModulesUrl . 'im/js/invite.js'),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => false,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 300, 'height' => 200),
                                                'minSize' => array('width' => 300, 'height' => 200),
                        'div' => array(
                                'id' => 'flcontainer',
                                'name' => 'flcontainer',
                                'style' => array(
                                        'position' => 'absolute',
                                        'top' => '-9999px',
                                        'left' => '0px',
                                        'width' => '300px',
                                        'height' => '200px',
                                        'z-index' => 90
                                )
                        )
                )
        );
?>