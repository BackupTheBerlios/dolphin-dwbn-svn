<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

		//--- Board statuses ---//
		if(!defined("BOARD_STATUS_NEW")) define("BOARD_STATUS_NEW", "new");
        if(!defined("BOARD_STATUS_CLOSED")) define("BOARD_STATUS_CLOSED", "closed");
        if(!defined("BOARD_STATUS_UPDATED")) define("BOARD_STATUS_UPDATED", "updated");

        $aInfo = array(
				'mode' => "free",
                'title' => "Whiteboard",
                'version' => "1.1.0000",
                'code' => "board_1.1.0000_free",
                'author' => "Boonex",
                'authorUrl' => "http://www.boonex.com"
        );
        $aModules = array(
                'user' => array(
                        'caption' => 'Whiteboard',
                        'parameters' => array('id', 'password'),
                        'js' => array(),
                        'inline' => true,
                        'vResizable' => false,
                        'hResizable' => false,
                        'reloadable' => true,
                        'layout' => array('top' => 0, 'left' => 0, 'width' => 750, 'height' => 760),
                                                'minSize' => array('width' => 750, 'height' => 760),
                        'div' => array()
                )
        );
?>