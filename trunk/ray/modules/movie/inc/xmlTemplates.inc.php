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
		1 => '<result value="#1#" />',
		2 => '<result value="#1#" status="#2#" />',
	),
	"file" => array (
		5 => '<file id="#1#" file="#2#" save="#3#" image="#4#" thumb="#5#" />',
		8 => '<file id="#1#" file="#2#" save="#3#" image="#4#" time="#5#"><title><![CDATA[#6#]]></title><tags><![CDATA[#7#]]></tags><desc><![CDATA[#8#]]></desc></file>',
		10 => '<file id="#1#" file="#2#" save="#3#" image="#4#" thumb="#5#" time="#6#" active="#7#"><title><![CDATA[#8#]]></title><tags><![CDATA[#9#]]></tags><desc><![CDATA[#10#]]></desc></file>'
	),
	"user" => array (
		5 => '<user id="#1#" nick="#2#" profile="#3#" all="#4#" approval="#5#" />'
	)
)
?>