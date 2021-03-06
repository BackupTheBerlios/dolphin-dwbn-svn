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


// login | join  functions

class Login extends ThingPage
{
	// join form fields
	var $f_join =  array (
		'username' => array (
			'value' => '',
			'type' => 'text',
			'title' => 'Username',			
			'regexp' => '/^[A-Za-z0-9_]{4,12}$/',
			'err' => 'Username must be from 4 to 12 characters',
			'attributes' => array ('class' => 'sh'),
			),
		'email' => array (
			'value' => '',
			'type' => 'text',
			'title' => 'Email',
			'regexp' => '/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/',
			'err' => 'Email must be valid',
			'attributes' => array ('class' => 'sh'),
			),		
	);

	// login form fields
	var $f_login =  array (
		'username' => array (
			'value' => '',
			'type' => 'text',
			'title' => 'Username',			
			'regexp' => '/^[A-Za-z0-9_]{4,12}$/',
			'err' => 'Username must be from 4 to 12 characters',
			'attributes' => array ('class' => 'sh'),
			),
		'pwd' => array (
			'value' => '',
			'type' => 'password',
			'title' => 'Password',
			'regexp' => '/^[A-Za-z0-9_]+$/',
			'err' => 'Password must be valid',
			'attributes' => array ('class' => 'sh'),
			),		
	);
		
	/**
	 * constructor
	 */
	function Login ()
	{

	}

	/**
	 * join window xml
	 */
	function getJoinForm ()
	{
		$cu = $this->getUrlsXml ();	
		return 	"<root>$cu<join><join_form>" . array2xml ($this->f_join) . "</join_form></join></root>";
	}

	/**
	 * login window xml
	 */
	function getLoginForm ()
    {
		$cu = $this->getUrlsXml ();	
		return 	"<root>$cu<login><login_form>" . array2xml ($this->f_login) . "</login_form></login></root>";
	}

	/**
	 * submit join 
	 *	@param $p	join fields
	 */	
	function joinSubmit ($p)
	{
		$js = '';
		
		// check input values
		for (reset($this->f_join); list ($name, $a) = each ($this->f_join);)
		{
			if ($a['regexp'])
				if (!preg_match($a['regexp'], $p[$name]))
					$js .= $this->_getJsErr($name, $a['err']);
									
			switch ($name)
			{
				case 'username':
					if (!$this->_checkUniqUser($p[$name]))
						$js .= $this->_getJsErr($name, 'Username must be uniq');
					break;
				case 'email':					
					if (!$this->_checkUniqEmail($p[$name]))
						$js .= $this->_getJsErr($name, 'Email must be uniq');					
					break;
			}
		}

		// add user
		if (!$js)
		{			
			$db = new DbLogin();
			
			$p['pwd'] = $this->_genPwd();
			
			if (!$db->insertUser($p))
			{
				$js .= $this->_getJsErr('email', 'Database error');
			}
			
			// send activation mail
			$mail = new BxMail ();
			if (!$mail->sendActivationMail($p))
			{
				$js .= $this->_getJsErr('email', 'Send mail failed');
			}
		}
		
		return '<js>' . $js . '</js>';
	}	


	/**
	 * submit login
	 *	@param $p	username/password fields
	 */		
	function loginSubmit ($p)
	{
		$js = '';
		
		// check input values
		for (reset($this->f_login); list ($name, $a) = each ($this->f_login);)
		{
			if ($a['regexp'])
				if (!preg_match($a['regexp'], $p[$name]))
					$js .= $this->_getJsErr($name, $a['err']);
									
		}

		// process login
		if (!$js)
		{			
			$db = new DbLogin();			
			
			$p['pwd'] = md5($p['pwd']);
			
			if (!$db->checkLogin($p))
			{
				$js .= $this->_getJsErr('pwd', 'password or login is incorrect');
			}
			else
			{
				setcookie ('orca_user', $p['username']);
				if (!setcookie ('orca_pwd', $p['pwd']))
					$js .= $this->_getJsErr('pwd', 'Cookies must be enabled to process login');
			}
		}
		
		return '<js>' . $js . '</js>';
	}	
	
	
	// private functions
	
	function _getJsErr ($name, $err)
	{
		return <<<EOS
			{
				var e = document.getElementById('f_err_$name');
				e.innerHTML = '$err';
				e.style.display = 'inline';
			}
EOS;
		
	}
	
	function  _checkUniqUser($s)
	{				
		$db = new DbLogin();
		return $db->getUserByName ($s) == $s ? false : true;
	}
	
	function  _checkUniqEmail($s)
	{
		$db = new DbLogin();	
		return $db->getUserByEmail ($s) == $s ? false : true;
	}	
	
	function _genPwd ()
	{
		$ret = '';
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		srand($seed);
		
		for ($i=0; $i < 8; ++$i)
		{
			
			switch (rand(1,3))
			{
				case 1: 
					$c = chr(rand(ord('a'),ord('z')));
					break;
				case 2: 
					$c = chr(rand(ord('A'),ord('Z')));
					break;
				case 3: 
					$c = chr(rand(ord('0'),ord('9')));
					break;
			}
			$ret .= $c;
		}
		return $ret;		
	}
		
}


?>
