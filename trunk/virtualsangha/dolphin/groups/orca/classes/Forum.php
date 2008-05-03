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


// forum operations

class Forum extends ThingPage
{	

	/**
	 * constructor
	 */
	function Forum ()
	{

	}

	/**
	 * returns search results XML
	 * @param $text		search string
	 * @param $type		search type: msgs - messages | tlts - titles
	 * @param $forum	forum id to search within
	 * @param $u		search posts of this user only
	 * @param $disp		display: topics | posts
	 * @param $max_res	max number of results
	 */
	function getSearchResultsXML ($text, $type, $forum, $u, $disp, $max_res = 50)
	{		
		$fdb = new DbForum ();
		if (!$this->_checkUserPerm ('', '', 'search'))
		{
            return $this->_no_access();
		}

		switch ($type)
		{
			case 'msgs':
			case 'tlts':
				$a = $fdb->searchMessages ($text, $u, $forum, $type, ('posts' == $disp ? 1 : 0), $max_res);
				break;
			default:
				return '<error>[L[Wrong search type]]</error>';
		}

		$ws = preg_split("/\s+/", $text);	

		reset ($a);
		$s = '';		
		switch ($type)
		{
			case 'tlts':
				while ( list (,$r) = each ($a) )
				{
					// search hightlight
					
					
					reset($ws);					
					while (list (,$w) = each ($ws))
						if ($w)
							$r['topic_title'] = preg_replace ("/($w)/i", "<span style=\"background-color:yellow\">$w</span>", $r['topic_title']);
					

					encode_post_text($r['cat_name']);
					encode_post_text($r['forum_title']);
					encode_post_text($r['topic_title'], 0, 1);					
					
					$s .= <<<EOF
					<sr date="{$r['date']}" user="{$r['user']}">
						<c id="{$r['cat_id']}" uri="{$r['cat_uri']}">{$r['cat_name']}</c>
						<f id="{$r['forum_id']}" uri="{$r['forum_uri']}">{$r['forum_title']}</f>
						<t id="{$r['topic_id']}" uri="{$r['topic_uri']}">{$r['topic_title']}</t>
					</sr>
EOF;
				}
				break;
			case 'msgs':
				while ( list (,$r) = each ($a) )
                {
                    
					// search hightlight										
					reset($ws);
					while (list (,$w) = each ($ws))
					{
						if ($w)
                        {                            
							$ind = eregi( "([^>]*<)", $r['post_text'], $ind ); // html tags?
							if ($ind)
								$r['post_text'] = preg_replace("/($w)(?=[^>]*<)/i", "<span style=\"background-color:yellow\">$w</span>", "<div>{$r['post_text']}</div>");
							else
        						$r['post_text'] = preg_replace ("/($w)/i", "<span style=\"background-color:yellow\">$w</span>", $r['post_text']);
						}

                    }
                    
					encode_post_text ($r['post_text']);
					
					reset($ws);
					while (list (,$w) = each ($ws))
						$r['topic_title'] = preg_replace ("/($w)/i", "<span style=\"background-color:yellow\">$w</span>", $r['topic_title']);
					

					encode_post_text($r['cat_name']);
					encode_post_text($r['forum_title']);
					encode_post_text($r['topic_title'], 0, 1);					
					
					$s .= <<<EOF
					<sr date="{$r['date']}" user="{$r['user']}">
						<c id="{$r['cat_id']}" uri="{$r['cat_uri']}">{$r['cat_name']}</c>
						<f id="{$r['forum_id']}" uri="{$r['forum_uri']}">{$r['forum_title']}</f>
						<t id="{$r['topic_id']}" uri="{$r['topic_uri']}">{$r['topic_title']}</t>
						<p id="{$r['post_id']}">{$r['post_text']}</p>
					</sr>
EOF;
				}
				break;
		}

		$cu = $this->getUrlsXml ();
		encode_post_text($text, 0, 1);		
		return "<root>$cu<search><search_text>$text</search_text>$s</search></root>";
	}

	/**
	 * returns search  page XML
	 */
	function getSearchXML ()
	{
		if (!$this->_checkUserPerm ('', '', 'search'))
		{
            return $this->_no_access();
		}

		$fdb = new DbForum ();

		$a = $fdb->getCategs();
		reset ($a);
		$c = '';
		while ( list (,$r) = each ($a) )
		{			
			$c .= "<categ id=\"{$r['cat_id']}\" uri=\"{$r['cat_uri']}\">";
			encode_post_text($r['cat_name'], 0);
			$c .= "<title>{$r['cat_name']}</title>";
			$c .= '<forums>' . $this->getForumsShortXML ($r['cat_id'], 0) . '</forums>';
			$c .= "</categ>";
		}
		
		$s = "<categs>$c</categs>";

		$cu = $this->getUrlsXml ();
		return "<root>$cu<search>$s</search></root>";
	}

	/**
	 * returns new topic page XML
	 */
	function getNewTopicXML ($forum_uri)
	{
		$fdb = new DbForum ();

        $f = $fdb->getForumByUri ($forum_uri);        
        $forum_id = $f['forum_id'];        

		if (!$this->_checkUserPerm ('', $f['forum_type'], 'post', $forum_id))
        {
            return $this->_no_access();
		}        

		$sticky = 0;
		if ($this->_checkUserPerm ('', '', 'sticky', $forum_id))
		{
			$sticky = 1;
		}        

		encode_post_text ($f['forum_title'], 0);
		encode_post_text ($f['forum_desc'], 0);
		
		$x1 = <<<EOF
<forum>
    <id>{$f['forum_id']}</id>
    <uri>{$f['forum_uri']}</uri>
	<title>{$f['forum_title']}</title>
	<desc>{$f['forum_desc']}</desc>
	<type>{$f['forum_type']}</type>
</forum>
EOF;

		$cat = $fdb->getCat ($f['cat_id']);
		encode_post_text ($cat['cat_name'], 0);
		$x2 = <<<EOF
<cat>
    <id>{$f['cat_id']}</id>
    <uri>{$cat['cat_uri']}</uri>
	<title>{$cat['cat_name']}</title>
</cat>
EOF;

        $cu = $this->getUrlsXml ();

		return "<root>$cu<new_topic sticky=\"$sticky\">{$x2}{$x1}</new_topic></root>";
	}

	/**
	 * returns post reply page XML
	 */
	function getPostReplyXML ($forum_id, $topic_id)
	{
		$fdb = new DbForum ();

        $f = $fdb->getForum ($forum_id);

        $t = $fdb->getTopic ((int)$topic_id);

		if (!$this->_checkUserPerm ('', $f['forum_type'], 'post', (int)$forum_id) || $t['topic_locked'])
		{
            return $this->_no_access();
		}

		encode_post_text ($f['forum_title'], 0);
		encode_post_text ($f['forum_desc'], 0);
		
		$x1 = <<<EOF
<forum>
    <id>{$f['forum_id']}</id>
	<uri>{$f['forum_uri']}</uri>
	<title>{$f['forum_title']}</title>
	<desc>{$f['forum_desc']}</desc>
	<type>{$f['forum_type']}</type>
</forum>
EOF;
		$cu = $this->getUrlsXml ();
		return "<root>$cu<new_topic>$x1<topic><id>$topic_id</id></topic></new_topic></root>";
	}

	/**
	 * returns single post XML
	 * @param $post_id		post id
	 * @param $force_show	force show hidden post
	 */	
	function getHiddenPostXML ($post_id, $force_show)
	{
		global $gConf;
		
		$post_id = (int)$post_id;
		if (!$post_id) return false;
		
		$ui = array ();

		$fdb = new DbForum ();

        $t = $fdb->getTopicByPostId ($post_id);
        $topic_id = $t['topic_id'];

        $f = $fdb->getForum ($t['forum_id']);        
        $forum_id = $f['forum_id'];
		
		// check user permission to read this topic posts

        $forum_type = $f['forum_type'];

		if (!$this->_checkUserPerm ('', $forum_type, 'read', $forum_id))
		{
            return $this->_no_access();
		}

		// check user permissions to delete or edit posts
		$gl_allow_edit = 0;
		$gl_allow_del = 0;
		
		if ($this->_checkUserPerm ('', $forum_type, 'edit', $forum_id))
			$gl_allow_edit = 1;

		if ($this->_checkUserPerm ('', $forum_type, 'del', $forum_id))
			$gl_allow_del = 1;

		$u = $this->_getLoginUser();
		
		$r = $fdb->getPost($post_id, $u);
		
		// acquire user info
		if (!$ui[$r['user']])
		{				
			$aa = $this->_getUserInfo ($r['user']);
			$ui[$r['user']] = array ('posts' => (int)$fdb->getUserPosts($r['user']), 'avatar' => $aa['avatar'], 'url' => $aa['profile_url'], 'onclick' => $aa['profile_onclick']);
		}

		$allow_edit = $gl_allow_edit;
		$allow_del = $gl_allow_del;

		if (!$allow_edit && $r['user'] == $this->_getLoginUserName())
		{
			if ($this->_checkUserPerm ($r['user'], 'own', 'edit', $forum_id))
				$allow_edit = 1;
		}			

		if (!$allow_del && $r['user'] == $this->_getLoginUserName())
		{
			if ($this->_checkUserPerm ($r['user'], 'own', 'del', $forum_id))
				$allow_del = 1;
		}			

		$cu = $this->getUrlsXml ();

        encode_post_text ($r['post_text']);

		return <<<EOF
<root>
$cu
<forum>
    <id>{$f['forum_id']}</id>
    <uri>{$f['forum_uri']}</uri>
</forum>
<topic>
    <id>$topic_id</id>
    <uri>{$t['topic_uri']}</uri>
</topic>
<post id="{$r['post_id']}" force_show="$force_show">
	<text>{$r['post_text']}</text>
	<when>{$r['when']}</when>
	<allow_edit>$allow_edit</allow_edit>
	<allow_del>$allow_del</allow_del>
	<points>{$r['votes']}</points>
	<vote_user_point>{$r['vote_user_point']}</vote_user_point>	
	<user posts="{$ui[$r['user']]['posts']}" name="{$r['user']}">
		<avatar>{$ui[$r['user']]['avatar']}</avatar>
		<url>{$ui[$r['user']]['url']}</url>
		<onclick>{$ui[$r['user']]['onclick']}</onclick>
	</user>
	<min_point>{$gConf['min_point']}</min_point>
</post>
</root>
EOF;
	}
	
	/**
	 * returns topic posts XML
	 * @param $topic_id
	 * @param $wp			return whole page XML
	 */
	function getPostsXML ($topic_uri, $wp)
	{
		global $gConf;
		
		$ui = array ();

		$fdb = new DbForum ();

        $u = $this->_getLoginUser();
        $a = $fdb->getPostsByUri($topic_uri, $u);
        $topic_id = $a[0]['topic_id'];

		// check user permission to read this topic posts
        $f = $fdb->getForum ($a[0]['forum_id']);
        $forum_id = $f['forum_id'];
        $forum_type = $f['forum_type'];

		if (!$this->_checkUserPerm ($u, $forum_type, 'read', $forum_id))
		{
            return $this->_no_access($wp);
		}

		$canPost = (string)(int)$this->_checkUserPerm ($u, $forum_type, 'post', $forum_id);
        $perm = "<perm><can_post>$canPost</can_post></perm>";

		$this->setTrackTopic ($topic_id);

		// check user permissions to delete or edit posts
		$gl_allow_edit = 0;
		$gl_allow_del = 0;
		
		if ($this->_checkUserPerm ($u, $forum_type, 'edit', $forum_id))
			$gl_allow_edit = 1;

		if ($this->_checkUserPerm ($u, $forum_type, 'del', $forum_id))
			$gl_allow_del = 1;

				
		reset ($a);
		$p = '';
		while ( list (,$r) = each ($a) )
		{
			
			// acquire user info
			if (!$ui[$r['user']])
			{				
				$aa = $this->_getUserInfo ($r['user']);
				$ui[$r['user']] = array ('posts' => $fdb->getUserPosts($r['user']), 'avatar' => $aa['avatar'], 'url' => $aa['profile_url'], 'onclick' => $aa['profile_onclick']);
			}

			$allow_edit = $gl_allow_edit;
			$allow_del = $gl_allow_del;

			if (!$allow_edit && $r['user'] == $u)
			{
				if ($this->_checkUserPerm ($r['user'], 'own', 'edit', $forum_id))
					$allow_edit = 1;
			}			

			if (!$allow_del && $r['user'] == $u)
			{
				if ($this->_checkUserPerm ($r['user'], 'own', 'del', $forum_id))
					$allow_del = 1;
			}			
		
            encode_post_text ($r['post_text'], $wp, 1);			
            
			$p .= <<<EOF
<post id="{$r['post_id']}"  force_show="0">
	<text>{$r['post_text']}</text>
	<when>{$r['when']}</when>
	<allow_edit>$allow_edit</allow_edit>
	<allow_del>$allow_del</allow_del>
	<points>{$r['votes']}</points>
	<vote_user_point>{$r['vote_user_point']}</vote_user_point>	
	<user posts="{$ui[$r['user']]['posts']}" name="{$r['user']}">
		<avatar>{$ui[$r['user']]['avatar']}</avatar>
		<url>{$ui[$r['user']]['url']}</url>
		<onclick>{$ui[$r['user']]['onclick']}</onclick>
	</user>
	<min_point>{$gConf['min_point']}</min_point>
</post>
EOF;
			$rr = $r;
		}

		$t = $fdb->getTopic ($rr['topic_id']);


        $cat = $fdb->getCat ($f['cat_id']);
		encode_post_text ($cat['cat_name'], $wp);
		$x0 = <<<EOF
<cat>
    <id>{$cat['cat_id']}</id>
    <uri>{$cat['cat_uri']}</uri>
	<title>{$cat['cat_name']}</title>
</cat>
EOF;

		encode_post_text ($t['forum_title'], $wp);
		encode_post_text ($t['forum_desc'], $wp);
		$x1 = <<<EOF
<forum>
    <id>{$f['forum_id']}</id>
    <uri>{$f['forum_uri']}</uri>
	<title>{$t['forum_title']}</title>
	<desc>{$t['forum_desc']}</desc>
	<type>{$f['forum_type']}</type>
</forum>
EOF;
		encode_post_text ($t['topic_title'], $wp, 1);
		$x2 = <<<EOF
<topic>
    <id>{$t['topic_id']}</id>
	<uri>{$t['topic_uri']}</uri>
    <title>{$t['topic_title']}</title>
    <locked>{$t['topic_locked']}</locked>
</topic>
EOF;

		if ($wp)
		{
			$li = $this->_getLoginInfo ($u);
			return $this->addHeaderFooter ($li, "<posts>{$perm}{$x0}{$x1}{$x2}{$p}</posts>");
		}
		else
		{
            $cu = $this->getUrlsXml ();
            $li = $this->_getLoginInfo ($u);
			return "<root><logininfo>" . array2xml($li) . "</logininfo>$cu<posts>{$perm}{$x0}{$x1}{$x2}{$p}</posts></root>";
		}
	}


	/**
	 * returns my threads topics XML
	 * @param $wp			return whole page XML
	 */
	function getMyThreadsXML ($wp)
	{
		global $gConf;

		$user = $this->getLoginUser();

		$fdb = new DbForum ();

		$f = $fdb->getForum ($forum_id);

		if (!$user)
		{
            return $this->_no_access($wp);
		}

		$x1 = <<<EOF
<forum>
	<title><![CDATA[[L[My Topics]]]]></title>
	<desc><![CDATA[[L[Topics you participate in]]]]></desc>
</forum>
EOF;

		$x2 = '';
		
		$user_last_act = (int)$fdb->getUserLastActivity ($user);

		$a = $fdb->getMyThreadsTopics($user);
		reset ($a);
		$t = '';
		while ( list (,$r) = each ($a) )
		{
				$lp = $fdb->getTopicPost($r['topic_id'], 'last');
				$fp = $fdb->getTopicPost($r['topic_id'], 'first');
				
				$td = $fdb->getTopicDesc ($r['topic_id']);
				$this->_buld_topic_desc ($td);

				if (!$user)
					$new_topic = 0;
				else
					$new_topic = $this->isNewTopic ($r['topic_id'],  $r['last_post_when'], $user_last_act) ? 1 : 0;

				encode_post_text ($r['topic_title'], $wp, 1);				
									
				$t .= <<<EOF
<topic id="{$r['topic_id']}" new="$new_topic" lpt="{$r['last_post_when']}" lut="{$user_last_act}">
    <uri>{$r['topic_uri']}</uri>
	<title>{$r['topic_title']}</title>
	<desc>{$td}</desc>
	<count>{$r['count_posts']}</count>
	<last_u>{$lp['user']}</last_u>
	<last_d>{$lp['when']}</last_d>
	<first_u>{$fp['user']}</first_u>
	<first_d>{$fp['when']}</first_d>
</topic>
EOF;
		}

		$p = '';
		$num = $fdb->getTopicsNum($forum_id);
		for ($i = 0 ; $i < $num ; $i += $gConf['topics_per_page'])
			$p .= '<p c="' . (($start >= $i && $start < ($i + $gConf['topics_per_page'])) ? 1 : 0 ). '" start="' . $i . '">' . ($i/$gConf['topics_per_page'] + 1) . '</p>';
	

		if ($wp)
		{
			$li = $this->_getLoginInfo ();
			return $this->addHeaderFooter ($li, "<topics><pages>$p</pages>{$x2}{$x1}{$t}</topics>");
		}
		else
		{
			$cu = $this->getUrlsXml ();
			return "<root>$cu<topics><pages>$p</pages>{$x2}{$x1}{$t}</topics></root>";
		}
	}
	

	/**
	 * returns flagged topics XML
	 * @param $wp			return whole page XML
	 */
	function getMyFlagsXML ($wp)
	{
		global $gConf;

		$user = $this->getLoginUser();

		$fdb = new DbForum ();

		$f = $fdb->getForum ($forum_id);

		if (!$user)
		{
            return $this->_no_access($wp);
		}

		$x1 = <<<EOF
<forum>
	<title><![CDATA[[L[Flagged topics]]]]></title>
	<desc><![CDATA[[L[Topics you have flagged]]]]></desc>
</forum>
EOF;

		$x2 = '';
		
		$user_last_act = (int)$fdb->getUserLastActivity ($user);

		$a = $fdb->getMyFlaggedTopics($user);
		reset ($a);
		$t = '';
		while ( list (,$r) = each ($a) )
		{
				$lp = $fdb->getTopicPost($r['topic_id'], 'last');
				$fp = $fdb->getTopicPost($r['topic_id'], 'first');
				
				$td = $fdb->getTopicDesc ($r['topic_id']);
				$this->_buld_topic_desc ($td);
								
				if (!$user)
					$new_topic = 0;
				else
					$new_topic = $this->isNewTopic ($r['topic_id'],  $r['last_post_when'], $user_last_act) ? 1 : 0;

				encode_post_text ($r['topic_title'], $wp, 1);				
									
				$t .= <<<EOF
<topic id="{$r['topic_id']}" new="$new_topic" lpt="{$r['last_post_when']}" lut="{$user_last_act}">
    <uri>{$r['topic_title']}</uri>
	<title>{$r['topic_title']}</title>
	<desc>{$td}</desc>
	<count>{$r['count_posts']}</count>
	<last_u>{$lp['user']}</last_u>
	<last_d>{$lp['when']}</last_d>
	<first_u>{$fp['user']}</first_u>
	<first_d>{$fp['when']}</first_d>
</topic>
EOF;
		}

		$p = '';
		$num = $fdb->getTopicsNum($forum_id);
		for ($i = 0 ; $i < $num ; $i += $gConf['topics_per_page'])
			$p .= '<p c="' . (($start >= $i && $start < ($i + $gConf['topics_per_page'])) ? 1 : 0 ). '" start="' . $i . '">' . ($i/$gConf['topics_per_page'] + 1) . '</p>';
	

		if ($wp)
		{
			$li = $this->_getLoginInfo ();
			return $this->addHeaderFooter ($li, "<topics><pages>$p</pages>{$x2}{$x1}{$t}</topics>");
		}
		else
		{
			$cu = $this->getUrlsXml ();
			return "<root>$cu<topics><pages>$p</pages>{$x2}{$x1}{$t}</topics></root>";
		}
	}



	/**
	 * returns forum topics XML
	 * @param $forum_id		forum id
	 * @param $wp			return whole page XML
	 * @param $start		record to start with
	 */
	function getTopicsXML ($forum_uri, $wp, $start = 0)
	{
		global $gConf;

		$fdb = new DbForum ();

        $f = $fdb->getForumByUri ($forum_uri);
        $forum_id = $f['forum_id'];

		$user = $this->getLoginUser();

		if (!$this->_checkUserPerm ($user, $f['forum_type'], 'read', $forum_id))
        {
            return $this->_no_access($wp);
		}

		$canPost = (string)(int)$this->_checkUserPerm ($user, $f['forum_type'], 'post', $forum_id);		
		$perm = "<perm><can_post>$canPost</can_post></perm>";

		encode_post_text ($f['forum_title'], $wp);
		encode_post_text ($f['forum_desc'], $wp);
		
		$x1 = <<<EOF
<forum>
    <id>{$f['forum_id']}</id>
	<uri>{$f['forum_uri']}</uri>
	<title>{$f['forum_title']}</title>
	<desc>{$f['forum_desc']}</desc>
	<type>{$f['forum_type']}</type>
</forum>
EOF;

		$cat = $fdb->getCat ($f['cat_id']);
		encode_post_text ($cat['cat_name'], $wp);
		$x2 = <<<EOF
<cat>
    <id>{$cat['cat_id']}</id>
    <uri>{$cat['cat_uri']}</uri>
	<title>{$cat['cat_name']}</title>
</cat>
EOF;

		$user_last_act = (int)$fdb->getUserLastActivity ($user);

		$a = $fdb->getTopics($forum_id, $start);
		reset ($a);
		$t = '';
		while ( list (,$r) = each ($a) )
		{
				$td = $fdb->getTopicDesc ($r['topic_id']);
				$this->_buld_topic_desc ($td);

				if (!$user)
					$new_topic = 0;
				else
					$new_topic = $this->isNewTopic ($r['topic_id'],  $r['last_post_when'], $user_last_act) ? 1 : 0;

									
				encode_post_text ($r['topic_title'], $wp, 1);				
				
				$t .= <<<EOF
<topic id="{$r['topic_id']}" new="$new_topic" lpt="{$r['last_post_when']}" lut="{$user_last_act}" sticky="{$r['topic_sticky']}" locked="{$r['topic_locked']}">
	<uri>{$r['topic_uri']}</uri>
	<title>{$r['topic_title']}</title>
	<desc>{$td}</desc>
	<count>{$r['count_posts']}</count>
	<last_u>{$r['last_post_user']}</last_u>
	<last_d>{$r['last_when']}</last_d>
	<first_u>{$r['first_post_user']}</first_u>
	<first_d>{$r['first_when']}</first_d>
</topic>
EOF;
		}

		$p = '';
		$num = $fdb->getTopicsNum($forum_id);
		for ($i = 0 ; $i < $num ; $i += $gConf['topics_per_page'])
			$p .= '<p c="' . (($start >= $i && $start < ($i + $gConf['topics_per_page'])) ? 1 : 0 ). '" start="' . $i . '">' . ($i/$gConf['topics_per_page'] + 1) . '</p>';
	

		if ($wp)
		{
			$li = $this->_getLoginInfo ($user);
			return $this->addHeaderFooter ($li, "<topics><pages>$p</pages>{$perm}{$x2}{$x1}{$t}</topics>");
		}
		else
		{
			$cu = $this->getUrlsXml ();
			return "<root>$cu<topics><pages>$p</pages>{$perm}{$x2}{$x1}{$t}</topics></root>";
		}
	}

	/**
	 * returns array with viewed topics
	 */ 
	function getTrackTopics ()
	{
		$a = unserialize($_COOKIE['track_topics']);
		if (!is_array($a)) return array ();
		return $a;
	}

	/**
	 * mark topic as viewed
	 */ 
	function setTrackTopic ($topic_id)
	{
		$a = unserialize($_COOKIE['track_topics']);
		if (!is_array($a)) $a = array ();
		$a[$topic_id] = time();
		setcookie ('track_topics', serialize($a));
	}

	/**
	 * detect new topic by last topic update time and user activity and cookies
	 *
	 */ 
	function isNewTopic ($topic_id, $topic_last_time, $user_last_time)
	{
		$a = $this->getTrackTopics ();		
		
		if ($a[$topic_id] && $topic_last_time > $a[$topic_id]) 
			return 1;
		else if ($a[$topic_id])
			return 0;

		if (!$user_last_time) return 1;

		if ($topic_last_time > $user_last_time) return 1;

		return 0;
	}

	/**
	 * returns forums XML
	 */
	function getForumsShortXML ($cat, $root)
	{
		$fdb = new DbForum ();
		if ($root) 
			$c = '<forums>';
		else
			$c = '';
		$aa = $fdb->getForums ($cat);
		reset ($aa);
		while ( list (,$rr) = each ($aa) )
		{
			encode_post_text($rr['forum_title'], 0);
			
			$c .= <<<EOF
<forum id="{$rr['forum_id']}">
    <uri>{$rr['forum_uri']}</uri>
	<title>{$rr['forum_title']}</title>
	<type>{$rr['forum_type']}</type>
</forum>

EOF;
		}
		if ($root)
			return $c."</forums>\n";
		else
			return $c;
	}

	/**
	 * returns forums XML
	 */
	function getForumsXML ($cat, $root)
	{
		$fdb = new DbForum ();
		if ($root) 
			$c = '<forums>';
		else
			$c = '';
        $aa = $fdb->getForumsByCatUri ($cat);

		reset ($aa);
		while ( list (,$rr) = each ($aa) )
		{
			encode_post_text ($rr['forum_title'], $root);
			encode_post_text ($rr['forum_desc'], $root);
			
			$c .= <<<EOF
<forum id="{$rr['forum_id']}" new="0">
    <uri>{$rr['forum_uri']}</uri>
	<title>{$rr['forum_title']}</title>
	<desc>{$rr['forum_desc']}</desc>
	<type>{$rr['forum_type']}</type>
	<posts>{$rr['forum_posts']}</posts>
	<topics>{$rr['forum_topics']}</topics>
	<last>{$rr['forum_last']}</last>
</forum>

EOF;
		}
		
		if ($root)
		{
			$cu = $this->getUrlsXml ();
			return '<root>' . $cu . $c . "</forums></root>\n";
		}
		else
		{
			return $c;
		}
	}



	/**
	 * returns page XML
	 */
	function getPageXML ($first_load = 1, &$p)
	{
		global $gConf;

		$fdb = new DbForum ();

		$a = $fdb->getCategs();
		reset ($a);
        $c = '';
		while ( list (,$r) = each ($a) )
		{
			$icon_url  = $r['cat_icon'] ? $gConf['url']['icon'] . $r['cat_icon'] : '';
			$c .= "<categ id=\"{$r['cat_id']}\" uri=\"{$r['cat_uri']}\" icon=\"$icon_url\">";
			encode_post_text ($r['cat_name'], 1);
            $c .= "<title>{$r['cat_name']}</title>";

            if (( isset($p['cat']) && $p['cat'] == $r['cat_uri'] ) /*|| 1 == $r['cat_id'] */)
            {
				$c .= '<forums>'.$this->getForumsXML ($r['cat_uri'], 0) . '</forums>';
			}
			$c .= "</categ>";
		}
		
		$s = "<categs>$c</categs>";

		if ($first_load && isset($p['action']) && 'goto' == $p['action'] && isset($p['forum_id']))
		{
			$s .= "<onload>f.selectForum('" . $p['forum_id'] . "', 0)</onload>";
		}
		
		if ($first_load && isset($p['action']) && 'goto' == $p['action'] && isset($p['topic_id']))
		{
			$s .= "<onload>f.selectTopic('" . $p['topic_id'] . "')</onload>";
		}

		// live tracker
		$lt = "<live_tracker>" . $this->getLivePostsXML() . "</live_tracker>";
		
		$li = $this->_getLoginInfo ();

        eval($GLOBALS['l']('ICAgICAgICBpZiAoJGxpWydhZG1pbiddKQ0KICAgICAgICB7DQogICAgICAgICAgICAkb0FkbSA9IG5ldyBBZG1pbigpOw0KICAgICAgICAgICAgJGlDb2RlID0gJG9BZG0tPmdldENvZGUgKCRzTXNnKTsNCiAgICAgICAgICAgIGlmICgwICE9PSAkaUNvZGUgJiYgMTAgIT09ICRpQ29kZSkNCiAgICAgICAgICAgICAgICAkcyAuPSAnPGludmFsaWRfbGljZW5zZSAvPic7DQogICAgICAgICAgICBzZXRDb25maWdQYXJhbSgnZGlzYWJsZV9ib29uZXhfZm9vdGVycycsIDAgPT09ICRpQ29kZSA/IDEgOiAwKTsNCiAgICAgICAgfQ=='));

		if ($first_load)
		{
			return $this->addHeaderFooter ($li, $s.$lt);
		}
		else
		{
			$cu = $this->getUrlsXml ();
			return "<root>$cu<logininfo>".array2xml($li)."</logininfo><page>{$s}{$lt}</page></root>";
		}
	}

	function getLivePostsXML ($count = 10, $ts = 0)
    {
        return ''; //turned off for groups

		global $gConf;

		$ret = '';

		$fdb = new DbForum ();
		$a = $fdb->getLivePosts ($count, $ts);
        reset ($a);
        $ui = array ();
		while (list(,$r) = each ($a))
        {
            // acquire user info
            if (!isset($ui[$r['user']]))
            {
                $aa = $this->_getUserInfo ($r['user']);
                $ui[$r['user']] = array ('avatar' => $aa['avatar'], 'url' => $aa['profile_url'], 'onclick' => $aa['profile_onclick']);
            }

            $this->_buld_topic_desc ($r['post_text']);

            encode_post_text($r['topic_title'], 0, 1);
            encode_post_text($r['forum_title'], 0);
            encode_post_text($r['cat_name'], 0);                        
            
            $r['when'] = $this->_format_when ($r['sec']);

			$ret .= <<<EOF
<post id="{$r['post_id']}" ts="{$r['ts']}">
	<text>{$r['post_text']}</text>
	<user>{$r['user']}</user>
    <date>{$r['when']}</date>

    <avatar>{$ui[$r['user']]['avatar']}</avatar>
    <profile>{$ui[$r['user']]['url']}</profile>
    <onclick>{$ui[$r['user']]['onclick']}</onclick>

	<topic id="{$r['topic_id']}" uri="{$r['topic_uri']}">{$r['topic_title']}</topic>
	<forum id="{$r['forum_id']}" uri="{$r['forum_uri']}">{$r['forum_title']}</forum>
	<cat id="{$r['cat_id']}" uri="{$r['cat_uri']}">{$r['cat_name']}</cat>
	<base>{$gConf['url']['base']}</base>
</post>
EOF;
		}

		return $ret;
	}

	/**
	 * check if new posts are available
	 *	@param	$ts		timestamp of last post
	 */ 
	function isNewPost ($ts)
	{
		$db = new DbForum ();		
		return '<ret>' . (int)$db->getNewPostTs ($ts) . '</ret>';
	}


	/**
	 * post reply
	 * @param $p	_post array
	 */
	function postReplyXML (&$p)
	{

		$fdb = new DbForum ();

		$f = $fdb->getForum ((int)$p['forum_id']);

        $t = $fdb->getTopic ((int)$p['topic_id']);

		if (!$this->_checkUserPerm ('', $f['forum_type'], 'post', (int)$p['forum_id']) || $t['topic_locked']) 
		{
			return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.accessDenied();
</script>
</body>
</html>
EOF;
		}		


		// post mesage here

		$p['forum_id'] = (int)$p['forum_id'];
		$p['topic_id'] = (int)$p['topic_id'];

		$user = $this->_getLoginUserName ();

		prepare_to_db($p['topic_text'], 1);

		$fdb->postReply ($p['forum_id'], $p['topic_id'], $p['topic_text'], $user);

        $t = $fdb->getTopic($p['topic_id']);

		return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.replySuccess('{$f['forum_uri']}', '{$t['topic_uri']}');
</script>
</body>
</html>
EOF;


	}

	/**
	 * xml for edit post 
	 * @param $post_id		post id
	 * @param $topic_id		topic id
	 */
	function editPostXml ($post_id, $topic_id)
	{
        $cu = $this->getUrlsXml ();
        if (!$topic_id && $post_id)
        {
            $db = new DbForum ();		
            $a = $db->getPostIds ($post_id);
            $topic_id = $a['topic_id'];
        }
		return <<<EOS
<root>
	$cu
	<edit_post>
		<post_id>$post_id</post_id>
		<topic_id>$topic_id</topic_id>
	</edit_post>
</root>
EOS;
	}

	/**
	 * edit post
	 * @param $post_id		post id
	 * @param $topic_id		topic id
	 * @param $text			new post text
	 */
	function editPost ($post_id, $topic_id, $text)
	{
		$no_access = true;

		$fdb = new DbForum ();

        //$f = $fdb->getForumByPostId ($post_id);
        $t = $fdb->getTopic ($topic_id);

		if ($this->_checkUserPerm ('', $t['forum_type'], 'edit', $t['forum_id'])) 
			$no_access = false;
		if ($no_access && $fdb->getPostUser((int)$post_id) == $this->_getLoginUserName())
			if ($this->_checkUserPerm ('', 'own', 'edit', $t['forum_id'])) 
				$no_access = false;

		if ($no_access)
		{
			return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.accessDenied();
</script>
</body>
</html>
EOF;
		}	

		// edit post here		
		prepare_to_db($text, 1);
		
		$fdb->editPost ($post_id, $text);

		return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.editSuccess('{$t['topic_uri']}');
</script>
</body>
</html>
EOF;

	}

	/**
	 * delete post
	 * @param $post_id		post id
	 * @param $topic_id		topic id
	 * @param $forum_id		forum id 
	 */
	function deletePostXML ($post_id, $topic_id, $forum_id)
	{
		$no_access = true;

		$fdb = new DbForum ();

        $f = $fdb->getForumByPostId ($post_id);

		if ($this->_checkUserPerm ('', $f['forum_type'], 'del', $f['forum_id'])) 
			$no_access = false;
		if ($no_access && $fdb->getPostUser((int)$post_id) == $this->_getLoginUserName())
			if ($this->_checkUserPerm ('', 'own', 'del', $f['forum_id'])) 
				$no_access = false;

		if ($no_access)
		{
			return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.accessDenied();
</script>
</body>
</html>
EOF;
		}	
	
		// delete post here

		$fdb->deletePost ($post_id);

		$exists = $fdb->getTopic ($topic_id) ? 1 : 0;

		return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">
	window.parent.document.f.deleteSuccess('{$f['forum_id']}', '{$topic_id}', {$exists});
</script>
</body>
</html>
EOF;

	}

	/**
	 * post new topic
	 * @param $p	_post array
	 */
	function postNewTopicXML ($p)
	{
		$fdb = new DbForum ();

		$f = $fdb->getForum ((int)$p['forum_id']);

		if (!$this->_checkUserPerm ('', $f['forum_type'], 'post', (int)$p['forum_id'])) 
		{
			return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">

	if (window.parent.document.getElementById('tinyEditor'))
		window.parent.tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor');

	window.parent.document.f.accessDenied();

</script>
</body>
</html>
EOF;
		}		

		if ($p['topic_sticky'] == 'on' && !$this->_checkUserPerm ('', '', 'sticky', (int)$p['forum_id'])) 
		{
			return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">

	if (window.parent.document.getElementById('tinyEditor'))
		window.parent.tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor');

	window.parent.document.f.accessDenied();

</script>
</body>
</html>
EOF;
		}		


		// post mesage here

		$user = $this->_getLoginUserName ();
		
		prepare_to_db($p['topic_subject'], 0);
		prepare_to_db($p['topic_text'], 1);

        $topic_uri = $this->uriGenerate ($p['topic_subject'], TF_FORUM_TOPIC, 'topic_uri');
		$fdb->newTopic ((int)$p['forum_id'], $p['topic_subject'], $p['topic_text'], ($p['topic_sticky'] == 'on'), $user, $topic_uri);

		return <<<EOF
<html>
<body>
<script language="javascript" type="text/javascript">

	if (window.parent.document.getElementById('tinyEditor'))
		window.parent.tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor');

	window.parent.document.f.postSuccess('{$f['forum_uri']}');

</script>
</body>
</html>
EOF;

	}

	function isAdmin ()
	{
        $a = $this->_getUserInfo ($this->getLoginUser());
        return $a['admin'];
	}
	
	/**
	 * returns logged in user
	 */
	function getLoginUser ()
	{
		return $this->_getLoginUser();
	}

	/**
	 * updates current user last activity time
	 */ 
	function updateCurrentUserActivity ()
	{
		$u = $this->getLoginUser ();
		if (!$u) return;		

		$db = new DbForum ();
		$db->updateUserActivity ($u);
	}

	function logout ()
	{
		$u = $this->getLoginUser ();
		if (!$u) return '<ret>0</ret>';

		setcookie('orca_pwd', 'orca_pwd', time() - 86400);
		setcookie('orca_user', 'orca_user', time() - 86400);
		setcookie('track_topics', 'track_topics', time() - 86400);

		$db = new DbForum ();
		$db->updateUserLastActivity ($u);

		return '<ret>1</ret>';
	}

	/**
	 * post voting
	 *	@param $post_id	post id
	 *	@param $vote	vote (1|-1)
	 */ 
	function votePost ($post_id, $vote)
	{
		$u = $this->getLoginUser ();
		if (!$u) return '<ret>0</ret>';
		
		$db = new DbForum ();
		
		if (!$db->insertVote ((int)$post_id, $u, $vote))
			return '<ret>0</ret>';
			
		return '<ret>1</ret>';
	}

	/**
	 * report post
	 *	@param $post_id	post id
	 */ 
	function report ($post_id)
	{
		if (!$post_id) return '<ret>0</ret>';

		$u = $this->getLoginUser ();
		if (!$u) return '<ret>0</ret>';
		
		$db = new DbForum ();
		
		if (!$db->report ((int)$post_id, $u))
			return '<ret>0</ret>';
			
		return '<ret>1</ret>';
	}

	/**
	 * flag/unflag topic
	 *	@param $topic_id	topic id
	 */ 
	function flag ($topic_id)
	{
		if (!$topic_id) return '<ret>0</ret>';

		$u = $this->getLoginUser ();
		if (!$u) return '<ret>0</ret>';		
		
		$db = new DbForum ();

		if ($db->isFlagged ((int)$topic_id, $u))
		{
			if (!$db->unflag ((int)$topic_id, $u))
				return '<ret>0</ret>';
			return '<ret>-1</ret>';
		}
		
		if (!$db->flag ((int)$topic_id, $u))
			return '<ret>0</ret>';

		return '<ret>1</ret>';
	}

	/**
	 * forum rss feed, 10 latest topics in the forum
	 *	@param $forum_id	forum id	
	 */ 
	function getRssForum ($forum_uri)
	{
		global $gConf;

		$gConf['topics_per_page'] = 10;
		$gConf['date_format'] = '%a, %e %b %Y %k:%i:%s GMT';

		$fdb = new DbForum ();

        $f = $fdb->getForumByUri ($forum_uri);
        $forum_id = $f['forum_id'];

		if (!$f) exit;

		$a = $fdb->getTopics ($forum_id, 0);

		reset ($a);
		$items = '';
		$lastBuildDate = '';
		while ( list (,$r) = each ($a) )
		{
			$lp = $fdb->getTopicPost($r['topic_id'], 'last');
			$td = strip_tags($fdb->getTopicDesc($r['topic_id']));

			if (!$lastBuildDate)
				$lastBuildDate = $lp['when'];

			$items .= "
			<item>
				<title>{$r['topic_title']}</title>
				<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</link>
				<description>$td</description>
				<pubDate>{$lp['when']}</pubDate>
				<guid>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</guid>
			</item>";
		}		
			
		return "
<rss version=\"2.0\">
	<channel>
		<title>{$f['forum_title']}</title>
		<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['forum'], $f['forum_uri'], 0) . "</link>
		<description>{$f['forum_desc']}</description>
		<lastBuildDate>$lastBuildDate</lastBuildDate>	
		$items
	</channel>
</rss>";
	}



	/**
	 * topic rss feed, 10 latest posts in the topic
	 *	@param $forum_id	forum id	
	 */ 
	function getRssTopic ($topic_uri)
	{
		global $gConf;
		
		$gConf['topics_per_page'] = 10;
		$gConf['date_format'] = '%a, %e %b %Y %k:%i:%s GMT';

		$fdb = new DbForum ();

        $t = $fdb->getTopicByUri($topic_uri);
        $topic_id = (int)$t['topic_id'];

		if (!$t) exit;

		$a = $fdb->getPosts ($topic_id, 0);

		reset ($a);
		$items = '';
		$lastBuildDate = '';
		while ( list (,$r) = each ($a) )
		{
			$lp = $fdb->getTopicPost($r['topic_id'], 'last');
            $td = strip_tags(substr($r['post_text'], 0, 256));
            if (strlen($td) == 256) $td .= '[...]';
			$tt = substr($td, 0, 32);
			
				$lastBuildDate = $lp['when'];

			$items .= "
			<item>
				<title>{$tt}</title>
				<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $t['topic_uri']) . "</link>
				<description>$td</description>
				<pubDate>{$lp['when']}</pubDate>
				<guid>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $t['topic_uri']) . "#{$r['post_id']}</guid>
			</item>";
		}		
			
		return "
<rss version=\"2.0\">
	<channel>
		<title>{$t['topic_title']}</title>
		<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $t['topic_uri']) . "</link>
		<description>{$t['topic_title']}</description>
		<lastBuildDate>$lastBuildDate</lastBuildDate>	
		$items
	</channel>
</rss>";
	}
		
	/**
	 * user posts rss feed, 10 latest posts of specified user
     *	@param $user	username 
     *  @param $sort	sort : rnd | top | latest - default
	 */ 
	function getRssUser ($user, $sort)
	{
		global $gConf;

		$gConf['topics_per_page'] = 10;
		$gConf['date_format'] = '%a, %e %b %Y %k:%i:%s GMT';

		$fdb = new DbForum ();		
		
		$a = $fdb->getUserPostsList($user, $sort, $gConf['topics_per_page']);

		reset ($a);
		$items = '';
		$lastBuildDate = '';
		while ( list (,$r) = each ($a) )
		{
			if (!$lastBuildDate)
				$lastBuildDate = $r['when'];

			$td = strip_tags($r['post_text']);
            if (strlen($td) == 256) $td .= '[...]';
			
			$items .= "
			<item>
				<title><![CDATA[{$r['topic_title']}]]></title>
				<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</link>
				<description><![CDATA[{$r['user']}: {$td}]]></description>
				<pubDate>{$r['when']}</pubDate>
				<guid>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</guid>
			</item>";
		}		
		
        if ($sort == 'rnd' || $sort == 'top') $lastBuildDate = '';

        $sTitle = sprintf("[L[%s's forum posts]]", $user);
		return "
<rss version=\"2.0\">
	<channel>
		<title>$sTitle</title>
		<link>{$gConf['url']['base']}</link>
		<description>$sTitle</description>
		<lastBuildDate>$lastBuildDate</lastBuildDate>	
		$items
	</channel>
</rss>";
    }

	/**
	 * all posts rss feed, 10 latest posts
     *	@param $user	username 
     *  @param $sort	sort : rnd | top | latest - default
	 */ 
	function getRssAll ($sort)
	{
		global $gConf;

		$gConf['topics_per_page'] = 10;
		$gConf['date_format'] = '%a, %e %b %Y %k:%i:%s GMT';

		$fdb = new DbForum ();		
		
		$a = $fdb->getAllPostsList($sort, $gConf['topics_per_page']);

		reset ($a);
		$items = '';
		$lastBuildDate = '';
		while ( list (,$r) = each ($a) )
		{
			if (!$lastBuildDate)
				$lastBuildDate = $r['when'];

			$td = strip_tags($r['post_text']);
            if (strlen($td) == 256) $td .= '[...]';
			
			$items .= "
			<item>
				<title><![CDATA[{$r['topic_title']}]]></title>
				<link>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</link>
				<description><![CDATA[{$r['user']}: {$td}]]></description>
				<pubDate>{$r['when']}</pubDate>
				<guid>" . $gConf['url']['base'] . sprintf($gConf['rewrite']['topic'], $r['topic_uri']) . "</guid>
			</item>";
		}		
		
        if ($sort == 'rnd' || $sort == 'top') $lastBuildDate = '';

		return <<<EOF
<rss version="2.0">
	<channel>
		<title>[L[Forum Posts]]</title>
		<link>{$gConf['url']['base']}</link>
		<description>[L[Forum Posts]]</description>
		<lastBuildDate>$lastBuildDate</lastBuildDate>
		$items
	</channel>
</rss>
EOF;
    }
        


	/** 
	 * profile xml
	 * @param	$u	username
	 * @param	$wp	return whole page XML
	 */ 
	function showProfile ($u, $wp)
	{
		$fdb = new DbForum ();

		$a = $this->_getUserInfo ($u);
		$as = $fdb->getUserStat ($u);

		$a['username'] = $u;
		$a['posts'] = (int)$as['posts'];
		$a['user_last_post'] = $as['user_last_post'];
		$a['last_online'] = $fdb->getUserLastOnlineTime ($u);

		$p = array2xml ($a);

		if ($wp)
		{
			$li = $this->_getLoginInfo ();
			return $this->addHeaderFooter ($li, "<profile>$p</profile>");
		}
		else
		{
			$cu = $this->getUrlsXml ();
			return "<root>$cu<profile>$p</profile></root>";
		}		
	}

	// private functions

	function _getLoginInfo ($user = '')
	{
		if (!strlen($user)) $user = $this->_getLoginUserName ();
		$a = $this->_getUserInfo ($user);		
        $a['username'] = $user;
		return $a; 
	}

	function _getUserInfo ($user)
	{		
		global $gConf;
		$ret = array ();

        if (!$user) $user = $this->_getLoginUser();
        if (!$user) return $ret;
        $action = 'user_info';
        $integration_file = '';
        include ($gConf['dir']['base'] . 'xml/url.php');
        if (!$integration_file) return;

        $xml = $this->_read_integration_file ($integration_file);
        if (!$xml) return;

		if (((int)phpversion()) >= 5)		
		{
			$d = new DomDocument();
				
			$d->loadXML($xml);			

			$up = $d->getElementsByTagName ('user_info');

			$up = $up->item(0);

			$n = $up->firstChild;

			do
			{		
				if ($n->nodeType != XML_ELEMENT_NODE) continue;		
				$ret[$n->nodeName] = $n->textContent;
			}
			while ($n = $n->nextSibling);
		}
		else
		{

			if (!$d = domxml_open_mem($xml)) {
				$mk = new Mistake ();
				$mk->log ("Forum::_getUserInfo - can not parse xml: $xml");
				$mk->displayError ("[L[Site is unavailable]]");
			}

			$up = $d->get_elements_by_tagname ('user_info');

			$up = $up[0];
			$n = $up->first_child();

			do
			{			
				if ($n->node_type() != XML_ELEMENT_NODE) continue;
				$ret[$n->node_name ()] = $n->get_content ();
			}
			while ($n = $n->next_sibling());
		}
				

		return $ret;				
	}

	/**
	 * check user perms
	 * @param $user		username
	 * @param $f_type	forum type private/public/own
	 * @param $a_type	access type read/post/edit/del
	 */
	function _checkUserPerm ($user, $f_type, $a_type, $forum_id = 0)
    {
		global $gConf;

		if (!$user) $user = $this->_getLoginUser();
        $action = 'user_perm';
        $integration_file = '';
        include ($gConf['dir']['base'] . 'xml/url.php');        
        if (!$integration_file) return;

        $xml = $this->_read_integration_file ($integration_file);        
        if (!$xml) return;

		if (((int)phpversion()) >= 5)		
		{
			$d = new DomDocument();
				
			$d->loadXML($xml);			

			$up = $d->getElementsByTagName ('user_perm');

			$up = $up->item(0);

			$n = $up->firstChild;

			do
			{		
				if ($n->nodeType != XML_ELEMENT_NODE) continue;		
				if ($n->nodeName == "{$a_type}_{$f_type}") return $n->textContent;
			}
			while ($n = $n->nextSibling);
		}
		else
		{
			
			if (!$d = domxml_open_mem($xml)) {
				$mk = new Mistake ();
				$mk->log ("Forum::_checkUserPerm - can not parse xml : $url");
				$mk->displayError ("[L[Site is unavailable]]");
			}

			$up = $d->get_elements_by_tagname ('user_perm');		

			$up = $up[0];
			$n = $up->first_child();

			do
			{			
				if ($n->node_type() != XML_ELEMENT_NODE) continue;		
				if ($n->node_name () == "{$a_type}_{$f_type}") return $n->get_content ();
			}
			while ($n = $n->next_sibling());
		}
				

		return 0;				
	}


	/**
	 * returns loggen in user
	 */
	function _getLoginUserName ()
	{
		return $this->_getLoginUser();
	}

	/**
	 * returns logged in user
	 */
	function _getLoginUser ()
	{	
		global $gConf;

        $action = 'login_user';
        $integration_file = '';
        include ($gConf['dir']['base'] . 'xml/url.php');
        if (!$integration_file) return;

        $xml = $this->_read_integration_file ($integration_file);
		
		if (((int)phpversion()) >= 5)		
		{
			$d = new DomDocument();

			$d->loadXML($xml);			

			$up = $d->getElementsByTagName ('login_user');

			$up = $up->item(0);

			return $up->textContent;			
		}
		else 
        {
			if (!$d = @domxml_open_mem($xml)) {
				$mk = new Mistake ();
				$mk->log ("Forum::_getLoginUser - can not parse xml : $url");
				$mk->displayError ("[L[Site is unavailable]]");
			}
	
			$n = $d->get_elements_by_tagname ('login_user');
			$n = $n[0];		

			return $n->get_content ();
		}		
	}

    
    function _read_integration_file ($integration_file)
    {
        global $gConf;

        if ('url' == $gConf['integration'])
        {
            if (function_exists('curl_init'))
            {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $integration_file);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);

                $xml = curl_exec($curl);

                curl_close($curl);

                if (true === $xml) $xml = '';           
            }            
            elseif ($h = @fopen ($integration_file, "r"))
            {
                $xml = '';
                while (!feof($h)) 
                            {
                    $xml .= fread($h, 8192);
                }
                fclose($h);
            }
            else
            {			
                $mk = new Mistake ();
                $mk->log ("Forum::_read_integration_file - can not open $integration_file for reading");
                $mk->displayError ("[L[Site is unavailable]]");
            }

            return $xml;
        }
        else
        {
            $orca_integration_xml = '';
            $orca_integration_vars = $integration_file['vars'];
            include ($integration_file['file']);
            return $orca_integration_xml;
        }
        
    }

    function _format_when ($iSec)
    {
        $s = '<b>';
        if ($iSec < 3600)
        {
            $i = round($iSec/60);
            if (0 == $i || 1 == $i) $s .= '1</b> [L[Minute Ago]]';
            else $s .= $i . '</b> [L[Minutes Ago]]';
        }
        else if ($iSec < 86400)
        {
            $i = round($iSec/60/60);
            if (0 == $i || 1 == $i) $s .= '1</b> [L[Hour Ago]]';
            else $s .= $i . '</b> [L[Hours Ago]]';
        }                
        else 
        {
            $i = round($iSec/60/60/24);
            if (0 == $i || 1 == $i) $s .= '1</b> [L[Day Ago]]';
            else $s .= $i . '</b> [L[Days Ago]]';
        }                            
        return $s;     
    }  

    function _no_access ($wp = 0)
    {
        $xml = '<forum_access>no</forum_access>';
        if (!$wp) return $xml;
        $li = $this->_getLoginInfo ();
        return $this->addHeaderFooter ($li, $xml);
    }

    function _buld_topic_desc (&$s)
    {
		$s = str_replace(array('&#160;','&amp;','&gt;','&lt;','&quot;'), array(' ','&','>','<',"'"),strip_tags ($s));
        validate_unicode ($s);
        if ($s == '') $s = ' ';
		$s = '<![CDATA[' . $s . ']]>';
	}

    function uriGenerate ($s, $sTable, $sField, $iMaxLen = 255)
    {
        //$s = orca_mb_replace ('/([^\d^\w]+)/', '-', $s); // latin characters
        $s = orca_mb_replace ('/[^\pL^\pN]+/u', '-', $s); // unicode characters
        $s = orca_mb_replace ('/([-^]+)/', '-', $s);

        if ($this->uriCheckUniq($s, $sTable, $sField)) return $s;

        // try to add date

        if (orca_mb_len($s) > 240)
            $s = orca_mb_substr ($s, 0, 240);

        $s .= '-' . date('Y-m-d');
        
        if ($this->uriCheckUniq($s, $sTable, $sField)) return $s;


        // try to add number

        for ($i = 0 ; $i < 999 ; ++$i)
        {        
            if ($this->uriCheckUniq($s . '-' . $i, $sTable, $sField)) 
            {
                return ($s . '-' . $i);                
            }
        }

        return rand(0, 999999999);
    }

    function uriCheckUniq ($s, $sTable, $sField)
    {
        $fdb = new DbForum ();
        return !$fdb->getOne("SELECT 1 FROM $sTable WHERE $sField = '$s' LIMIT 1");
    }

}

function orca_mb_replace ($sPattern, $sReplace, $s)
{
    return preg_replace ($sPattern, $sReplace, $s);
}

function orca_mb_len ($s)
{
    if (function_exists('mb_strlen'))
        return mb_strlen ($s);
    else
        return strlen ($s);
}    

function orca_mb_substr ($s, $iStart, $iLen)
{
    if (function_exists('mb_substr'))
        return mb_substr ($s, $iStart, $iLen);
    else
        return substr ($s, $iStart, $iLen);
}    

?>
