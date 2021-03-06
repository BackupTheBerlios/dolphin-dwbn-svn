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


// login | join  functions

class Login extends ThingPage
{
	// join form fields
	var $f_join =  array (
		'username' => array (
			'value' => '',
			'type' => 'text',
			'title' => '[L[Username]]',
			'regexp' => '/^[A-Za-z0-9_]{4,12}$/',
			'err' => '[L[Join Login Username Error]]', 
			'attributes' => array ('class' => 'sh'),
			),
		'email' => array (
			'value' => '',
			'type' => 'text',
			'title' => '[L[Email]]',
			'regexp' => '/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/',
			'err' => '[L[Join Email Error]]',
			'attributes' => array ('class' => 'sh'),
			),		
	);

	// login form fields
	var $f_login =  array (
		'username' => array (
			'value' => '',
			'type' => 'text',
			'title' => '[L[Username]]',			
			'regexp' => '/^[A-Za-z0-9_]{4,12}$/',
			'err' => '[L[Join Login Username Error]]',
			'attributes' => array ('class' => 'sh'),
			),
		'pwd' => array (
			'value' => '',
			'type' => 'password',
			'title' => '[L[Password]]',
			'regexp' => '/^[A-Za-z0-9_]+$/',
			'err' => '[L[Login Password Error]]',
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
						$js .= $this->_getJsErr($name, '[L[Username must be uniq]]');
					break;
				case 'email':					
					if (!$this->_checkUniqEmail($p[$name]))
						$js .= $this->_getJsErr($name, '[L[Email must be uniq]]');
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
				$js .= $this->_getJsErr('email', '[L[Database error]]');
			}
			
			// send activation mail
			$mail = new BxMail ();
			if (!$mail->sendActivationMail($p))
			{
				$js .= $this->_getJsErr('email', '[L[Send mail failed]]');
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
				$js .= $this->_getJsErr('pwd', '[L[password or login is incorrect]]');
			}
			else
			{
				setcookie ('orca_user', $p['username']);
				if (!setcookie ('orca_pwd', $p['pwd']))
					$js .= $this->_getJsErr('pwd', '[L[Cookies must be enabled to process login]]');
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
