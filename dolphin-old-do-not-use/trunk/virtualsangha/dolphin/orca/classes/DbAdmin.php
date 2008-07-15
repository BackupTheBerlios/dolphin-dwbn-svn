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


// admin operations with database

if (!defined('TF_FORUM'))       define ('TF_FORUM',			'`'.$gConf['db']['prefix'].'forum`');
if (!defined('TF_FORUM_CAT'))   define ('TF_FORUM_CAT',		'`'.$gConf['db']['prefix'].'forum_cat`');
if (!defined('TF_FORUM_POST'))  define ('TF_FORUM_POST',	'`'.$gConf['db']['prefix'].'forum_post`');
if (!defined('TF_FORUM_TOPIC')) define ('TF_FORUM_TOPIC',	'`'.$gConf['db']['prefix'].'forum_topic`');

define ('CAT_ORDER_STEP', 128);

class DbAdmin extends BxDb
{

	/**
	 * constructor
	 */
	function DbAdmin ()
	{
		global $gConf;
		parent::BxDb ($gConf['db']['db'], $gConf['db']['user'], $gConf['db']['pwd'], $gConf['db']['host'], $gConf['db']['port'], $gConf['db']['sock']);
	}

	function deleteCategoryAll ($cat_id)
	{	
		$sql = "SELECT `forum_id` FROM " . TF_FORUM . " WHERE `cat_id` = '$cat_id'";
		$a = $this->getAll ($sql);
		for ( reset($a) ; list (, $r) = each ($a) ; )
		{
			$this->deleteForumPosts ($r['forum_id']);
			$this->deleteForumTopics ($r['forum_id']);
			$this->deleteForum ($r['forum_id']);			
		}		
		return $this->deleteCategory ($cat_id);
	}

	function getCatByForumId ($forum_id)
	{
		$sql = "SELECT `cat_id`, `cat_uri` FROM " . TF_FORUM . " INNER JOIN " . TF_FORUM_CAT . " USING (`cat_id`) WHERE `forum_id` = '$forum_id' LIMIT 1";
		return $this->getRow ($sql);
	}
	
	function getCatOrder ($cat_id)
	{
		if ($cat_id)
			$sql = "SELECT `cat_order` FROM " . TF_FORUM_CAT . " WHERE `cat_id` = $cat_id LIMIT 1";
		else
			$sql = "SELECT `cat_order` FROM  " . TF_FORUM_CAT . " ORDER BY `cat_order` DESC LIMIT 1";
		return $this->getOne($sql);
	}
	
	function setNewOrder ($cat_id, $new_order)
	{
		$sql = "UPDATE " . TF_FORUM_CAT . " SET `cat_order` = '$new_order' WHERE `cat_id` = '$cat_id'";
		return $this->query ($sql);
	}
	
	function getCatsInOrder ($cat_order, $dir, $num)
	{		
		$sql = "SELECT `cat_id`,`cat_order` FROM  " . TF_FORUM_CAT . " WHERE `cat_order` ".($dir == 'up' ? '<' : '>')." $cat_order  ORDER BY  `cat_order` " .($dir == 'up' ? 'DESC' : 'ASC'). " LIMIT $num";		
		return $this->getAll ($sql);
	}
	
	function deleteForumAll ($forum_id)
	{
		$this->deleteForumPosts ($forum_id);
		$this->deleteForumTopics ($forum_id);
		return $this->deleteForum ($forum_id);
	}
	
	function deleteCategory ($cat_id)
	{				
		$sql = "DELETE FROM " . TF_FORUM_CAT . " WHERE `cat_id` = '$cat_id'";
		return $this->query ($sql);
	}

	function deleteForumPosts ($forum_id)
	{
		$sql = "DELETE FROM " . TF_FORUM_POST . " WHERE `forum_id` = '$forum_id'";
		return $this->query ($sql);
	}

	function deleteForumTopics ($forum_id)
	{
		$sql = "DELETE FROM " . TF_FORUM_TOPIC . " WHERE `forum_id` = '$forum_id'";
		return $this->query ($sql);
	}

	function deleteForum ($forum_id)
	{
		$sql = "DELETE FROM " . TF_FORUM . " WHERE `forum_id` = '$forum_id'";
		return $this->query ($sql);
	}	


	function getCatName ($cat_id)
	{
		$sql = "SELECT `cat_name` FROM " . TF_FORUM_CAT . " WHERE `cat_id` = '$cat_id' LIMIT 1";
		return $this->getOne ($sql);
	}

	function editCategory ($cat_id, $cat_name)
	{
		$sql = "UPDATE " . TF_FORUM_CAT . " SET `cat_name` = '$cat_name' WHERE `cat_id` = '$cat_id'";
		return $this->query ($sql);
	}
	
	function insertCategory ($cat_name, $uri)
	{
		$sql = "INSERT INTO " . TF_FORUM_CAT . " SET `cat_name` = '$cat_name', `cat_uri` = '$uri',`cat_order` = " . ($this->getCatOrder (0) + CAT_ORDER_STEP);
		return $this->query ($sql);
	}		

	function getForum ($forum_id)
	{
		$sql = "SELECT `cat_id`, `forum_title`, `forum_desc`, `forum_type` FROM " . TF_FORUM . " WHERE `forum_id` = '$forum_id' LIMIT 1";
		return $this->getRow ($sql);
	}

	function editForum ($forum_id, $title, $desc, $type)
    {   
        $sql = "UPDATE " . TF_FORUM . " SET `forum_title` = '$title', `forum_desc` = '$desc', `forum_type` = '$type'  WHERE `forum_id` = '$forum_id'";		
		return $this->query ($sql);
	}

	function insertForum ($cat_id, $title, $desc, $type, $uri)
    {		        
		$sql = "INSERT INTO " . TF_FORUM . " SET `cat_id` = '$cat_id', `forum_title` = '$title', `forum_desc` = '$desc', `forum_type` = '$type', `forum_uri` = '$uri'";
		return $this->query ($sql);
	}

	function getReportedPosts ($u)
	{
		global $gConf;
		
		$sql_add1 = "'-1' AS `voted`, 0 as `vote_user_point`, ";
		$sql_add2 = '';
		
		if ($u)
		{
			$sql_add1 = "(1 - ISNULL(t2.`post_id`)) AS `voted`, t2.`vote_point` as `vote_user_point`, ";
			$sql_add2 = " LEFT JOIN " . TF_FORUM_VOTE . " AS t2 ON ( t2.`user_name` = '$u' AND t1.`post_id` = t2.`post_id`) ";
		}
		
		$sql =  "SELECT `forum_id`, `topic_id`, t1.`post_id`, `user`, `post_text`, `votes`, $sql_add1 DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `when` FROM " . TF_FORUM_POST . " AS t1 $sql_add2 WHERE `reports` != 0 ORDER BY t1.`when` ASC";
				
		return $this->getAll ($sql);
	}	

    function isLocked ($topic_id)        
    {
        return $this->getOne ("SELECT `topic_locked` FROM " . TF_FORUM_TOPIC . " WHERE `topic_id` = $topic_id LIMIT 1");
    }

    function lock ($topic_id)        
    {
        return $this->query ("UPDATE " . TF_FORUM_TOPIC . " SET `topic_locked` = IF(`topic_locked`, 0, 1) WHERE `topic_id` = $topic_id LIMIT 1");
    }

// private functions

}





?>
