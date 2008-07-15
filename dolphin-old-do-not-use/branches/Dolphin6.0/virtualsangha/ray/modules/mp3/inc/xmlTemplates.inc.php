<?
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by BoonEx Ltd. and cannot be modified for other than personal usage.
* This product cannot be redistributed for free or a fee without written permission from BoonEx Ltd.
* This notice may not be removed from the source code.
*
***************************************************************************/

$aXmlTemplates = array (
	"cat" => array (
		2 => '<cat id="#1#"><title><![CDATA[#2#]]></title></cat>',
		3 => '<cat id="#1#" parent="#2#"><path><![CDATA[#3#]]></path></cat>'
	),
		
	"file" => array (
		2 => '<file id="#1#" file="#2#" />',
		6 => '<file id="#1#" file="#2#" time="#3#"><title><![CDATA[#4#]]></title><tags><![CDATA[#5#]]></tags><desc><![CDATA[#6#]]></desc></file>',
		7 => '<file id="#1#" file="#2#" time="#3#"  active="#4#"><title><![CDATA[#5#]]></title><tags><![CDATA[#6#]]></tags><desc><![CDATA[#7#]]></desc></file>'
	),

	"user" => array (
		5 => '<user id="#1#" nick="#2#" profile="#3#" all="#4#" approval="#5#" />'
	),

	"result" => array (
		1 => '<result value="#1#" />',
		2 => '<result value="#1#" status="#2#" />'		
	)
);
?>