/**
 *                            Orca Interactive Forum Script
 *                              ---------------
 *     Started          : Mon Mar 23 2006
 *     Copyright        : (C) 2007 BoonEx Group
 *     Website          : http://www.boonex.com
 * This file is part of Orca - Interactive Forum Script
 * GPL
**/


/**
 * admin functionality
 */


/**
 * constructor
 */
function Admin (base, forum)
{	
	this._base = base;
	this._forum = forum;
}   



/**
 * edit categories admin page
 */
Admin.prototype.editCategories = function ()
{
	this._forum.loading ('LOADING');

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');		

		m.innerHTML = r;

		$this._forum.stopLoading ();

		$this._forum.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=edit_categories", urlXsl + "edit_categories.xsl", h);

	document.h.makeHist('action=goto&edit_cats=1');

	return false;
}

/**
 * edit categories admin page
 */
Admin.prototype.reportedPosts = function ()
{
	this._forum.loading ('LOADING');

	var $this = this;

	var h = function (r)
	{		
		var m = document.getElementById('orca_main');		

		m.innerHTML = r;

		$this._forum.stopLoading ();

		$this._forum.checkHeight ();
	}

	new BxXslTransform(this._base + "?action=reported_posts", urlXsl + "forum_posts.xsl", h);

//	document.h.makeHist('action=goto&edit_cats=1');

	return false;
}

/**
 * move category up or down
 *	@param id	category id
*	@param dir	direction (up|down)
 */
Admin.prototype.moveCat = function (cat_id, dir)
{
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{			
			$this.editCategories();		
		}		
	}

	new BxXmlRequest (this._base + "?action=edit_category_move&cat_id="+cat_id+"&dir="+dir, h, true);

	return true;
}

/**
 * delete category
 *	@param id	category id
 */
Admin.prototype.delCat = function (cat_id)
{
	if (!confirm ('Are you sure to delete category with all forums, topics and post')) return false;

	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			alert ('Category has been successfully deleted');
			$this.editCategories();
			return;
		}

		alert ('Can not delete category');
	}

	new BxXmlRequest (this._base + "?action=edit_category_del&cat_id="+cat_id, h, true);

	return true;
}

/**
 * delete forum
 *	@param forum_id	forum id
 */
Admin.prototype.delForum = function (forum_id)
{
	if (!confirm ('Are you sure to delete forum with topics and posts')) return false;

	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if (ret > 0)
		{
			alert ('Forum has been successfully deleted');			
			$this.selectCat(ret, 'cat'+ret, true, true);
			return;
		}

		alert ('Can not delete forum');
	}

	new BxXmlRequest (this._base + "?action=edit_forum_del&forum_id="+forum_id, h, true);

	return true;
}

/**
 * edit category
 *	@param id	category id
 */
Admin.prototype.editCat = function (cat_id)
{	
	var $this = this;

	var h = function (r)
	{			
		$this._forum.showHTML (r, 300, 200);
	}

	new BxXslTransform(this._base + "?action=edit_category&cat_id="+cat_id, urlXsl + "edit_cat_form.xsl", h);

	return true;
}

/**
 * new group
 */
Admin.prototype.newCat = function ()
{	
	var $this = this;

	var h = function (r)
	{			
		$this._forum.showHTML (r, 300, 200);
	}

	new BxXslTransform(this._base + "?action=edit_category&cat_id="+0, urlXsl + "edit_cat_form.xsl", h);

	return true;
}

/**
 * edit category
 *	@param cat_name	new group name
 *	@param cat_id	category id 
 */
Admin.prototype.editCatSubmit = function (cat_id, cat_name)
{
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			if (cat_id > 0)
				alert ('Group has been successfully modified');
			else
				alert ('New group has been successfully added');
			$this._forum.hideHTML();
			$this.editCategories();
			return false;
		}

		if (cat_id > 0)
			alert ('Can not modify group');
		else
			alert ('Can not add new group');
		return false;
	}

    cat_name = encodeURIComponent (cat_name); 

	new BxXmlRequest (this._base + "?action=edit_category_submit&cat_id="+cat_id+"&cat_name="+cat_name, h, true);

	return false;
}


/**
 * edit forum
 *	@param id	category id
 */
Admin.prototype.editForum = function (forum_id)
{	
	var $this = this;

	var h = function (r)
	{			
		$this._forum.showHTML (r, 400, 200);
	}

	new BxXslTransform(this._base + "?action=edit_forum&forum_id="+forum_id, urlXsl + "edit_forum_form.xsl", h);

	return true;
}


/**
 * new category
 */
Admin.prototype.newForum = function (cat_id)
{	
	var $this = this;

	var h = function (r)
	{			
		$this._forum.showHTML (r, 400, 200);
	}
	
	new BxXslTransform (this._base + "?action=edit_forum&forum_id=0&cat_id="+cat_id, urlXsl + "edit_forum_form.xsl", h);

	return true;
}


/**
 * edit forum
 *	@param forum_id	forum id
 *	@param title 	forum title
 *	@param desc 	forum description
 *	@param type 	forum type
 */
Admin.prototype.editForumSubmit = function (cat_id, forum_id, title, desc, type)
{
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');			
		var ret = o.getRetNodeValue (r, 'ret');
		if ('1' == ret)
		{
			if (forum_id > 0)
				alert ('Forum has been successfully modified');
			else
				alert ('New forum has been successfully added');
			$this._forum.hideHTML();
			$this.selectCat (cat_id, 'cat'+cat_id, true, true);			
			return false;
		}

		if (forum_id > 0)
			alert ('Can not modify forum');
		else
			alert ('Can not add new forum');
		return false;
	}

    title = encodeURIComponent(title); 
    desc = encodeURIComponent(desc); 

	new BxXmlRequest (this._base + "?action=edit_forum_submit&cat_id="+cat_id+"&forum_id="+forum_id+"&title="+title+"&desc="+desc+"&type="+type, h, true);

	return false;
}


/**
 * returns new topic page XML
 */
Admin.prototype.selectCat = function (cat, id, force_show, force_reload)
{	
	var e = document.getElementById(id);

	if (!e) 
	{
		new BxError("category id is not defined", "please set category ids");
		return false;
	}

	// determine next forum sibling 
	var et = e.nextSibling;	
	while (et && !(et.tagName == 'DIV' || et.tagName == 'UL'))
		et = et.nextSibling;
	if (et && et.tagName != 'DIV') et = null;

	// determine next cat sibling 
	var en = e.nextSibling;	
	while (en && en.tagName != 'UL' && en.id && !en.id.match(/^cat/))
		en = en.nextSibling;

	var ei = e.getElementsByTagName('div')[0];

	if (et && !force_show)
	{
		ei.style.backgroundPosition = '0px 0px';
		e.parentNode.removeChild (et);
		if (!force_reload) return false;
	}

	this._forum.loading ('LOADING FORUMS');

	var $this = this;

	this._cat = cat;
	
	var h = function (r)
	{	
		var d = document.createElement("div");		
		d.innerHTML = r;

		if (et)
			e.parentNode.replaceChild (d, et);
		else
			e.parentNode.insertBefore (d, en);		

		ei.style.backgroundPosition = '0px -32px';

		$this._forum.stopLoading ();

		$this._forum.checkHeight ();

		return false;
	}

	new BxXslTransform(this._base + "?action=list_forums_admin&cat=" + cat, urlXsl + "edit_cat_forums.xsl", h);

	//document.h.makeHist('action=goto&cat_id=' + cat);

	return false;
}

/*
 * lock/unlock
 */
Admin.prototype.lock = function (topic_id, locked)
{				
	var $this = this;

	var h = function (r)
	{		
		var o = new BxXmlRequest('','','');
		var ret = o.getRetNodeValue (r, 'ret');
        var eImg = document.getElementById('btn_lock_topic');
		if ('1' == ret)
		{                   
			alert ('Topic has been successfully locked');            
            if (eImg) 
            {
                eImg.src = eImg.src.replace(/unlocked/,'locked');
                var eB = eImg.nextSibling;
                if (eB.tagName != 'B') eB = eB.nextSibling;
                //if (eB.tagName == 'B') eB.innerHTML = eB.innerHTML.replace(/Lock/,'Unlock');
            }
			return false;
		}
		if ('-1' == ret)
		{         
			alert ('Topic has been successfully unlocked');
            if (eImg) 
            {
                eImg.src = eImg.src.replace(/locked/,'unlocked');
                var eB = eImg.nextSibling;
                if (eB.tagName != 'B') eB = eB.nextSibling;
                //if (eB.tagName == 'B') eB.innerHTML = eB.innerHTML.replace(/Unlock/,'Lock');
            }
			return false;
		}

		alert ('Only admin can lock/unlock topics');
		return false;
	}

	new BxXmlRequest (this._base + "?action=lock_topic&topic_id=" + topic_id + "&ts=" + (new Date()), h, true);

	return false;
}
