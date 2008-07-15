<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$aXmlTemplates = array (
        "result" => array (
              1 => "<result value=\"#1#\" />",
              2 => "<result value=\"#1#\" status=\"#2#\" />",
        ),
        "user" => array (
              2 => "<user nick=\"#1#\" profile=\"#2#\" />"
        ),
		"board" => array (
			2 => "<board id=\"#1#\" status=\"#2#\" />",
			6 => "<board id=\"#1#\" status=\"#2#\" nick=\"#4#\" profile=\"#5#\" image=\"#6#\"><![CDATA[#3#]]></board>"
		),
)
?>