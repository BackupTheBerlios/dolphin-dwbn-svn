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
                8 => "<msg id=\"#2#\" uId=\"#3#\" uNick=\"#4#\" uSex=\"#5#\" uAge=\"#6#\" uImg=\"#7#\" uProfile=\"#8#\"><![CDATA[#1#]]></msg>"//--- IM message
        ),

        "user" => array (
                1 => "<user id=\"#1#\" />",
                6 => "<user id=\"#1#\" nick=\"#2#\" password=\"#3#\" sex=\"#4#\" age=\"#5#\" online=\"#6#\" />",
                7 => "<user id=\"#1#\" nick=\"#2#\" password=\"#3#\" sex=\"#4#\" age=\"#5#\" online=\"#6#\" img=\"#7#\" />",
                8 => "<user id=\"#1#\" nick=\"#2#\" password=\"#3#\" sex=\"#4#\" age=\"#5#\" online=\"#6#\" img=\"#7#\" profile=\"#8#\" />"
        ),

        "result" => array (
                1 => "<result value=\"#1#\" />"
        )
);
?>