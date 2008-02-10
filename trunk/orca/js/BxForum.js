/**
*                            Orca Interactive Forum Script
*                              ---------------
*     Started             : Mon Mar 23 2006
*     Copyright           : (C) 2007 BoonEx Group
*     Website             : http://www.boonex.com
* This file is part of Orca - Interactive Forum Script
* GPL
**/


/**
 * forum functionality
 */


/**
 * constructor
 */
function Forum (base, min_points)
{	
	this._base = base;
	this._forum = 0;
	this._topic = 0;
	this._min_points = min_points;
}   


/**
 * edit post
 * @param id	post id 
 */
Forum.prototype.editPost = function (id)
{
	var node = document.getElementById(id);
	if (!node)
	{
		this.showHiddenPost(id, '$this.editPost (id);');
		return;
	}
	
	// ---------------


	var $this = this;

	var h = function (r)
	{		

		var html = node.innerHTML;		

		if (node.getElementsByTagName('form')[0]) return false;

		if (!node.parentNode.style.height || node.parentNode.style.height != 'auto')
		{
			node.parentNode._height = node.parentNode.style.height;
			node.parentNode.style.height = 'auto';
		}
	
		var div = document.createElement('div');
		
		div.innerHTML = r;

		node.appendChild (div);
		node.style.height = '310px';
		node.style.overflow = 'hidden';		


		window.orcaSetupContent = function (id, body, doc)
		{	
			body.innerHTML = html;			
			window.orcaSetupContent = function (id, body, doc) {};
		}

        if (document.getElementById('tinyEditor_'+id))
		tinyMCE.execCommand('mceAddControl', false, 'tinyEditor_'+id);

//		tinyMCE.setContent (html);
		
/*
		if (!window.ed)
		{
			window.ed = new BxEditor('edit');
			document.ed = window.ed;
			window.ed.inited = 0;
		}     
		else
		{
			document.ed = window.ed;
			window.ed.setName('edit');
			window.ed.inited = 0;

		}

		var e = div.getElementsByTagName ('iframe')[0];
		e.onload = function () 
		{ 		
			window.ed.init();
			window.ed.setText(html);		

			if (window.ed.inited) return;				
			window.ed.initMenu();	
			window.ed.inited = 1;		
		}

		e.onreadystatechange = function ()
		{	
			if (this.readyState == 'complete') 
		    {			
				window.ed.init();
				window.ed.setText(html);

				if (window.ed.inited) return;
				window.ed.initMenu();	
				window.ed.inited = 1;			
			}
		}

		e.src = "src.html";	
*/
		$this.checkHeight ();		

		return false;
	}	

	new BxXslTransform(this._base + "?action=edit_post_xml&post_id=" + id + "&topic_id=" + this._topic, urlXsl + "edit_post.xsl", h);

	return false;

}

/**
 * cancel post editing
 * @param id	post id 
 */
Forum.prototype.editPostCancel = function (id)
{
	var node = document.getElementById(id);
	var f = node.getElementsByTagName('form')[0];
	if (!f) return false;

	tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor_'+id);

	node.removeChild(f.parentNode);
	node.style.height = 'auto';

	if (node.parentNode._height) node.parentNode.style.height = node.parentNode._height;	

	this.checkHeight ();
}
/**
 * expand/collapse rearch result row
 * @param id	post id 
 */
Forum.prototype.expandPost = function (id)
{	
	var p = document.getElementById(id);
	var ul = p.parentNode.parentNode;
	var lis = ul.getElementsByTagName('li');
	var divs = p.parentNode.getElementsByTagName('div');
	var ll = divs.length;
	var l = lis.length;
	var n = parseInt (lis[0].style.height);
	var e = null;

	for (var i=0 ; i<ll ; ++i)
	{
		if (divs[i].className == 'colexp2') 
		{
			e =	divs[i];
			break;
		}
	}

	if (36 == n || '' == lis[0].style.height)
	{		
		for (var i=0 ; i<l ; ++i)
			lis[i].style.height = parseInt(lis[i].clientHeight) + parseInt(p.clientHeight ? p.clientHeight : p.offsetHeight) + 15 + "px";
		e.style.backgroundPosition = '0px -13px';
	}
	else
	{
		for (var i=0 ; i<l ; ++i)
			lis[i].style.height = '36px';
		e.style.backgroundPosition = '0px 0px';
	}

	this.checkHeight ();
}

/**
 * search the forum
 */
Forum.prototype.search = function (text, type, forum, u, disp)
{	
	this.loading ('SEARCHING');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

    if (-1 == text.search('%'))
        text = encodeURIComponent(text);

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');		

		m.innerHTML = r;

		$this.runScripts ('orca_main');

        $this.setWindowTitle(null); 

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=search&text=" + text + "&type=" + type + "&forum=" + forum + "&u=" + u + "&disp=" + disp, urlXsl + "search.xsl", h);

	document.h.makeHist('action=goto&search_result=1&' + text + '&' + type + '&' + forum + '&' + u + '&' + disp);

	return false;
}
	

/**
 * returns new topic page XML
 */
Forum.prototype.selectCat = function (cat, id)
{		
	var e = document.getElementById(id);

	if (!e) 
	{
		new BxError("category id is not defined", "please set category ids");
		return false;
	}

	// determine next forum sibling 
	var et = e.nextSibling;	
	while (et && !(et.tagName == 'DIV' || et.tagName == 'div' || et.tagName == 'UL' || et.tagName == 'ul'))
		et = et.nextSibling;
	if (et && et.tagName != 'DIV' && et.tagName != 'div') 
		et = null;

	// determine next cat sibling 
	var en = e.nextSibling;	
	while (en && en.tagName != 'UL' && en.tagName != 'ul' && en.id && !en.id.match(/^cat/))
		en = en.nextSibling;

	var ei = e.getElementsByTagName('div')[0];

	if (et)
	{
		ei.style.backgroundPosition = '0px 0px';
		e.parentNode.removeChild (et);
		return false;
	}

	this.loading ('LOADING FORUMS');

	var $this = this;

	var h = function (r)
	{		

		var d = document.createElement("div");		
		d.innerHTML = r;

		if (et)
			e.parentNode.replaceChild (d, et);
		else
			e.parentNode.insertBefore (d, en);		

        $this.setWindowTitle(null);

		ei.style.backgroundPosition = '0px -32px';

		$this.stopLoading ();

		$this.checkHeight ();
		
	}

	new BxXslTransform(this._base + "?action=list_forums&cat=" + cat, urlXsl + "cat_forums.xsl", h);

	document.h.makeHist('action=goto&cat_id=' + cat);

	return false;
}

/**
 * select forum
 *	@param id	forum id
 */
Forum.prototype.selectForum = function (id, start)
{
	this.loading ('LOADING FORUM TOPICS');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	this._forum = id;

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');		

		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=list_topics&forum=" + this._forum + "&start=" + start, urlXsl + "forum_topics.xsl", h);

	document.h.makeHist('action=goto&forum_id=' + this._forum + "&" + start);

	return false;
}


/**
 * select forum
 *	@param id	forum id
 */
Forum.prototype.selectForumIndex = function (cat)
{
	this.loading ('LOADING FORUM INDEX');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();

		var ec = document.getElementById('cat' + cat);	
		if (ec) ec.blur();

		correctPNG('live_fade');
	}

	new BxXslTransform(this._base + "?action=forum_index" + (cat ? ("&cat=" + cat) : ''), urlXsl + "home.xsl", h);

	document.h.makeHist('action=goto&cat_id=' + cat);

	return false;
}



/**
 * show profile page
 *	@param user	usrname to show 
 */
Forum.prototype.showProfile = function (user)
{
	this.loading ('LOADING PROFILE PAGE');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=profile&user=" + user, urlXsl + "profile.xsl", h);

	document.h.makeHist('action=goto&profile=' + user);

	return false;
}


/**
 * select topic
 *	@param id	topic id
 */
Forum.prototype.selectTopic = function (id)
{
	this.loading ('LOADING TOPIC POSTS');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	this._topic = id;

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;

        $this.setWindowTitle(null);

        $this.runScripts ('orca_main');

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=list_posts&topic=" + this._topic, urlXsl + "forum_posts.xsl", h);

	document.h.makeHist('action=goto&topic_id=' + this._topic);

	return false;
}


/**
 * open new 'post new topic' page
 *	@param id	forum id
 */
Forum.prototype.newTopic = function (id)
{
	this.loading ('LOADING POST TOPIC PAGE');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	this._forum = id;

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

        $this.setWindowTitle(null);

        if (document.getElementById('tinyEditor'))
        {
            if (0 < document.getElementById('tinyEditor').value.length)
                document.getElementById('tinyEditor').value = '';
		tinyMCE.execCommand('mceAddControl', false, 'tinyEditor'); 
        }

		$this.stopLoading ();

/*
        if (!window.ed)
        {
            window.ed = new BxEditor('edit');
            document.ed = window.ed;
            window.ed.init();
            window.ed.initMenu();
        } 
        
        else
        {
            document.ed = window.ed;
            window.ed.setName('edit');
            window.ed.init();
            window.ed.initMenu();
        }
*/		

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=new_topic&forum=" + this._forum, urlXsl + "new_topic.xsl", h);

	document.h.makeHist('action=goto&new_topic=' + this._forum);

	return false;
}


/**
 * cancel new topic submission
 */
Forum.prototype.cancelNewTopic = function (forum_id, start)
{
	if (document.getElementById('tinyEditor'))
		tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor');

	return this.selectForum (forum_id, start);
}

/**
 * my threads page
 */
Forum.prototype.showMyThreads = function ()
{
    if (!isLoggedIn)
    {
        alert('Please login to view topics you participate in');
        return;
    }

	this.loading ('LOADING');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=show_my_threads", urlXsl + "forum_topics.xsl", h);

	document.h.makeHist('action=goto&my_threads=1');

	return false;
}


/**
 * my flags page
 */
Forum.prototype.showMyFlags = function ()
{
    if (!isLoggedIn)
    {
        alert('Please login to view flagged topics');
        return;
    }

	this.loading ('LOADING');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=show_my_flags", urlXsl + "forum_topics.xsl", h);

	document.h.makeHist('action=goto&my_flags=1');

	return false;
}

/**
 * open new 'search' page
 */
Forum.prototype.showSearch = function ()
{
	this.loading ('LOADING SEARCH PAGE');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=show_search", urlXsl + "search_form.xsl", h);

	document.h.makeHist('action=goto&search=1');

	return false;
}




/**
 * open new 'post reply' page
 *	@param id_f	forum id
 *	@param id_t	topic id
 */
Forum.prototype.postReply = function (id_f, id_t)
{
	this.loading ('LOADING POST REPLY PAGE');

	var m = document.getElementById('reply_container');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	if (document.getElementById('tinyEditor'))
	{
		tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor'); 
	}

	this._forum = id_f;
	this._topic = id_t;

	var $this = this;

	var h = function (r)
	{		
/*
		var bt = document.getElementById('reply_button');
		if (!bt) 
		{
			new BxError("reply_button div is not defined", "please name it");
		}
		bt.style.display = 'none';
*/
		m.innerHTML = r;
        m.style.display='block';

        if (document.getElementById('tinyEditor'))
        {
            if (0 < document.getElementById('tinyEditor').value.length)
                document.getElementById('tinyEditor').value = '';
		tinyMCE.execCommand('mceAddControl', false, 'tinyEditor'); 
        }

		$this.stopLoading ();
/*
		if (!window.ed)
		{
			window.ed = new BxEditor('edit');
			document.ed = window.ed;
			window.ed.init();
			window.ed.initMenu();
		}

		else
		{
			document.ed = window.ed;
			window.ed.setName('edit');
			window.ed.init();
			window.ed.initMenu();
		}
*/
		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=reply&forum=" + this._forum + "&topic=" + this._topic, urlXsl + "post_reply.xsl", h);

	return false;
}



/**
 * open new 'post reply' page
 *	@param id_f	forum id
 *	@param id_t	topic id
 */
Forum.prototype.postReplyWithQuote = function (id_f, id_t, p_id)
{
	this.loading ('LOADING POST REPLY PAGE');

	var m = document.getElementById('reply_container');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	if (document.getElementById('tinyEditor'))
	{
		tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor'); 
	}

	this._forum = id_f;
	this._topic = id_t;

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;		
        m.style.display='block';

		var post = $this.getPostText(p_id);

		post = post.replace (/<text>/ig, '')
		post = post.replace (/<\/text>/ig, '')
		post =  '<p>&#160;</p><div class="quote_post">' + post + '</div> <p>&#160;</p>';

		window.orcaSetupContent = function (id, body, doc)
		{	
			body.innerHTML = post;			
			window.orcaSetupContent = function (id, body, doc) {};
		}

        if (document.getElementById('tinyEditor'))
		tinyMCE.execCommand('mceAddControl', false, 'tinyEditor'); 

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=reply&forum=" + this._forum + "&topic=" + this._topic, urlXsl + "post_reply.xsl", h);

	return false;
}

/**
 * cancel reply
 */
Forum.prototype.cancelReply = function ()
{
/*
	var bt = document.getElementById('reply_button');
	if (!bt) 
	{
		new BxError("reply_button div is not defined", "please name it");
	}
	bt.style.display = 'inline';
*/

	if (document.getElementById('tinyEditor'))
	{
		tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor'); 
	}

	var m = document.getElementById('reply_container');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}
	m.innerHTML = '&#160;';
    m.style.display='none';
}

/**
 * show access denied page
 */
Forum.prototype.accessDenied = function ()
{
	this.loading ('LOADING PAGE');

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

        $this.setWindowTitle(null);

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=access_denied", urlXsl + "default_access_denied.xsl", h);

	return false;
}

/**
 * show new topic successfully created  page
 *	@param forum_id	forum id
 */
Forum.prototype.postSuccess = function (forum_id)
{
	this.loading ('LOADING PAGE');	

	var m = document.getElementById('orca_main');
	if (!m) 
	{
		new BxError("orca_main div is not defined", "please name orca_main content container");
	}

	if (document.getElementById('tinyEditor'))
	{
		tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor'); 
	}

	this._forum = forum_id;

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');

		m.innerHTML = r;

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=post_success&forum=" + forum_id, urlXsl + "default_post_success.xsl", h);

	return false;
}


/**
 * show reply success page
 *	@param f_id	forum id
 *	@param t_id	topic id
 */
Forum.prototype.replySuccess = function (f_id, t_id)
{
	tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor');
	return this.selectTopic(t_id);
}


/**
 * delete post
 *	@param p	post id
 *	@param f	forum id
 *	@param t	topic id
 *	@param ask	conform deletetion
 */
Forum.prototype.deletePost = function (p, f, t, ask)
{
	if (ask) if (!confirm('Are you sure ?')) return false;

	var form = document.getElementById('tmp_del_form');

	if (!form) 
	{
		form = document.createElement('form');
		form.style.display = 'none';
		form.id = 'tmp_del_form';
		form.method = 'post';
		form.target = 'post_actions';
		document.body.appendChild(form);
	}

	if (!form) return;

	form.action = 'index.php?action=delete_post&post_id=' + p + '&forum_id=' + f + '&topic_id=' + t;
	form.submit();

	return false;
}

/**
 * show delete success page
 *	@param forum_id	forum id
 */
Forum.prototype.deleteSuccess = function (f_id, t_id, t_exists)
{
	if (f_id)
	{
		if (t_exists)
			this.selectTopic (t_id);
		else
			this.selectForum (f_id, 0);
	}
	else if (0 == f_id && 0 == t_id)
	{
		orca_admin.reportedPosts();
	}

	if (t_exists)
		this.showModalMsg ("Post was successfully deleted");
	else
		this.showModalMsg ("Topic and post were successfully deleted");

	return false;
}

/**
 * show edit success page
 *	@param forum_id	forum id
 */
Forum.prototype.editSuccess = function (t_id)
{
	this.selectTopic(t_id);

	this.showModalMsg ("Post was successfully edited");

	return false;
}

/**
 * show compose message form
 */
Forum.prototype.composeMessage = function (to, mid)
{
	this.loading ('LOADING COMPOSE MESSAGE PAGE');

	var $this = this;

	var h = function (r)
	{
	    var e = document.getElementById('messages');
		e.innerHTML = r;

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform (this._url + "&compose=1&to=" + to + "&mID=" + mid, urlXsl + "mailbox_compose.xsl", h);
}



/**
 * show compose message form
 */
Forum.prototype.composeComplete = function (ret)
{
	this.loading ('SENDING MESSAGE');

	var $this = this;

	var h = function (r)
	{
	    var e = document.getElementById('messages');
		e.innerHTML = r;

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform (this._url + "&compose_complete=1&ret=" + ret, urlXsl + "mailbox_compose_complete.xsl", h);
}

/**
 * validate compose message form
 */
Forum.prototype.validateForm = function (f)
{
	if (f['mText'].value.length == 0 )
	{
		alert('Message body empty');
		return false;
	}

	this.loading ('SENDING MESSAGE');

	return true;
}

/**
 * show valid charachters
 */
Forum.prototype.showValidChars = function (a)
{
	alert("Valid chars:\r\n A-Z a-z 0-9 ! @ # $ % ^ & * ( ) < > _ = + { } ' \" ? . : , | / \\ [] -");
}

/**
 * check string value
 */
Forum.prototype.checkSubject = function (s)
{
	//if (!s.match (/^[\sA-Za-z0-9\!@#\$%\^&\*\(\)_\=\+\{\}'\"\?\.:,\|/\\\[\]\-]{5,50}$/))	
	if (s.length < 5 || s.length > 50)
		return false;
	return true;
}

/**
 * check string value
 */
Forum.prototype.checkText = function (s)
{
	return ((s.length > 4 && s.length < 64000) ? true : false);
}

/**
 * check form values
 */
Forum.prototype.checkPostTopicValues = function (s, t, n)
{	
	var ret1 = false;
	var ret2 = false;
	var e;

	if (true == n)
	{
		e = document.getElementById('err_' + s.name);	
		if (!this.checkSubject(s.value)) 
		{		
			if (e) e.style.display = "inline";
			s.style.backgroundColor = "#ffaaaa";
			s.focus();
		}
		else
		{
			if (e) e.style.display = "none";
			s.style.backgroundColor = "#ffffff";
			ret1 = true;
		}
	}

	e = document.getElementById('err_' + t.name);	
	if (!this.checkText(t.value)) 
	{
		if (e) e.style.display = "inline";
		t.style.backgroundColor = "#ffaaaa";
//		if (!ret1) t.focus ();
	}
	else
	{
		if (e) e.style.display = "none";
		t.style.backgroundColor = "#ffffff";
		ret2 = true;
	}

	return (n ? (ret1 && ret2) : ret2);
}



/**
 * create and display loading message
 */
Forum.prototype.hideModalMsg = function ()
{
	var e = document.body;
	var l = document.getElementById ("modal_msg");
	e.removeChild(l);
}

/**
 * create and display loading message
 */
Forum.prototype.showModalMsg = function (str)
{

	var e = document.body;
	var t = document.createTextNode(str);
	var d = document.createElement("div");
	var s = document.createElement("div");
	var br = document.createElement("br");
	var i = document.createElement("input");		

	e.appendChild (d);

	d.id = "modal_msg";
	d.style.position = "absolute";
	d.style.zIndex = "50001";
	d.style.textAlign = "center";
	d.style.width = e.clientWidth + "px";
	d.style.height = (window.innerHeight ? (window.innerHeight + 30) : screen.height) + "px";			
	d.style.top = getScroll() - 30 + "px";
	d.style.left = 0 + "px";
	d.style.display = "inline";
	d.style.backgroundImage = "url(/img/loading_bg.gif)";


	s.style.border = "1px solid #B5B5B5";
	s.style.backgroundColor = "#F3F3F3";
	s.style.color = "#333333";
	s.style.padding = "20px";
	s.style.marginTop = (parseInt(d.style.height) / 2 - 20) + "px";
	s.style.marginLeft = "auto";
	s.style.marginRight = "auto";
	s.style.width = "300px";
	s.style.fontWeight = "bold";
	s.style.lineHeight = "30px";

	i.type = "reset";
	i.value = " OK ";
	i.style.marginTop = "15px";
	i.onclick = function () {
		document.f.hideModalMsg ();
		return false;
	}

	d.appendChild(s);

	s.appendChild(t);
	s.appendChild(br);
	s.appendChild(i);

}

/**
 * create and display loading message
 */
Forum.prototype.stopLoading = function ()
{
	var l = document.getElementById ("loading");
	if (l)
	{
		l.style.display = "none";
	}
}

/**
 * create and display loading message
 */
Forum.prototype.loading = function (sid)
{

	var d = document.getElementById ("loading");
	var e = document.body; // getElementById('content');

	if (d)
	{
		d.firstChild.innerHTML = sid + "...";
		d.style.top = getScroll() - 30 + "px";
		d.style.left = 0 + "px";
		d.style.display = "inline";
	}
	else
	{
		var d = document.createElement("div");
		var s = document.createElement("span");

		e.appendChild (d);

		d.id = "loading";
		d.style.position = "absolute";
		d.style.zIndex = "50000";
		d.style.textAlign = "center";
		d.style.width = e.clientWidth + "px";
		d.style.height = (window.innerHeight ? (window.innerHeight + 30) : screen.height) + "px";			
		d.style.top = getScroll() - 30 + "px";
		d.style.left = 0 + "px";
		d.style.display = "inline";
		d.style.backgroundImage = "url(img/loading_bg.gif)";		

		s.style.border = "1px solid #B5B5B5";
		s.style.backgroundColor = "#F3F3F3";
		s.style.color = "#333333";
		s.style.padding = "20px";
		s.style.fontWeight = "bold";
		s.style.lineHeight = d.style.height;

		d.appendChild(s);
		s.innerHTML = sid + "...";
	}
}



/**
 * create and display loading message
 */
Forum.prototype.hideHTML = function (w, h, html)
{
	var l = document.getElementById ("show_html");
	
	if (l)
	{
		document.body.removeChild(l);
	}
}

/**
 * create and display loading message
 */
Forum.prototype.showHTML = function (html, w, h)
{
	var d = document.getElementById ("show_html");
	var e = document.body; 

	if (d)
	{
		var div = d.firstChild;
		div.innerHTML = html;
		d.style.top = getScroll() - 30 + "px";
		d.style.left = 0 + "px";
		d.style.display = "block";
		if (w) div.style.width = w + 'px';
		if (h) div.style.height = h + 'px';
		div.style.top = parseInt(d.style.height)/2 - h/2 + 'px';
		div.style.width = parseInt(d.style.width)/2 - w/2 + 'px';
	}
	else
	{
		var d = document.createElement("div");
		var div = document.createElement("div");

		e.appendChild (d);

		d.id = "show_html";
		d.style.position = "absolute";
		d.style.zIndex = "49000";
		d.style.textAlign = "center";
		d.style.width = e.clientWidth + "px";
		d.style.height = (window.innerHeight ? (window.innerHeight + 30) : screen.height) + "px";			
		d.style.top = getScroll() - 30 + "px";
		d.style.left = 0 + "px";
		d.style.display = "inline";
		d.style.backgroundImage = "url(img/loading_bg.gif)";		

		div.innerHTML = html;
		div.style.position = "absolute";
		if (w) div.style.width = w + 'px';
		if (h) div.style.height = h + 'px';
		div.style.top = parseInt(d.style.height)/2 - h/2 + 'px';
		div.style.left = parseInt(d.style.width)/2 - w/2 + 'px';

		d.appendChild(div);
	}
}


/*
 * correct auto height in explorer
 */
Forum.prototype.checkHeight = function ()
{
//	e_c = document.getElementById('content');
//	if (!e_c) return;
//	e_c.style.height = "100px";
//	e_c.style.height = "auto";
}


Forum.prototype.hideHiddenPost = function (id)
{
	this.loading ('POST IS LOADING');

	var m = document.getElementById('post_row_'+id);
	if (!m) 
	{
		return false;
	}	

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;

		$this.stopLoading ();

		$this.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=hide_hidden_post&post_id=" + id, urlXsl + "forum_posts.xsl", h);

	//document.h.makeHist('action=goto&forum_id=' + this._forum + "&" + start);

	return false;		
}

Forum.prototype.showHiddenPost = function (id, run)
{
	this.loading ('POST IS LOADING');

	var m = document.getElementById('post_row_'+id);
	if (!m) 
	{
		return false;
	}	

	var $this = this;

	var h = function (r)
	{		
		m.innerHTML = r;

        var mm = document.getElementById('post_row_'+id);
        if (mm)
        {
            mm.innerHTML = $this.replaceTildaA (mm.innerHTML);
        }


        $this.runScripts ('post_row_'+id);

		$this.stopLoading ();

		$this.checkHeight ();

		if (run) eval (run);
	}

	new BxXslTransform(this._base + "?action=show_hidden_post&post_id=" + id, urlXsl + "forum_posts.xsl", h);

	//document.h.makeHist('action=goto&forum_id=' + this._forum + "&" + start);

	return false;	
}

/*
 * align the post, after the avatar load
 */
Forum.prototype.alignPost = function (img, points)
{
	if (img.parentNode && points >= this._min_points)
	{
		var d = img.parentNode.parentNode.parentNode; 
		var y = 35;//img.parentNode.y | img.parentNode.offsetTop; 
		if ((img.clientHeight + y ) > d.clientHeight) 
			d.style.height = img.clientHeight + 5 + "px"; 
		document.f.checkHeight(); 
	}
}

/*
 * good vote post 
 */
Forum.prototype.voteGood = function (post_id)
{				
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			var e = document.getElementById ('points_'+post_id);
			e.innerHTML = parseInt(e.innerHTML) + 1;
			$this.hideVoteButtons (post_id);
			$this.hideReportButton  (post_id);
			return false;
		}

		alert ('Vote error');
		return false;
	}

	new BxXmlRequest (this._base + "?action=vote_post_good&post_id="+post_id, h, true);

	return false;		
}

/*
 * flag/unflag 
 */
Forum.prototype.flag = function (topic_id)
{				
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			alert ('Topic has been successfully added to your flagged topics');
			return false;
		}
		if ('-1' == ret)
		{
			alert ('Topic has been successfully removed from your flagged topics');
			return false;
		}

		alert ('Please login to flag topics');
		return false;
	}

	new BxXmlRequest (this._base + "?action=flag_topic&topic_id="+topic_id, h, true);

	return false;
}

/*
 * report post 
 */
Forum.prototype.report = function (post_id)
{				
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			alert ('Post has been reported');
			return false;
		}

		alert ('Report error');
		return false;
	}

	new BxXmlRequest (this._base + "?action=report_post&post_id="+post_id, h, true);

	return false;		
}

/*
 * place -1 vote for post
 */
Forum.prototype.voteBad = function (post_id)
{
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			var e = document.getElementById ('points_'+post_id);
			e.innerHTML = parseInt(e.innerHTML) - 1;
			$this.hideHiddenPost (post_id);
			return false;
		}

		alert ('Vote error');
		return false;
	}

	new BxXmlRequest (this._base + "?action=vote_post_bad&post_id="+post_id, h, true);

	return false;				
}

/*
 * make vote buttons inactive
 */
Forum.prototype.hideVoteButtons = function (post_id)
{
	var e = document.getElementById('rate_'+post_id);
	var a = e.getElementsByTagName('img');
	if (a[0]) 
	{
		a[0].src = urlImg + 'vote_good_gray.gif';
		a[0].parentNode.onclick = function () {};
	}
	if (a[1]) 
	{
		a[1].src = urlImg + 'vote_bad_gray.gif';
		a[1].parentNode.onclick = function () {};
	}	
}

/*
 * make report button inactive
 */
Forum.prototype.hideReportButton = function (post_id)
{
	var e = document.getElementById('report_'+post_id);
	var a = e.getElementsByTagName('img');
	if (a[0]) 
	{
		a[0].src = urlImg + 'report_gray.gif';
		a[0].parentNode.onclick = function () {};
	}
}

Forum.prototype.getPostText = function (post_id)
{
	var e = document.getElementById(post_id);
	if (!e) return '';

	return e.innerHTML;
}

function getScroll()
{
	if (navigator.appName == "Microsoft Internet Explorer")
	{
//		return document.body.scrollTop;
		return document.documentElement.scrollTop
	}
	else
	{
		return window.pageYOffset;
	}
}


Forum.prototype.livePost = function (ts)
{
	var to = 3000;  // timeout
	var $this = this;

	var lt = document.getElementById('live_tracker');

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		//ret = parseInt(ret);
		if (ret > 0)
		{			
			// get new post and insert it 

			var hh = function (r)			
			{			
				if (!lt) return;

				// delete oldest

				var ln = lt.lastChild;
				while (ln.className != 'live_post')
				{
					ln = ln.previousSibling;
					if (!ln) break;
				}

                                if (ln)
				lt.removeChild (ln);

				// insert new
				lt.innerHTML = r + lt.innerHTML;
	
				// watch latest post
				setTimeout('f.livePost('+ret+')', to);

				// super effect, get new inserted item
				var fn = lt.firstChild;
				while (fn.className != 'live_post')
				{
					fn = fn.nextSibling;
					if (!fn) break;
				}
				setTimeout('f.fade(\'' + fn.id + '\',1,1,1)', 100);
			}

			new BxXslTransform ($this._base + "?action=get_new_post&ts=" + ts +"&now=" + (new Date()), urlXsl + "live_tracker.xsl",hh);
		
			return false;
		}	

		// watch latest post	
		setTimeout('f.livePost('+ts+')', to);

		return false;
	}

	
	if (lt)
		new BxXmlRequest (this._base + "?action=is_new_post&ts=" + ts +"&now=" + (new Date()), h, true);	

	return false;		
}


Forum.prototype.fade = function (id, r, g, b)
{
	r += 5;
	g += 5;
	b += 5;

	if (r > 59) r = 59;
	if (g > 59) g = 59;
	if (b > 59) b = 59;

	var e = document.getElementById (id);
	e.style.height = b + 'px';

	if (r < 59 || g < 59 || b < 59) 
		setTimeout('f.fade(\'' + id + '\','+r+','+g+','+b+')', 100);
}

Forum.prototype.setWindowTitle = function (s)
{

    if ((!s || !s.length) && document.getElementById('forum_title'))
            s = document.getElementById('forum_title').innerHTML;

    if (!s || !s.length)
        window.document.title = defTitle;
    else
    window.document.title = s + ' :: Orca Forum';
}

Forum.prototype.runScripts = function (id)
{
    var ee = document.getElementById(id);
    var a = ee.getElementsByTagName('script');
    var ajs = new Array(a.length);

    if (!a.length) return;

    for (var i=0 ; i<a.length ; ++i)
    {        
        if (!a[i]) continue;

        ajs[i] = a[i].innerHTML;
    }

    for (var i=0 ; i<ajs.length ; ++i)
    {
        eval (ajs[i]);
    }
}

Forum.prototype.replaceTildaA = function (s)
{
    return s.replace (/\xC2/gm,'');    
}
