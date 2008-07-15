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
                1 => "<msg><![CDATA[#1#]]></msg>",
                6 => "<msg color=\"#2#\" bold=\"#3#\" underline=\"#4#\" italic=\"#5#\" smileset=\"#6#\"><![CDATA[#1#]]></msg>"
        ),

        "user" => array (
                2 => "<user id=\"#1#\" nick=\"#2#\" />",
                6 => "<user id=\"#1#\" nick=\"#2#\" sex=\"#3#\" age=\"#4#\" img=\"#5#\" profile=\"#6#\" />",
        ),

        "result" => array (
                1 => "<result value=\"#1#\" />",
                6 => "<result value=\"#1#\" uId=\"#3#\" uNick=\"#4#\" uImg=\"#5#\" uProfile=\"#6#\"><![CDATA[#2#]]></result>"
        ),

        "smileset" => array (
                3 => "<smileset name=\"#1#\" home=\"#2#\" current=\"#3#\" />"
        )
);
?>