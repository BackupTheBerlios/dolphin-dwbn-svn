<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define('BX_DOL_TABLE_PROFILES', '`Profiles`');


require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolMistake.php' );

class BxDolDb extends BxDolMistake
{
	var $host, $port, $socket, $dbname, $user, $password, $link;
	var $current_res, $current_arr_type;

	/*
	*set database parameters and connect to it
	*/
	function BxDolDb()
	{
		$this->host = DATABASE_HOST;
		$this->port = DATABASE_PORT;
		$this->socket = DATABASE_SOCK;
		$this->dbname = DATABASE_NAME;
		$this->user = DATABASE_USER;
		$this->password = DATABASE_PASS;
		$this->current_arr_type = MYSQL_ASSOC;

		//	connect to db automatically
		$this->connect();
	}

	/**
	 * connect to database with appointed parameters
	 */
	function connect()
	{
		$full_host = $this->host;
		$full_host .= $this->port ? ':'.$this->port : '';
		$full_host .= $this->socket ? ':'.$this->socket : '';

		$this->link = @mysql_pconnect($full_host, $this->user, $this->password) or $this->error('Cannot connect to database');
		if (!$this->link)
		{
			echo 'Could not connect to MySQL database. <br />Did you properly edit <b>inc/header.inc.php</b> file ?';
			exit;
		}

		if (!$this->select_db())
		{
			echo 'Could not select MySQL database. <br />Did you properly edit <b>inc/header.inc.php</b> file ?';
			exit;
		}
		
		if( !mysql_query( "SET NAMES 'utf8'", $this -> link ) ) {
			echo 'Could not make SET NAMES. Please upgrade your server.';
			exit;
		}
	}

	function select_db()
	{
		return @mysql_select_db($this->dbname, $this->link) or $this->error('Cannot complete query (select_db)');
	}

	/**
	 * close mysql connection
	 */
	function close()
	{
		mysql_close($this->link);
	}




	/**
	 * execute sql query and return one row result
	 */
	function getRow($query, $arr_type = MYSQL_ASSOC)
	{
		if(!$query)
			return array();
		if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM)
			$arr_type = MYSQL_ASSOC;
		$res = mysql_query($query, $this->link) or $this->error('Cannot complete query (getRow)');
		$arr_res = array();
		if($res && mysql_num_rows($res))
		{
			$arr_res = mysql_fetch_array($res, $arr_type);
			mysql_free_result($res);
		}
		return $arr_res;
	}

	/**
	 * execute sql query and return one value result
	 */
	function getOne($query)
	{
		if(!$query)
			return false;
		$res = mysql_query($query, $this->link) or $this->error("Cannot complete query [$query] (getOne)");
		$arr_res = array();
		if($res && mysql_num_rows($res))
			$arr_res = mysql_fetch_array($res);
		if(count($arr_res))
			return $arr_res[0];
		else
			return false;
	}

	/**
	 * execute sql query and return the first row of result
	 * and keep $array type and poiter to all data
	 */
	function getFirstRow($query, $arr_type = MYSQL_ASSOC)
	{
		if(!$query)
			return array();
		if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM)
			$this->current_arr_type = MYSQL_ASSOC;
		else
			$this->current_arr_type = $arr_type;
		$this->current_res = mysql_query($query, $this->link) or $this->error('Cannot complete query (getFirstRow)');
		$arr_res = array();
		if($this->current_res && mysql_num_rows($this->current_res))
			$arr_res = mysql_fetch_array($this->current_res, $this->current_arr_type);
		return $arr_res;
	}

	/**
	 * return next row of pointed last getFirstRow calling data
	 */
	function getNextRow()
	{
		$arr_res = mysql_fetch_array($this->current_res, $this->current_arr_type);
		if($arr_res)
			return $arr_res;
		else
		{
			mysql_free_result($this->current_res);
			$this->current_arr_type = MYSQL_ASSOC;
			return array();
		}
	}

	/**
	 * return number of affected rows in current mysql result
	 */
	function getNumRows($res = false)
	{
		if(!$res)
			$res = @mysql_num_rows($this->current_res);

		if((int)$res > 0)
			return (int)$res;
		else
			return 0;
	}


	/**
	 * execute any query return number of rows affected/false
	 */
	function query($query)
	{
		if(!$query)
			return false;
		$res = mysql_query($query, $this->link) or $this->error('Cannot complete query (query)');

		if($res)
			return mysql_affected_rows();
		else
			return false;
	}

	/**
	 * execute sql query and return table of records as result
	 */
	function getAll($query, $arr_type = MYSQL_ASSOC)
	{
		if(!$query)
			return array();
		if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM)
			$arr_type = MYSQL_ASSOC;

		$res = mysql_query($query, $this->link) or $this->error('Cannot complete query [' . $query . '] (getAll) ');
		$arr_res = array();
		if($res)
		{
			while($row = mysql_fetch_array($res, $arr_type))
			{
				$arr_res[] = $row;
			}
			mysql_free_result($res);
		}
		return $arr_res;
	}

    function lastId()
    {
        return mysql_insert_id($this->link);
    }

	function error($text)
	{
		$this->log($text.': '.mysql_error($this->link));
		//echoDbg( debug_backtrace() ); //output debug information
	}

}

?>
