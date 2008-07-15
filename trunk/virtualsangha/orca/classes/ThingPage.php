<?php
/***************************************************************************
*                            Orca Interactive Forum Script
*                              -----------------
*     begin                : Fr Nov 10 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Orca - Interactive Forum Script
*
* Orca is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Orca is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Orca, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


// parent object for all pages classes

class ThingPage extends Thing
{

	
// public functions

	/**
	 * constructor
	 */
	function ThingPage ()
	{
	}

    function getLangs ()
    {
        global $gConf;

        $a = array ();
        if (isset($gConf['dir']['langs']))
        {
            $d = dir($gConf['dir']['langs']);
            while (FALSE !== ($entry = $d->read()))
            {
                if ($entry == '.' || $entry == '..')
                    continue;
                $a[] = substr($entry, 0, 2);
            }
        }
        return $a;
    } 

    function getLangsXml ()
    {
        return '<langs>' . array2xml($this->getLangs(), 'lang') . '</langs>';
    }

	function getUrlsXml ()
	{
		global $gConf;		

		$ret = '';

		$ret .= "<urls>";
		$ret .= "<xsl_mode>{$gConf['xsl_mode']}</xsl_mode>";
		$ret .= "<icon>{$gConf['url']['icon']}</icon>";
		$ret .= "<img>{$gConf['url']['img']}</img>";
		$ret .= "<css>{$gConf['url']['css']}</css>";
        $ret .= "<xsl>{$gConf['url']['xsl']}</xsl>";		
        $ret .= "<js>{$gConf['url']['js']}</js>";
        $ret .= "<editor>{$gConf['url']['editor']}</editor>";
		$ret .= "</urls>\n";		
		
		return $ret;		
	}

    function addHeaderFooter (&$li, $content)
    {
        global $gConf;
        global $glHeader;
	global $glFooter;

        $ret = '';

        $ret .= "<root>\n";

        $ret .= '<disable_boonex_footers>' . (int)getConfigParam('disable_boonex_footers') . '</disable_boonex_footers>';

        $ret .= '<header><![CDATA['.$glHeader.']]></header>';
		$ret .= '<footer><![CDATA['.$glFooter.']]></footer>';

        $ret .= "<min_point>{$gConf['min_point']}</min_point>\n";
        
        $ret .= "<base>{$gConf['url']['base']}</base>\n";

        $ret .= "<title>{$gConf['def_title']}</title>\n";

        $integration_xml = '';
        @include ($gConf['dir']['base'] . 'xml/xml.php');
        $ret .= $integration_xml;

		$ret .= $this->getUrlsXml ();

        if (is_array($li))
        {
            $ret .= "<logininfo>";
            reset ($li);
            while (list($k,$v) = each($li))
            {
                $ret .= "<$k>$v</$k>";
            }            
            $ret .= "</logininfo>";

            if (1 == $li['admin'])
            {
                $ret .= $this->getLangsXml();
            }
        }

        

        $ret .= "<page>\n";

        $ret .= $content;

        $ret .= "</page>\n";

        $ret .= "</root>\n";

        return $ret;
    }

	/**
	 * returns page XML
	 */
	function getPageXML (&$li)
	{
		return $this->addHeaderFooter ($li, $this->content);
	}


	/**
	 * write cache to a file 
	 *	@param $fn	filename to write to
	 *	@param $s	string to write	
	 */
	function cacheWrite ($fn, $s)
	{
		global $gConf;

		if (!$gConf['cache']['on']) return;
		
		$f = fopen ($gConf['dir']['xmlcache'] . $fn, "w");

		if (!$f)
		{
			$mk = new Mistake ();
			$mk->log ("ThingPage::readCache - can not open file({$gConf['dir']['xmlcache']}$fn) for writing");
			$mk->displayError ("[L[Site is unavailable]]");
		}
			
		fwrite ($f, $s);

		fclose ($f);		
	}

	/**
	 * read cache from a file 
	 *	@param $fn		filename to read from
	 *	@param return	string from a file	
	 */
	function cacheRead ($fn)
	{
		global $gConf;

		$f = fopen ($gConf['dir']['xmlcache'] . $fn, "r");

		if (!$f)
		{
			$mk = new Mistake ();
			$mk->log ("ThingPage::readCache - can not open file({$gConf['dir']['xmlcache']}$fn) for reading");
			$mk->displayError ("[L[Site is unavailable]]");
		}
	
		$s = '';
		while ($st = fread ($f, 1024)) $s .= $st;

		fclose ($f);

		return $s;	
	}

	/**
	 * check if cache is available 
	 *	@param $fn		filename to check
	 *	@param return	true if cache is available
	 */
	function cacheExists ($fn)
	{
		global $gConf;
		return file_exists ($gConf['dir']['xmlcache'] . $fn);
	}

	/**
	 * check if cache is enabled
	 */
	function cacheEnabled ()
	{
		global $gConf;
		return $gConf['cache']['on'];
	}

// private functions
	
}





?>