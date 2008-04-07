<?
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


//  mail sending class

class BxMail extends Mistake 
{	
    var $_sSenderName = 'Orca Forum';
	
	/**
	 * send mail with password
	 * @param $p	email template variables to replace
	 */ 
	function sendActivationMail (&$p)
	{
		global $gConf;

		$subj = "Orca Forum Registration Details";

		$mailContent = <<<EOF
Dear {username},

Your username: {username}
Your password: {pwd}
Site URL: {$gConf['url']['base']}

Thank you for joining.

Best Regards, Orca Forum.

EOF;

		for (reset ($p) ; list ($k, $v) = each ($p); )
		{
			$mailContent = str_replace ('{'.$k.'}', $v, $mailContent);
		}


		$headers = "From:" . $gConf['email']['sender'] . "\r\nContent-type: text/html";				
		
		return mail ($p['email'], $subj, $mailContent, $this->additionalHeaders, '-f'.$gConf['email']['sender']);
	}
	
}
?>
