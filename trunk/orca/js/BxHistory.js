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
 * Enable back browser button for ajax
 */


isIE = 0;
if (document.all && !window.opera) isIE = 1;

/**
 * constructor
 */
function BxHistory ()
{
	this._hash = ""; // current hash (after #)
	this._to = 400; // timeout to check for history change
	this._hf = null; // hidden iframe
	this._en = '';
}

/**
 * go to the specified page - override this function to handle specific actions
 * @param h		hash (#)
 */
BxHistory.prototype.go = function (h)
{
	var a = h.split('&');
	if (!a.length) return;

	if (a[0] == 'action=goto')
	{
		var aa = a[1].split('=');
		switch (aa[0])
		{
			// admin functions
			case 'edit_cats':
				if (document.orca_admin) document.orca_admin.editCategories ();
				break;

			// user functions

			case 'cat_id':
				document.f.selectForumIndex (aa[1]);
				break;
			case 'forum_id':
				document.f.selectForum (aa[1], a[2]);
				break;
			case 'topic_id':
				document.f.selectTopic (aa[1]);
				break;

			case 'new_topic':
				document.f.newTopic (aa[1]);
				break;
			case 'search':
				document.f.showSearch ();
				break;
			case 'search_result':
				document.f.search (a[2], a[3], a[4], a[5], a[6]);
				break;
			case 'my_flags':
				document.f.showMyFlags ();
				break;
			case 'my_threads':
				document.f.showMyThreads ();
				break;
			case 'profile':
				document.f.showProfile (aa[1]);
				break;
		}
	}

	return;
}

/**
 * history initialization
 * @param name		hame of history object
 */
BxHistory.prototype.init = function (name)
{
	this._en = name;

	if (isIE) this._initHiddenFrame();

	this.handleHist ();
	window.setInterval(this._en + ".handleHist()", this._to);

	return true;
}

/**
 * handle history (ontimer function)
 */
BxHistory.prototype.handleHist =  function ()
{
	if (isIE)
	{
		var id = this._hf.contentDocument || this._hf.contentWindow.document;
		var h = id.getElementById('hidfr').value;

		if ( h != window.location.hash)
		{						
			this._hash = h;
			var h = this._hash.substr(1);
			//alert ('h = ' + h + "\n" + 'window.location.hash = ' + window.location.hash);
			if (h.length)
			{ 
				this.go (h);
			}
			else if (!h.length && window.location.hash.length)
			{				
				var h = window.location.hash.charAt(0) == '#' ? window.location.hash.substr(1) : window.location.hash;
				this.pushHist (h);
				this.go (h);
			}
		}
	}
	else
	{
		if ( window.location.hash != this._hash )
		{			
			this._hash = window.location.hash;
			var h = this._hash.substr(1);			
			if (h.length) this.go (h);
		}
	}
	return true;
}

/**
 * record history
 * @param h	hash
 */
BxHistory.prototype.makeHist = function (h)
{
	if (h.charAt(0) != '#') h = '#' + h;
	
	if (window.location.hash == h) return;

	if (isIE)
	{
		var id = this._hf.contentDocument || this._hf.contentWindow.document;

		var hhh = id.getElementById('hidfr').value;		

		id.getElementById('hidfr').value = h;		

		if (h != hhh)
			this.pushHist(h);

		window.location.hash = h;
	}
	else
	{
		window.location.hash = h;
		this._hash = window.location.hash;
	}


	return true;
}

/**
 * save history : IE only
 * @param h	hash
 */
BxHistory.prototype.pushHist = function (h) 
{
	if (h.charAt(0) != '#') h = '#' + h;

	var id = this._hf.contentDocument || this._hf.contentWindow.document;

	id.write ('<input id="hidfr" value="' + h + '"/>');
	id.close();

	this._hash = window.location.hash;
}

// private -------------------------------------------

/**
 * init hidden frame : IE only
 */
BxHistory.prototype._initHiddenFrame = function ()
{

	var b = document.body;
	var i = document.createElement('iframe');
	
	i.style.display = 'none';
	i.id = 'hidfr';

	b.appendChild(i);
	
	this._hf = document.getElementById('hidfr');	

    var id = null;
    if (this._hf.contentDocument)
        id = this._hf.contentDocument
    else
    if (this._hf.contentWindow && this._hf.contentWindow.document)
	    id = this._hf.contentWindow.document;

    if (id)
    {
    	id.write ('<input id="hidfr" />');
	    id.close();
    }
}



