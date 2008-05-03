<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


##                                                  ##
## Set of functions to work with daily quotes       ##
##                                                  ##

$quote_table = 'DailyQuotes';

$quote_html = <<<EOS

<!-- QUOTES [begin] -->
<table cellspacing="0" cellpadding=0 class="text">
<tr><td class="quotes_text" align=right><i>%Text%</i></td></tr>
<tr><td class="quotes_author" align=right><b>%Author%</b></td></tr>
</table>
<!-- QUOTES [ end ] -->

EOS;

/**
 * get a quote from the database
 * returns HTML text that represents quote - text and author
 */
function quote_get ( )
{
	global $quote_table;
	global $quote_html;

	$arr = db_arr("SELECT `Text`, `Author` FROM $quote_table ORDER BY RAND() LIMIT 1");
	$ret = $quote_html;
	$ret = str_replace('%Text%', process_text_output($arr['Text']), $ret);
	$ret = str_replace('%Author%', process_line_output($arr['Author']), $ret);

	return $ret;
}


?>
