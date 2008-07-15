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


define ('TF_FORUM',			 '`'.$gConf['db']['prefix'].'forum`');
define ('TF_FORUM_CAT',		 '`'.$gConf['db']['prefix'].'forum_cat`');
define ('TF_FORUM_POST',	 '`'.$gConf['db']['prefix'].'forum_post`');
define ('TF_FORUM_TOPIC',	 '`'.$gConf['db']['prefix'].'forum_topic`');
define ('TF_FORUM_VOTE',	 '`'.$gConf['db']['prefix'].'forum_vote`');
define ('TF_FORUM_REPORT',	 '`'.$gConf['db']['prefix'].'forum_report`');
define ('TF_FORUM_FLAG',	 '`'.$gConf['db']['prefix'].'forum_flag`');
define ('TF_FORUM_USER_ACT', '`'.$gConf['db']['prefix'].'forum_user_activity`');
define ('TF_FORUM_USER_STAT','`'.$gConf['db']['prefix'].'forum_user_stat`');

// forum database functions

class DbForum extends BxDb
{

	/**
	 * constructor
	 */
	function DbForum ()
	{
		global $gConf;
		parent::BxDb ($gConf['db']['db'], $gConf['db']['user'], $gConf['db']['pwd'], $gConf['db']['host'], $gConf['db']['port'], $gConf['db']['sock']);
	}
	
	function searchMessages ($s, $u, $f, $type, $posts, $limit)
	{
		global $gConf;

		if ($limit > 500)  $limit = 500;

		$u = trim ($u);
		$s = trim ($s);

		$sql_w = '';

		if (strlen($s) > 2) 
		{
			$s = preg_replace ('/\s+/', '%', $s);
			if ('msgs' == $type) // messages
				$sql_w .= " AND `post_text` LIKE '%$s%' ";
			else // titles
				$sql_w .= " AND `topic_title` LIKE '%$s%' ";
		}

		if (strlen($u) > 2) 
		{			
			$u = preg_replace ('/\s+/', '%', $u);
			if ('msgs' == $type)
				$sql_w .= " AND `user` LIKE '%$u%' ";
			else
				$sql_w .= " AND `first_post_user` LIKE '%$u%' ";
		}
		
		if ($f > 0)
		{
			$sql_w .= " AND t3.`forum_id` = '$f' ";
		}

		$x = '';
		if ($posts) $x .= ', `post_text` ';


		if ('msgs' == $type)
		{
			return $this->getAll ("
	SELECT DISTINCTROW t4.`cat_id`, t4.`cat_uri`, `cat_name`, t3.`forum_id`, t3.`forum_uri`, `forum_title`, t2.`topic_id`, t2.`topic_uri`, `topic_title`, `post_id`, DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `date`, `user` $x
	FROM " . TF_FORUM_POST . " AS t1
	INNER JOIN  " . TF_FORUM_TOPIC . " AS t2 ON (t2.`topic_id` = t1.`topic_id`)
	INNER JOIN  " . TF_FORUM . " AS t3 ON (t1.`forum_id` = t3.`forum_id`)
	INNER JOIN " . TF_FORUM_CAT . " AS t4 ON (t3.`cat_id` = t4.`cat_id`)
	WHERE 1 $sql_w
	ORDER BY `last_post_when` DESC
	LIMIT $limit");
		}
		else // search titles	
		{
			return $this->getAll ("
	SELECT t4.`cat_id`, t4.`cat_uri`, `cat_name`, t3.`forum_id`, t3.`forum_uri`, `forum_title`, t2.`topic_id`, t2.`topic_uri`, `topic_title`, DATE_FORMAT(FROM_UNIXTIME(`first_post_when`),'{$gConf['date_format']}') AS `date`, `first_post_user` AS `user`
	FROM " . TF_FORUM_TOPIC . " AS t2
	INNER JOIN  " . TF_FORUM . " AS t3 ON (t2.`forum_id` = t3.`forum_id`)
	INNER JOIN " . TF_FORUM_CAT . " AS t4 ON (t3.`cat_id` = t4.`cat_id`)
	WHERE 1 $sql_w
	ORDER BY `last_post_when` DESC
	LIMIT $limit");
		}
	}

	function getCategs ()
	{
		return $this->getAll ("SELECT `cat_id`, `cat_uri`, `cat_name`, `cat_icon` FROM " . TF_FORUM_CAT . " ORDER BY `cat_order` ASC");
	}

	function getCatTitle ($id)
	{
		return $this->getOne ("SELECT `cat_name` FROM " . TF_FORUM_CAT . " WHERE `cat_id` = '$id'");
	}

	function getCat ($id)
	{
		return $this->getRow ("SELECT `cat_id`, `cat_uri`, `cat_name` FROM " . TF_FORUM_CAT . " WHERE `cat_id` = '$id'");
    }

	function getForums ($c)
	{
		global $gConf;
		return $this->getAll ( "SELECT `forum_id`, `forum_uri`, `cat_id`, `forum_title`, `forum_desc`, `forum_type`, `forum_posts`, `forum_topics`, DATE_FORMAT(FROM_UNIXTIME(`forum_last`),'{$gConf['date_format']}') AS `forum_last`, `forum_last` AS `forum_last_ts` FROM " . TF_FORUM . ($c ? " WHERE `cat_id` = '$c'": '') . ' ORDER BY `forum_id` ASC');
    }

	function getForumsByCatUri ($c)
	{
		global $gConf;
		return $this->getAll ( "SELECT `forum_id`, `forum_uri`, `tc`.`cat_id`, `tc`.`cat_uri`, `forum_title`, `forum_desc`, `forum_type`, `forum_posts`, `forum_topics`, DATE_FORMAT(FROM_UNIXTIME(`forum_last`),'{$gConf['date_format']}') AS `forum_last`, `forum_last` AS `forum_last_ts` FROM " . TF_FORUM . " AS `tf` INNER JOIN " . TF_FORUM_CAT . " AS `tc` ON (`tf`.`cat_id` = `tc`.`cat_id`) " . ($c ? " WHERE `cat_uri` = '$c'": '') . ' ORDER BY `forum_id` ASC');
	}    

	function getForum ($f)
    {
        return $this->getForumBy ('forum_id', $f);
	}

	function getForumByUri ($f)
    {
        return $this->getForumBy ('forum_uri', $f);
    }

	function getForumBy ($sName, $sVal)
	{
		global $gConf;
		return $this->getRow ( "SELECT `cat_id`, `forum_id`, `forum_uri`, `forum_title`, `forum_desc`, `forum_type`, `forum_posts`, DATE_FORMAT(FROM_UNIXTIME(`forum_last`),'{$gConf['date_format']}') AS `forum_last` FROM " . TF_FORUM . " WHERE `$sName` = '$sVal' LIMIT 1");
    }

	function getForumByPostId ($post_id)
	{
		return $this->getRow ( "SELECT `tf`.`forum_id`, `tf`.`forum_uri`, `tf`.`forum_type` FROM " . TF_FORUM . " AS `tf` INNER JOIN " . TF_FORUM_POST . " USING(`forum_id`) WHERE `post_id` = '$post_id' LIMIT 1");
    }

	function getPostIds ($p)
	{
		return $this->getRow ( "SELECT `forum_id`, `topic_id` FROM " . TF_FORUM_POST . " WHERE `post_id` = '$p' LIMIT 1");
	}

	function getTopicsNum ($f)
	{
		return $this->getOne ("SELECT COUNT(`topic_id`) FROM " . TF_FORUM_TOPIC . " WHERE `forum_id` = '$f'");
	}

	function getTopics ($f, $start)
	{
        global $gConf;

		return $this->getAll ( "SELECT f1.`topic_id`, f1.`topic_uri`, `topic_title`, `first_post_user`, DATE_FORMAT(FROM_UNIXTIME(`first_post_when`),'{$gConf['date_format']}') AS `first_when`, `last_post_user`, DATE_FORMAT(FROM_UNIXTIME(`last_post_when`),'{$gConf['date_format']}') AS `last_when`, `last_post_when`, `topic_posts` AS `count_posts`, `topic_sticky`, `topic_locked` FROM " . TF_FORUM_TOPIC . " AS f1 WHERE f1.`forum_id` = '$f' ORDER BY `topic_sticky` DESC, `last_post_when` DESC LIMIT $start, {$gConf['topics_per_page']}");
	}

	function getMyFlaggedTopics ($u)
	{
		global $gConf;

		$sql = "SELECT f1.`topic_id`, f1.`topic_uri`, `topic_title`, `last_post_when`, `topic_posts` AS `count_posts` FROM " . TF_FORUM_TOPIC . " AS f1 INNER JOIN "  . TF_FORUM_FLAG . " AS f2 USING (`topic_id`) WHERE f2.`user` = '$u' ORDER BY `last_post_when` DESC";

		return $this->getAll ($sql);
	}

	function getMyThreadsTopics ($u)
	{
		global $gConf;

		$sql = "SELECT DISTINCTROW f1.`topic_id`, f1.`topic_uri`, `topic_title`, `last_post_when`, `topic_posts` AS `count_posts` FROM " . TF_FORUM_TOPIC . " AS f1 INNER JOIN "  . TF_FORUM_POST . " AS f2 USING (`topic_id`) WHERE f2.`user` = '$u' ORDER BY `last_post_when` DESC";

		return $this->getAll ($sql);
	}

	function getTopic ($t)
    {
        return $this->getTopicBy ('topic_id', $t);
	}

	function getTopicByUri ($t)
    {
        return $this->getTopicBy ('topic_uri', $t);
    }

	function getTopicBy ($sName, $sVal)
	{
		return $this->getRow ( "SELECT `topic_id`, `topic_uri`, `topic_title`, `forum_title`, `forum_desc`, `forum_type`, `forum_uri`, f1.`forum_id`, `cat_id`, `topic_locked` FROM " . TF_FORUM_TOPIC . " AS f1 INNER JOIN " . TF_FORUM . " USING (`forum_id`) WHERE f1.`$sName` = '$sVal' LIMIT 1");
    }

	function getPostUser ($p)
	{
		return $this->getOne ( "SELECT `user` FROM " . TF_FORUM_POST . " WHERE `post_id` = '$p'");
	}

	function getTopicPost ($t, $x = 'last')
	{
		global $gConf;
		return $this->getRow ( "SELECT `user`, t1.`when` AS `when2`, DATE_FORMAT(FROM_UNIXTIME(`when`),'{$gConf['date_format']}') AS `when` FROM " . TF_FORUM_POST . " AS t1 WHERE `topic_id` = '$t' ORDER BY t1.`when` " . ('last' == $x ?  'DESC' : 'ASC') .  "  LIMIT 1");
	}

	function getTopicDesc ($t)
    {
        global $gConf;
		return $this->getOne ( "SELECT LEFT(`post_text`, {$gConf['topics_desc_len']}) FROM " . TF_FORUM_POST . " WHERE `topic_id` = '$t' ORDER BY `when` ASC LIMIT 1");
	}

	function editPost ($p, $text)
	{
		return $this->query ("UPDATE " . TF_FORUM_POST . " SET `post_text` = '$text' WHERE post_id = '$p'");
	}

	function newTopic ($f, $title, $text, $sticky, $user, $uri)
	{		
		$ts = time ();

		$sticky = $sticky ? $ts : 0;

		// add topic title
		if (!$this->query ("INSERT INTO" . TF_FORUM_TOPIC . " SET `topic_posts` = 1, `forum_id` = '$f', `topic_title` = '$title', `when` = '$ts', `first_post_user` = '$user', `first_post_when` = '$ts', `last_post_user` = '$user', `last_post_when` = '$ts', `topic_sticky` = '$sticky', `topic_uri` = '$uri'"))
			return false;

		// get topic_id
		if (!($topic_id = $this->getOne ("SELECT `topic_id` FROM " . TF_FORUM_TOPIC . " WHERE `forum_id` = '$f' AND `when` = '$ts'")))
			return false;

		// add topic post
		if (!$this->query ("INSERT INTO" . TF_FORUM_POST . " SET `topic_id` = '$topic_id', `forum_id` = '$f', `user` = '$user', `post_text` = '$text', `when` = '$ts'"))
			return false;

		// increase number of forum posts and set timeof last post
		if (!$this->query ("UPDATE" . TF_FORUM . " SET `forum_posts` = `forum_posts` + 1, `forum_topics` = `forum_topics` + 1, `forum_last` = '$ts' WHERE `forum_id` = '$f'"))
			return false;

		// update user stats
		if (!$this->userStatsInc ($user, $ts))
			return false;

		return true;
	}

	function deletePost ($post_id)
	{
		$a = $this->getPostIds ($post_id);

		$user = $this->getPostUser ($post_id);

		// delete post
		if (!$this->query ("DELETE FROM " . TF_FORUM_POST . " WHERE `post_id` = '$post_id'"))
			return false;

		// decrease number of forum posts
		if (!$this->query ("UPDATE" . TF_FORUM . " SET `forum_posts` = `forum_posts` - 1 WHERE `forum_id` = '{$a['forum_id']}'"))
			return false;

		// update user stats
		if (!$this->userStatsDec ($user))
			return false;

		// update last post
		$last = $this->getTopicPost ($a['topic_id'], 'last');

		// decrease number of topic posts
		if (!$this->query ("UPDATE" . TF_FORUM_TOPIC . " SET `topic_posts` = `topic_posts` - 1, `last_post_user` = '{$last['user']}', `last_post_when` = '{$last['when2']}' WHERE `topic_id` = '{$a['topic_id']}'"))
			return false;
			
		// delete topic
		if (0 == $this->getOne("SELECT COUNT(*) FROM " . TF_FORUM_POST . " WHERE `topic_id` = '{$a['topic_id']}'"))
		{
			if ($this->query("DELETE FROM " . TF_FORUM_TOPIC . " WHERE `topic_id` = '{$a['topic_id']}'"))
			{
				// descrease number of topics					
				$this->query ("UPDATE " . TF_FORUM . " SET `forum_topics` = `forum_topics` - 1 WHERE `forum_id` = '{$a['forum_id']}'");
			}
		}

		return true;
	}

	function postReply ($forum_id, $topic_id, $text, $user)
	{		
		$ts = time ();

		// add topic post
		if (!$this->query ("INSERT INTO" . TF_FORUM_POST . " SET `topic_id` = '$topic_id', `forum_id` = '$forum_id', `user` = '$user', `post_text` = '$text', `when` = '$ts'"))
			return false;

		// increase number of forum posts and set timeof last post
		if (!$this->query ("UPDATE" . TF_FORUM . " SET `forum_posts` = `forum_posts` + 1, `forum_last` = '$ts' WHERE `forum_id` = '$forum_id'"))
			return false;

		// update last post
		$last = $this->getTopicPost ($topic_id, 'last');

		// increase number of topic posts
		if (!$this->query ("UPDATE" . TF_FORUM_TOPIC . " SET `topic_posts` = `topic_posts` + 1, `last_post_user` = '{$last['user']}', `last_post_when` = '{$last['when2']}' WHERE `topic_id` = '{$topic_id}'"))
			return false;

		// update user stats
		if (!$this->userStatsInc ($user, $ts))
			return false;

		return true;
	}

	function getPosts ($t, $u)
    {
        return $this->getPostsBy ($u, '`ft`.`topic_id`', $t);
	}

	function getPostsByUri ($t, $u)
    {
        return $this->getPostsBy ($u, '`ft`.`topic_uri`', $t);
    }

	function getPostsBy ($u, $sName, $sVal)
	{
		global $gConf;
		
		$sql_add1 = "'-1' AS `voted`, 0 as `vote_user_point`, ";
		$sql_add2 = '';
		
		if ($u)
		{
			$sql_add1 = "(1 - ISNULL(t2.`post_id`)) AS `voted`, t2.`vote_point` as `vote_user_point`, ";
			$sql_add2 = " LEFT JOIN " . TF_FORUM_VOTE . " AS t2 ON ( t2.`user_name` = '$u' AND t1.`post_id` = t2.`post_id`) ";
		}
		
		$sql =  "SELECT `ft`.`forum_id`, `t1`.`topic_id`, `t1`.`post_id`, `user`, `post_text`, `votes`, $sql_add1 DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `when` FROM " . TF_FORUM_POST . " AS t1 $sql_add2 INNER JOIN " . TF_FORUM_TOPIC . " AS `ft`  ON (`ft`.`topic_id` = `t1`.`topic_id`) WHERE $sName = '$sVal' ORDER BY t1.`when` ASC";

		return $this->getAll ($sql);
    }    


	function getUserPostsList ($user, $sort, $limit = 10)
	{
		global $gConf;

		switch ($sort)
		{
			case 'top':
				$order_by = " t1.`votes` DESC ";
				break;
			case 'rnd':
				$order_by = " RAND() ";
				break;
			default:
				$order_by = " t1.`when` DESC ";
		}
				
		$sql =  "
		SELECT t1.`forum_id`, t1.`topic_id`, t2.`topic_uri`, t2.`topic_title`, t1.`post_id`, t1.`user`, LEFT(`post_text`, 256) AS `post_text`, DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `when`
			FROM " . TF_FORUM_POST . " AS t1
		INNER JOIN " . TF_FORUM_TOPIC . " AS t2
			ON (t1.`topic_id` = t2.`topic_id`)
		WHERE  t1.`user` = '$user'
		ORDER BY " . $order_by . "
		LIMIT $limit";		
		
		return $this->getAll ($sql);
	}
				
	function getAllPostsList ($sort, $limit = 10)
	{
		global $gConf;

		switch ($sort)
		{
			case 'top':
				$order_by = " t1.`votes` DESC ";
				break;
			case 'rnd':
				$order_by = " RAND() ";
				break;
			default:
				$order_by = " t1.`when` DESC ";
		}
				
		$sql =  "
		SELECT t1.`forum_id`, t1.`topic_id`, t2.`topic_uri`, t2.`topic_title`, t1.`post_id`, t1.`user`, LEFT(`post_text`, 256) AS `post_text`, DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `when`
			FROM " . TF_FORUM_POST . " AS t1
		INNER JOIN " . TF_FORUM_TOPIC . " AS t2
			ON (t1.`topic_id` = t2.`topic_id`)
		WHERE  1
		ORDER BY " . $order_by . "
		LIMIT $limit";		
		
		return $this->getAll ($sql);
	}
		
	function getPost ($post_id, $u)
	{
		global $gConf;
		
		$sql_add1 = "'-1' AS `voted`, 0 as `vote_user_point`, ";
		$sql_add2 = '';
		
		if ($u)
		{
			$sql_add1 = "(1 - ISNULL(t2.`post_id`)) AS `voted`, t2.`vote_point` as `vote_user_point`, ";
			$sql_add2 = " LEFT JOIN " . TF_FORUM_VOTE . " AS t2 ON ( t2.`user_name` = '$u' AND t1.`post_id` = t2.`post_id`) ";
		}
		
		$sql =  "SELECT `forum_id`, `topic_id`, t1.`post_id`, `user`, `post_text`, `votes`, $sql_add1 DATE_FORMAT(FROM_UNIXTIME(t1.`when`),'{$gConf['date_format']}') AS `when` FROM " . TF_FORUM_POST . " AS t1 $sql_add2 WHERE t1.`post_id` = '$post_id' LIMIT 1";		
		return $this->getRow ($sql);
	}

	
	function getUserPosts ($u)
	{
		//return $this->getOne ("SELECT COUNT(`post_id`) FROM " . TF_FORUM_POST . " WHERE `user` = '$u'");
		return (int)$this->getOne ("SELECT `posts` FROM " . TF_FORUM_USER_STAT . " WHERE `user` = '$u'");
	}

	function insertVote ($post_id, $u, $vote)
	{				
		$sql = "INSERT INTO " . TF_FORUM_VOTE . " SET `user_name` = '$u', `post_id` = '$post_id', `vote_point` = " . ($vote > 0 ? '1' : '-1') . ", `vote_when` = UNIX_TIMESTAMP()";		
		if (!$this->query($sql)) return false;
		
		$sql = "UPDATE " . TF_FORUM_POST . " SET `votes` = `votes` " . ($vote > 0 ? '+ 1' : '- 1') . " WHERE `post_id` = '$post_id' LIMIT 1";
		return $this->query($sql);
	}

	function getTopicByPostId ($post_id)
	{
		$sql = "SELECT `topic_id`, `forum_id` FROM " . TF_FORUM_POST . " WHERE `post_id` = '$post_id'";
		return $this->getRow ($sql);		
    }

	function report ($post_id, $u)
	{
		$sql = "INSERT INTO " . TF_FORUM_REPORT . " SET `user_name` = '$u', `post_id` = '$post_id'";
		if (!$this->query($sql)) return false;
		
		$sql = "UPDATE " . TF_FORUM_POST . " SET `reports` = `reports` + 1 WHERE `post_id` = '$post_id' LIMIT 1";
		return $this->query($sql);
	}

	function isFlagged ($topic_id, $u)
	{
		$sql = "SELECT `topic_id` FROM " . TF_FORUM_FLAG . " WHERE `user` = '$u' AND `topic_id` = '$topic_id'";
		return $this->getOne ($sql);
	}

	function flag ($topic_id, $u)
	{
		$sql = "INSERT INTO " . TF_FORUM_FLAG . " SET `user` = '$u', `topic_id` = '$topic_id', `when` = UNIX_TIMESTAMP()";
		return $this->query ($sql);
	}

	function unflag ($topic_id, $u)
	{
		$sql = "DELETE FROM " . TF_FORUM_FLAG . " WHERE `user` = '$u' AND `topic_id` = '$topic_id' LIMIT 1";
		return $this->query ($sql);
	}

	function updateUserActivity ($user)
	{
		global $gConf;

		$sql = "SELECT `act_current` FROM " . TF_FORUM_USER_ACT . " WHERE `user`  = '$user' LIMIT 1";
		$current = (int)$this->getOne ($sql);		

		if ((time() - $current) > $gConf['online'])
		{
			if ($current)
				$sql = "UPDATE " . TF_FORUM_USER_ACT . " SET `act_current`='" . time() . "', `act_last` = '$current' WHERE `user` = '$user'";
			else
				$sql = "INSERT INTO " . TF_FORUM_USER_ACT . " (`user`,`act_current`,`act_last`) VALUES ('$user', '" . time() . "', '$current')";
		}
		else
		{
			$sql = "UPDATE " . TF_FORUM_USER_ACT . " SET `act_current`='" . time() . "' WHERE `user` = '$user'";
		}

		return $this->query ($sql);
	}

	function updateUserLastActivity ($user)
	{
		global $gConf;

		$t = time();
		
		$sql = "UPDATE " . TF_FORUM_USER_ACT . " SET `act_current`='$t', `act_last` = '$t' WHERE `user` = '$user'";

		return $this->query ($sql);
	}

	function getUserLastActivity ($user)
	{
		$sql = "SELECT `act_last` FROM " . TF_FORUM_USER_ACT . " WHERE `user`  = '$user' LIMIT 1";
		return (int)$this->getOne ($sql);
	}

	function getUserLastOnlineTime ($user)
	{
		global $gConf;
		return $this->getOne ("SELECT DATE_FORMAT(FROM_UNIXTIME(`act_current`),'{$gConf['date_format']}') AS `act_current` FROM " . TF_FORUM_USER_ACT . " WHERE `user`  = '$user' LIMIT 1");
	}

	function userStatsInc ($user, $when)
	{
		$u = $this->getOne ("SELECT `user` FROM " . TF_FORUM_USER_STAT . " WHERE `user` = '$user'");
		if ($u)
		{
			$this->query ("UPDATE " . TF_FORUM_USER_STAT . " SET `posts` = `posts` + 1, `user_last_post` = '$when' WHERE `user` = '$user'");
		}
		else
		{
			$this->query ("INSERT INTO " . TF_FORUM_USER_STAT . " SET `posts` = 1, `user_last_post` = '$when', `user` = '$user'");
		}			
	}

	function userStatsDec ($user)
	{
		$u = $this->getOne ("SELECT `user` FROM " . TF_FORUM_USER_STAT . " WHERE `user` = '$user'");
		if (!$u) return;

		$when = $this->getOne ("SELECT `when` FROM " . TF_FORUM_POST . " WHERE `user` = '$user' ORDER BY `when` DESC LIMIT 1");

		return $this->query ("UPDATE " . TF_FORUM_USER_STAT . " SET `posts` = `posts` - 1, `user_last_post` = '$when' WHERE `user` = '$user'");
	}

	function getUserStat ($u)
	{
		global $gConf;

		return $this->getRow ("SELECT `posts`, DATE_FORMAT(FROM_UNIXTIME(`user_last_post`),'{$gConf['date_format']}') AS `user_last_post` FROM " . TF_FORUM_USER_STAT . " WHERE `user` = '$u'");
	}

	function getLivePosts ($c, $ts)
    {
        global $gConf;

		$where = '1';
		$order = 'DESC';
		if ($ts)
		{
			$where = "tp.`when` > $ts";
			$order = 'ASC';
		}

		$sql = "SELECT (UNIX_TIMESTAMP() - tp.`when`) AS `sec`, tp.`when` AS `ts`, `tp`.`user`, `tp`.`post_id`, LEFT(`tp`.`post_text`, {$gConf['live_tracker_desc_len']}) AS `post_text`, tt.`topic_id`, tt.`topic_uri`, `tt`.`topic_title`, tf.`forum_id`, tf.`forum_uri`, `tf`.`forum_title`, tc.`cat_id`, tc.`cat_uri`, `tc`.`cat_name` FROM " . TF_FORUM_POST . " AS tp INNER JOIN " . TF_FORUM_TOPIC . " AS tt USING(`topic_id`) INNER JOIN " . TF_FORUM . " AS tf ON (tf.`forum_id` = tp.`forum_id` AND `tt`.`forum_id` = tf.`forum_id`) INNER JOIN " . TF_FORUM_CAT . " AS tc USING(`cat_id`) WHERE $where ORDER BY tp.`when` $order LIMIT $c";

	
		return $this->getAll ($sql);
	}


	function getNewPostTs ($ts)
	{
		return $this->getOne("SELECT `when` FROM " . TF_FORUM_POST . " WHERE `when` > '$ts' ORDER BY `when` ASC LIMIT 1");
	}

// private functions

}





?>
