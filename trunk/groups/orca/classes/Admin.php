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


// admin functions

class Admin extends ThingPage
{
	/**
	 * constructor
	 */
	function Admin ()
	{
		global $f;
		$this->_admin = $f->isAdmin ();
	}

    function register ($LN)
    {        
        eval($GLOBALS['l']('ICAgICAgICBpZiAoISR0aGlzLT5fYWRtaW4pIHJldHVybiAnJzsNCg0KICAgICAgICAkaUNvZGUgPSAkdGhpcy0+Z2V0Q29kZSAoJHNNc2csICRMTik7DQoNCiAgICAgICAgJHNDbG9zZSA9ICcnOw0KICAgICAgICBpZiAoJGlDb2RlID09PSAwIHx8ICRpQ29kZSA9PT0gMTApDQogICAgICAgIHsNCiAgICAgICAgICAgIHNldENvbmZpZ1BhcmFtKCdsaWNlbnNlJywgJExOKTsNCiAgICAgICAgICAgICRzQ2xvc2UgPSAid2luZG93LnBhcmVudC5kb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnd2FybicpLnN0eWxlLmRpc3BsYXk9J25vbmUnIjsgICAgICAgICAgICAgICAgICAgICAgICANCiAgICAgICAgfSAgICAgICAgDQogICAgICAgIHNldENvbmZpZ1BhcmFtKCdkaXNhYmxlX2Jvb25leF9mb290ZXJzJywgMCA9PT0gJGlDb2RlID8gMSA6IDApOw0KICAgICAgICBpZiAoMCA9PT0gJGlDb2RlKSAkc01zZyA9ICJMaWNlbnNlIGhhcyBiZWVuIHN1Y2Nlc3NmdWxseSByZWdpc3RlcmVkLiBUaGFuayB5b3UuIjsNCiAgICAgICAgaWYgKDEwID09PSAkaUNvZGUpICRzTXNnID0gIkZyZWUgTGljZW5zZSBoYXMgYmVlbiBzdWNjZXNzZnVsbHkgcmVnaXN0ZXJlZC4gVGhhbmsgeW91LiI7DQogICAgICAgICRzID0gJzxzY3JpcHQgbGFuZ3VhZ2U9ImphdmFzY3JpcHQiIHR5cGU9InRleHQvamF2YXNjcmlwdCI+DQp3aW5kb3cucGFyZW50LmFsZXJ0KCInLiRzTXNnLiciKTsNCicuJHNDbG9zZS4nOw0KPC9zY3JpcHQ+Jzs='));
        return $s;
    }

    function getCode (&$sMsg, $LN = '')
    {
        eval($GLOBALS['l']('ICAgICAgICBnbG9iYWwgJGdDb25mOw0KDQogICAgICAgIGlmICghJExOKQ0KICAgICAgICAgICAgJExOID0gZ2V0Q29uZmlnUGFyYW0gKCdsaWNlbnNlJyk7DQogICAgICAgIA0KICAgICAgICBpZiAocHJlZ19tYXRjaCgnL2h0dHA6XC9cLyhbYS16QS1aMC05XC4tXSspXC8vJywgJGdDb25mWyd1cmwnXVsnYmFzZSddLCAkbSkpICRkID0gc3RyX3JlcGxhY2UoJ3d3dy4nLCcnLCRtWzFdKTsNCiAgICAgICAgJGlDb2RlID0gMTsNCiAgICAgICAgJHNNc2cgPSAnSW52YWxpZCBMaWNlbnNlJzsNCiAgICAgICAgaW5pX3NldCgnZGVmYXVsdF9zb2NrZXRfdGltZW91dCcsIDMpOw0KICAgICAgICAkcyA9IGZpbGVfZ2V0X2NvbnRlbnRzICgiaHR0cDovL2xpY2Vuc2UuYm9vbmV4LmNvbS9vcmNhLnBocD9MTj17JExOfSZkPXskZH0iKTsNCiAgICAgICAgaWYgKHByZWdfbWF0Y2ggKCcvPGNvZGU+KFxkKyk8XC9jb2RlPi4qPG1zZz4oLio/KTxcL21zZz4vJywgJHMsICRtKSkgDQogICAgICAgIHsNCiAgICAgICAgICAgICRpQ29kZSA9ICRtWzFdOw0KICAgICAgICAgICAgJHNNc2cgPSAkbVsyXTsNCiAgICAgICAgfQ=='));

        return (int)$iCode;
    }

	/**
	 * change category order
	 *	@param $cat_id	category id
	 *	@param $dir		direction (up|down)
	 *	@param return	xml (<ret>0</ret>|<ret>1</ret>)
	 */	
	function moveCat ($cat_id, $dir)
	{
		if (!$this->_admin) return '<ret>0</ret>';
		
		$db = new DbAdmin ();
		$cat_order = $db->getCatOrder ($cat_id);
		
		$a = $db->getCatsInOrder ($cat_order, $dir, 2);		
		
		$new_order = 0;
		
		if (2 == count ($a))
		{
			$new_order = $a[0]['cat_order']	> $a[1]['cat_order'] ? $a[1]['cat_order'] + ($a[0]['cat_order'] - $a[1]['cat_order'])/2 : $a[0]['cat_order'] + ($a[1]['cat_order'] - $a[0]['cat_order'])/2;
		}
		else if (1 == count ($a))
		{
			$new_order = $cat_order > $a[0]['cat_order'] ? $a[0]['cat_order']/2 : $a[0]['cat_order'] + CAT_ORDER_STEP;
		}
		
		if ($new_order)
		{
			$db->setNewOrder ($cat_id, $new_order);		
			return '<ret>1</ret>';	
		}
		
		return '<ret>0</ret>';
	}

	/**
	 * delete category 
	 *	@param $cat_id	category id
	 *	@param return	xml (<ret>0</ret>|<ret>1</ret>)
	 */		
	function deleteCategory ($cat_id)
	{
		if (!$this->_admin) return '<ret>0</ret>';		
		
		$db = new DbAdmin ();		
		return $db->deleteCategoryAll ((int)$cat_id) ? '<ret>1</ret>' : '<ret>0</ret>';
	}

	/**
	 * delete forum
	 *	@param $forum_id	forum id
	 *	@param return		xml (<ret>0</ret>|<ret>1</ret>)
	 */			
	function deleteForum ($forum_id)
	{
		if (!$this->_admin) return '<ret>0</ret>';
		
		$db = new DbAdmin ();		
		
		$cat_id = $db->getCatIdByForumId($forum_id);
		
		if ($db->deleteForumAll ((int)$forum_id))
			return '<ret>'.$cat_id.'</ret>';
		else
			return '<ret>0</ret>';
	}

	/**
	 * show edit category page
	 *	@param $cat_id		category id
	 *	@param return		category window xml 
	 */				
	function editCategory ($cat_id)
	{		
		$db = new DbAdmin ();
		$cat_name = $db->getCatname ((int)$cat_id);

		$cu = $this->getUrlsXml ();

		encode_post_text ($cat_name, 0);
		
		return <<<EOS
<root>
$cu
<cat cat_id="$cat_id">
	<cat_name>$cat_name</cat_name>	
</cat>
</root>
EOS;
	}

	/**
	 * save category information
	 *	@param $cat_id		category id
	 *	@param $cat_name	category name
	 *	@param return		xml (<ret>0</ret>|<ret>1</ret>)
	 */				
	function editCategorySubmit ($cat_id, $cat_name)
	{
		if (!$this->_admin) return '<ret>0</ret>';
		
		$cat_name = unicode_urldecode($cat_name);		
		prepare_to_db($cat_name, 0);

		// cat_name check 
		
		$db = new DbAdmin ();		
		if ($cat_id)
			return $db->editCategory ((int)$cat_id, $cat_name) ? '<ret>1</ret>' : '<ret>0</ret>';
		else
			return $db->insertCategory ($cat_name) ? '<ret>1</ret>' : '<ret>0</ret>';
	}	

	/**
	 * show edit forum page
	 *	@param $forum_id	forum id
	 *	@param $cat_id		category id
	 *	@param return		forum edit window xml 
	 */				
	function editForum ($forum_id, $cat_id)
	{		
		$db = new DbAdmin ();
		
		if ($forum_id)
		$a = $db->getForum ((int)$forum_id);
		else
			$a['cat_id'] = $cat_id;

		$cu = $this->getUrlsXml ();

		encode_post_text ($a['forum_title'], 0);
		encode_post_text ($a['forum_desc'], 0);
		
		return <<<OES
<root>
$cu
<forum forum_id="$forum_id">
	<cat_id>{$a['cat_id']}</cat_id>	
	<title>{$a['forum_title']}</title>	
	<desc>{$a['forum_desc']}</desc>	
	<type>{$a['forum_type']}</type>	
</forum>
</root>
OES;
	}

	/**
	 * save forum information
	 *	@param $cat_id		category id
	 *	@param $forum_id	forum id
	 *	@param $title		forum title
	 *	@param $desc		forum description
	 *	@param $type		forum type (public|private)
	 *	@param return		xml (<ret>0</ret>|<ret>1</ret>)
	 */					
	function editFormSubmit ($cat_id, $forum_id, $title, $desc, $type)	
	{
		if (!$this->_admin) return '<ret>0</ret>';
		
		$title = unicode_urldecode ($title);
		$desc = unicode_urldecode ($desc);

		prepare_to_db($title, 0);
		prepare_to_db($desc, 0);        

		$db = new DbAdmin ();		
		if ($forum_id > 0)
			return $db->editForum ((int)$forum_id, $title, $desc, $type) ? '<ret>1</ret>' : '<ret>0</ret>';
		else
			return $db->insertForum ((int)$cat_id, $title, $desc, $type) ? '<ret>1</ret>' : '<ret>0</ret>';

	}
		

	/**
	 * returns reported posts XML
	 */
	function getReportedPostsXML ()
	{
		global $gConf;
		global $f;
		
		$ui = array ();

		$fdb = new DbForum ();
		$adb = new DbAdmin ();		

		if (!$this->_admin) return "<root><posts></posts></root>";

		// check user permissions to delete or edit posts
		$gl_allow_edit = 1;
		$gl_allow_del = 1;

		$u = $f->_getLoginUser();
		
		$a = $adb->getReportedPosts($u);
		reset ($a);
		$p = '';
		while ( list (,$r) = each ($a) )
		{
			// acquire user info
			if (!$ui[$r['user']])
			{				
				$aa = $f->_getUserInfo ($r['user']);
				$ui[$r['user']] = array ('posts' => $fdb->getUserPosts($r['user']), 'avatar' => $aa['avatar']);
			}

			$allow_edit = $gl_allow_edit;
			$allow_del = $gl_allow_del;

			encode_post_text ($r['post_text']);
			
			$p .= <<<EOF
<post id="{$r['post_id']}"  force_show="1">
	<text>{$r['post_text']}</text>
	<when>{$r['when']}</when>
	<allow_edit>$allow_edit</allow_edit>
	<allow_del>$allow_del</allow_del>
	<points>{$r['votes']}</points>
	<vote_user_point>{$r['vote_user_point']}</vote_user_point>	
	<user posts="{$ui[$r['user']]['posts']}" name="{$r['user']}">
		<avatar>{$ui[$r['user']]['avatar']}</avatar>
	</user>
	<min_point>{$gConf['min_point']}</min_point>
</post>
EOF;
			$rr = $r;

		}

		$cu = $this->getUrlsXml ();
		return "<root>$cu<posts><topic><title>Reported Posts</title><id>0</id></topic><forum><id>0</id></forum>{$p}</posts></root>";

	}

	/**
	 * lock/unlcok topic
	 *	@param $topic_id	topic id
	 */ 
	function lock ($topic_id)
    {        
        if (!$topic_id || !$this->_admin) return '<ret>0</ret>';
		
		$db = new DbAdmin ();

		if ($db->isLocked ((int)$topic_id))
		{
			if (!$db->lock ((int)$topic_id))
				return '<ret>0</ret>';
			return '<ret>-1</ret>';
		}
		
		if (!$db->lock ((int)$topic_id))
			return '<ret>0</ret>';

		return '<ret>1</ret>';
    }

}


?>
