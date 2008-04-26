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


// error handling functions

class Mistake extends ThingPage
{

// private variables
	
	var $_error;							// current error string

// public functions

	/**
	 * constructor
	 */
	function Mistake ()
	{
	}


	/**
	 *	set error string for the object
	 */
	function log ($s)
	{
		global $gConf;

		if (strlen ($gConf['dir']['error_log']))
		{
			$fp = @fopen ($gConf['dir']['error_log'], "a");
			if ($fp)
			{
				@fwrite ($fp, date ('Y-m-d H:i:s', time ()) . "\t$s\n");
				@fclose ($fp);
			}
		}


		if($gConf['debug'])
			$this->displayError($s);		

		$this->_error = $s;
	}	


	function displayError ($s)
	{
		global $gConf;

		transCheck ($this->getErrorPageXML ($s), $gConf['dir']['xsl'] . 'default_error.xsl', 1);

		exit;
	}


	/**
	 * returns page XML
	 */
	function getErrorPageXML ($s)
	{
		return $this->addHeaderFooter ($s, $s);
	}

// private functions


}





?>
