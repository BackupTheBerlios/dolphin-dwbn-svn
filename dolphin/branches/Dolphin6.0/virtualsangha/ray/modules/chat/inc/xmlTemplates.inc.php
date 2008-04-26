<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$aXmlTemplates = array (
        "message" => array (
                8 => "<msg id=\"#1#\" userId=\"#3#\" color=\"#4#\" bold=\"#5#\" underline=\"#6#\" italic=\"#7#\" smileset=\"#8#\"><![CDATA[#2#]]></msg>"
        ),

        "user" => array (
                2 => "<user id=\"#1#\" status=\"#2#\" />",
                3 => "<user id=\"#1#\" status=\"#2#\" roomId=\"#3#\" />",
                4 => "<user id=\"#1#\" status=\"#2#\" roomId=\"#3#\" type=\"#4#\" />",
                10 => "<user id=\"#1#\" status=\"#2#\" nick=\"#3#\" sex=\"#4#\" age=\"#5#\" photo=\"#7#\" profile=\"#8#\" type=\"#9#\" roomId=\"#10#\" ><![CDATA[#6#]]></user>",

                9 => "<user id=\"#1#\" nick=\"#2#\" sex=\"#3#\" age=\"#4#\" photo=\"#6#\" profile=\"#7#\" type=\"#8#\" ban=\"#9#\"><![CDATA[#5#]]></user>" //searchUser
        ),

        "result" => array (
                1 => "<result value=\"#1#\" />",
                2 => "<result value=\"#1#\" status=\"#2#\" />",
                3 => "<result value=\"#1#\" id=\"#2#\" nick=\"#3#\" />"
        ),

        "room" => array (
                2 => "<room id=\"#1#\" status=\"#2#\" />",
                4 => "<room id=\"#1#\" status=\"#2#\" owner=\"#4#\"><![CDATA[#3#]]></room>"
        ),

        "smileset" => array (
                3 => "<smileset name=\"#1#\" home=\"#2#\" current=\"#3#\" />"
        )
);
?>