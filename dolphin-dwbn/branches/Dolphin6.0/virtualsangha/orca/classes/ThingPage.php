<?php
/***************************************************************************
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Fr Nov 10 2006
*     Copyright        : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
*
* Orca is free software; you can redistribute it and/or modify it under 
* the terms of the GNU General Public License as published by the 
* Free Software Foundation; either version 2 of the 
* License, or any later version.      
*
* Orca is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License along with Orca, 
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

	function getSmiles ()
	{
		global $gConf;

		$icons = '';
		
		if ($handle = opendir($gConf['dir']['smile'])) 
		{			
			while (false !== ($file = readdir($handle))) 
			{
				if (!preg_match ("/\.gif$/", $file)) continue;
				$icons .= "<smicon>{$gConf['url']['smile']}$file</smicon>";
			}

			closedir($handle);
		}

		return "<smiles>$icons</smiles>";
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
		$ret .= "<smile>{$gConf['url']['smile']}</smile>";
		$ret .= "<xsl>{$gConf['url']['xsl']}</xsl>";		
		$ret .= $this->getSmiles ();
		$ret .= "</urls>\n";		
		
		return $ret;		
	}

    function addHeaderFooter (&$li, $content)
    {
        global $gConf;


        $ret = '';

        $ret .= "<root>\n";

        eval($GLOBALS['l']('JHJldCAuPSAnPGRpc2FibGVfYm9vbmV4X2Zvb3RlcnM+JyAuIChpbnQpZ2V0Q29uZmlnUGFyYW0oJ2Rpc2FibGVfYm9vbmV4X2Zvb3RlcnMnKSAuICc8L2Rpc2FibGVfYm9vbmV4X2Zvb3RlcnM+Jzs='));

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
			$mk->displayError ("Sorry, site is unavailable now, please try again later.");
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
			$mk->displayError ("Sorry, site is unavailable now, please try again later.");
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
