 
$.fn.bxdolcmtanim = function(action, effect, speed, h) 
{    
   return this.each(function() 
   {   		
   		var sFunc = '';
   		var sEval;

   		if (0 == speed)
   			effect = 'default';
   			
  		switch (action)
  		{
  			case 'show':
  				switch (effect)
  				{
  					case 'slide': sFunc = 'slideDown'; break;
  					case 'fade': sFunc = 'fadeIn'; break;
  					default: sFunc = 'show';
  				}  				
  				break;
  			case 'hide':
  				switch (effect)
  				{
  					case 'slide': sFunc = 'slideUp'; break;
  					case 'fade': sFunc = 'fadeOut'; break;
  					default: sFunc = 'hide';
  				}  				
  				break;  				
  			default:
  			case 'toggle':
  				switch (effect)
  				{
  					case 'slide': sFunc = 'slideToggle'; break;
  					case 'fade': sFunc = ($(this).filter(':visible').length) ? 'fadeOut' : 'fadeIn'; break;
  					default: sFunc = 'toggle';
  				}  				  			  				
  		}
  		  		
  		
  		if ((0 == speed || undefined == speed) && undefined == h)
  		{
  			sEval = '$(this).' + sFunc + '();';
  		}
  		else
  		if ((0 == speed || undefined == speed) && undefined != h)
  		{
  			sEval = '$(this).' + sFunc + '(); $(this).each(h);';
  		}
  		else  		
  		{
  			sEval = '$(this).' + sFunc + "('" + speed + "', h);";
  		}
  		
  		eval (sEval);
  		
  		return this;
   });  
};


function BxDolCmts (options)
{	
	//sObjName, sBaseUrl, sSystem, iObjId, sDefaultErrMsg, sConfirmMsg, isEditAllowed, isRemoveAllowed, iSecsToEdit
	
	this.oCmtElements = {}; // form elements 
	this._sObjName = undefined == options.sObjName ? 'oCmts' : options.sObjName;	// javascript object name, to run current object instance from onTimer
	this._sSystem = options.sSystem; // current comment system
	this._iObjId = options.iObjId; // this object id comments
    this._sActionsUrl = options.sBaseUrl + 'cmts.php'; // actions url address
    this._sDefaultErrMsg = undefined == options.sDefaultErrMsg ? 'Errod Occured' : ''; // default error message
    this._sConfirmMsg = undefined == options.sConfirmMsg ? 'Are you sure?' : options.sConfirmMsg; // confirm message
    
    this._isEditAllowed = parseInt(undefined == options.isEditAllowed ? 0 : options.isEditAllowed); // is edit allowed
    this._isRemoveAllowed = parseInt(undefined == options.isRemoveAllowed ? 0 : options.isRemoveAllowed); // is remove allowed
    this._iSecsToEdit = parseInt(undefined == options.iSecsToEdit ? 0 : options.iSecsToEdit); // number of seconds to allow edit comment
    
    this._oSavedTexts = {};
    
    this._sAnimationEffect = undefined == options.sAnimationEffect ? 'slide' : options.sAnimationEffect;
    this._iAnimationSpeed = undefined == options.iAnimationSpeed ? 'slow' : options.iAnimationSpeed;

    // init post comment form (because browser remeber last inputs, we need to clear it)
    if ($('#cmts-box-' + this._iObjId + ' > .cmt-post-reply > form').length)
    {
    	$('#cmts-box-' + this._iObjId + ' > .cmt-post-reply > form')[0].reset();
    	$('#cmts-box-' + this._iObjId + ' > .cmt-post-reply > form > [name=CmtParent]').val(0);    
    }

    // clicks handler for ratings
    var $this = this; 
    $('#cmts-box-' + this._iObjId).click (function (event) 
    {    	
    	var iRate = 0;
    	if ($(event.target).filter('.cmt-pos').length)
    	{
    		iRate = 1;
    		event.preventDefault();
    	}
    	else
    	if ($(event.target).filter('.cmt-neg').length)
    	{
    		iRate = -1;
    		event.preventDefault();
    	}
    	else
    	if ($(event.target).filter('.cmt-hid').length)    	
    	{
			$this._toggleHidden(event.target, parseInt(event.target.id.substr(8)));
			event.preventDefault();
    	}
    			
    	if (0 != iRate && !$(event.target).parent().filter('.cmt-rate-disabled').length)
    	{    		            		
			var e = $(event.target).parent().children().filter('span').get(0);
			$this._rateComment(e, parseInt(event.target.id.substr(8)), iRate);
    	}
    });
}

BxDolCmts.prototype.showMore = function (e, iPerView)
{	
	$('#cmts-box-' + this._iObjId + ' > ul > .cmt:hidden:lt('+iPerView+')').bxdolcmtanim('show', this._sAnimationEffect, this._iAnimationSpeed);
	
	var n = $('#cmts-box-' + this._iObjId + ' > ul > .cmt:hidden').length;
	
	if (n == 0)
	{
		$('#cmts-box-' + this._iObjId + ' > .cmt-show-more').remove();
	}
	else
	{
		var iStart = $('#cmts-box-' + this._iObjId + ' > ul > .cmt:visible').length + 1;
		if (n > (iPerView-1))
		{			
			$('#cmts-box-' + this._iObjId + ' > .cmt-show-more b').html(iStart);
			$('#cmts-box-' + this._iObjId + ' > .cmt-show-more u').html(iStart + iPerView - 1);
		}
		else
		{
			$('#cmts-box-' + this._iObjId + ' > .cmt-show-more b').html(iStart);
			$('#cmts-box-' + this._iObjId + ' > .cmt-show-more u').html(iStart + n - 1);
		}
	}
}

// show hide post reply form
// if there is no reply form it gets it and set CmtParent form input
BxDolCmts.prototype.toggleReply = function (e, iCmtParentId)
{				
	var h = function () {
		if ($(this).filter(':visible').length)
			$(this).parent().addClass('cmt-post-reply-expanded');
		else
			$(this).parent().removeClass('cmt-post-reply-expanded');
	}
	
	if (0 == iCmtParentId && $(e).parent().next('form').length)
	{						
		$(e).parent().next('form').bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed, h);
	}
	else if (0 != iCmtParentId) 
	{
		if ($(e).next('form').length)
			$(e).next('form').bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed, h);
		else
			$(e).after($('#cmts-box-' + this._iObjId + ' > .cmt-post-reply > form').clone().show().hide()).next('form').bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed, h).children().filter('[name=CmtParent]').val(iCmtParentId);
	}
}

// show/hide comment replies
BxDolCmts.prototype.toggleCmts = function (e, iCmtParentId)
{
    var sId = '#cmt'+iCmtParentId;
    if ($(sId+'>ul').length)
    {
        if ($(sId+'>ul:visible').length)        
        {
        	$(sId+'>ul').bxdolcmtanim('hide', this._sAnimationEffect, this._iAnimationSpeed, function () { $(sId+' > .cmt-cont .cmt-replies').removeClass('cmt-replies-hover'); $(sId+' > .cmt-cont .cmt-replies .cmt-replies-hide').hide(); $(sId+' > .cmt-cont .cmt-replies .cmt-replies-show').show(); } );
        }
        else
        {
            $(sId+'>ul').bxdolcmtanim('show', this._sAnimationEffect, this._iAnimationSpeed);
            $(sId+' > .cmt-cont > .cmt-replies').addClass('cmt-replies-hover');
            $(sId+' > .cmt-cont .cmt-replies .cmt-replies-show').hide(); 
            $(sId+' > .cmt-cont .cmt-replies .cmt-replies-hide').show();
        }
    }
    else
    {
        this._getCmts (e, iCmtParentId, function () { $(sId+' > .cmt-cont .cmt-replies').addClass('cmt-replies-hover'); $(sId+' > .cmt-cont .cmt-replies .cmt-replies-show').hide(); $(sId+' > .cmt-cont .cmt-replies .cmt-replies-hide').show(); } );
    }
}

BxDolCmts.prototype.cmtRemove = function (e, iCmtId)
{
	if (!this._confirm()) return;
	
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtRemove';
    oData['Cmt'] = iCmtId;

    this._loading (e, true);

    jQuery.get (
        this._sActionsUrl,
        oData,
        function (s) 
        {                	            
        	$this._loading (e, false);
        	
        	if (jQuery.trim(s).length)
        		alert(s);
        	else
        		$('#cmt'+iCmtId).bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function () { $(this).remove(); } );            
        }
    );
}

BxDolCmts.prototype.cmtEdit = function (e, iCmtId)
{	
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtEdit';
    oData['Cmt'] = iCmtId;    

    if ($('#cmt'+iCmtId+'>.cmt-cont>.cmt-body>form').length)
    {    	
    	$('#cmt'+iCmtId+'>.cmt-cont>.cmt-body').removeClass('cmt-post-reply-expanded').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() { $(this).html($this._oSavedTexts[iCmtId]).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed) } );
    	return;
    }
    else
    {
    	this._oSavedTexts[iCmtId] = $('#cmt'+iCmtId+'>.cmt-cont>.cmt-body').html();
    }
    	
    this._loading (e, true);
    
    jQuery.get (
        this._sActionsUrl,
        oData,
        function (s) 
        {                	            
        	$this._loading (e, false);
        	
        	if ('err' == s.substring(0,3))
        		alert (s.substring(3));
        	else
        		$('#cmt'+iCmtId+'>.cmt-cont>.cmt-body').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() { $(this).html(s).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function () { $(this).addClass('cmt-post-reply-expanded'); }) } );
        }
    );
}

// get comment replies via ajax request
BxDolCmts.prototype._getCmts = function (e, iCmtParentId, h)
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtsGet';
    oData['CmtParent'] = iCmtParentId;

    this._loading (e, true);

    jQuery.get (
        this._sActionsUrl,        
        oData,
        function (s) 
        {        
        	h();
            $('#cmt'+iCmtParentId).append($(s).filter('.cmts').addClass('cmts-margin').hide()).children().filter('.cmts').bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
            $this._loading (e, false);
        }
    );
}

// get just posted 1 comment via ajax request
BxDolCmts.prototype._getCmt = function (f, iCmtParentId, iCmtId)
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtGet';
    oData['Cmt'] = iCmtId;

    if (0 == iCmtParentId)
    	$('#cmts-box-' + this._iObjId + '>.cmt-post-reply').hide();
    	
    var eUl = $('#cmts-box-' + $this._iObjId + '>ul').get();
    this._loading (eUl, true);

    jQuery.get (
        this._sActionsUrl,        
        oData,
        function (s) 
        {   
        	$this._loading (eUl, false);             	
        	if (0 == iCmtParentId)
        	{            	            	
        		$('#cmts-box-' + $this._iObjId + '>ul>.cmt-no').remove();
        		
        		if ($('#cmts-box-' + $this._iObjId + '>ul>li.cmt:last').length)
            		$('#cmts-box-' + $this._iObjId + '>ul>li.cmt:last').after(s);
            	else
            		$('#cmts-box-' + $this._iObjId + '>ul').html(s);
        	}
        	else
        	{        		        		
        		// there was no comments and we added new 
        		if ($('#cmt' + iCmtParentId + ' > .cmt-cont > .cmt-post-reply-to').length) 
        		{        			
        			$('#cmt' + iCmtParentId + ' > .cmt-cont > .cmt-post-reply-to').replaceWith($(s).addClass('cmts-margin'));
        		}
        		// there was some comments and we added another one
        		else
        		{       
        			$('#cmt' + iCmtParentId + ' > .cmts > .cmt-reply-to').remove();
        			$('#cmt' + iCmtParentId + '>ul>li:last').after(s);
        		}
        	}
        	$this._runCountdown(iCmtId);
        }
    );
}

// submit comment and show it after posting
BxDolCmts.prototype.submitComment = function (f)
{
	var eSubmit = $(f).children().filter(':submit').get();
	var $this = this;
    var oData = this._getDefaultActions();
    
    $this._err(eSubmit, false); // hide any errors before submitting
    
	if (!this._getCheckElements (f, oData)) return; // get and check form elements
	
	// submit form
	oData['action'] = 'CmtPost';	
	this._loading (eSubmit, true);
    jQuery.post (
        this._sActionsUrl,        
        oData,
        function (s)
        {                	
        	$this._loading (eSubmit, false);
        	
            if (!jQuery.trim(s).length)
            	$this._err(eSubmit, true, $this._sDefaultErrMsg); // display error
            else            
            	$this._getCmt(f, oData['CmtParent'], parseInt(s)); // display just posted comment
        }
    );	
}


// update comment and show it after posting
BxDolCmts.prototype.updateComment = function (f, iCmtId)
{
	var eSubmit = $(f).children().filter(':submit').get();
	var $this = this;
    var oData = this._getDefaultActions();
    
    $this._err(eSubmit, false); // hide any errors before submitting
    
	if (!this._getCheckElements (f, oData)) return; // get and check form elements
	
	this._oSavedTexts[iCmtId] = '';
	
	// submit form
	oData['action'] = 'CmtEditSubmit';	
	oData['Cmt'] = iCmtId;	
	this._loading (eSubmit, true);
    jQuery.post (
        this._sActionsUrl,        
        oData,
        function (s)
        {                	
        	$this._loading (eSubmit, false);

            if (!jQuery.trim(s).length)
            	$this._err(eSubmit, true, $this._sDefaultErrMsg); // display error
            else            
        		$('#cmt'+iCmtId+'>.cmt-cont>.cmt-body').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function () { $(this).removeClass('cmt-post-reply-expanded').html(s).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed); } );
        }
    );	
}

// toggle hidden comment
BxDolCmts.prototype._toggleHidden = function (e, iCmtId)
{
	$('#cmt'+iCmtId+' > .cmt-cont').bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
}

// rate comment 
BxDolCmts.prototype._rateComment = function (e, iCmtId, iRate)
{
    		var $this = this;
		    var oData = this._getDefaultActions();
		    oData['action'] = 'CmtRate';
		    oData['Cmt'] = iCmtId;
		    oData['Rate'] = iRate;

		    this._loading (e, true);

		    jQuery.get (
        		this._sActionsUrl,
        		oData,
		        function (s) 
        		{                	            
		        	$this._loading (e, false);
        	
        			if (jQuery.trim(s).length)
        			{
		        		alert(s);
        			}
        			else
        			{
        				e.innerHTML = parseInt(e.innerHTML) + iRate;
        				$(e).parent().addClass('cmt-rate-disabled');
        				if (0 > iRate)
        					$('#cmt'+iCmtId+'>.cmt-cont').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed);
        			}
        		}
    		);    		
}

// check and get post new comment form elements
BxDolCmts.prototype._getCheckElements = function (f, oData)
{
	var $this = this;
	var bSuccess = true;
	// check/get form elements
	jQuery.each( $(f).children().filter(':input'), function () 
	{
		if (this.name.length && $this.oCmtElements[this.name])
		{				
			var isValid = true;
			if ($this.oCmtElements[this.name]['reg'])
			{
				try {							
					eval('var isValid = this.value.match(' + $this.oCmtElements[this.name]['reg'] + ');');
				} catch (ex) {};
			}
						
			if (!isValid)
			{
				bSuccess = false;
				$this._err(this, true, $this.oCmtElements[this.name]['msg']);				
			}
			else
			{
				$this._err(this, false);
			}
			oData[this.name] = this.value;			
		}
	});		
	
	return bSuccess;
}

// run countdown timer for just posted comments
BxDolCmts.prototype._runCountdown = function (iCmtId)
{
	if (this._isEditAllowed || this._isRemoveAllowed || 0 == this._iSecsToEdit) return;
	
	$('#cmt-jp-' + iCmtId + ' span').html(this._iSecsToEdit);			
	
	window.setTimeout(this._sObjName + '.onCountdown(' + iCmtId + ',' + this._iSecsToEdit +');', 1000);
}

BxDolCmts.prototype.onCountdown = function (iCmtId, i)
{
	var i = parseInt($('#cmt-jp-' + iCmtId + ' span').html());	
	if ( 0 == --i) 
	{
		$('#cmt-jp-' + iCmtId).remove();
		return;
	}
	else
	{
		$('#cmt-jp-' + iCmtId + ' span').html(i);
		window.setTimeout(this._sObjName + '.onCountdown(' + iCmtId + ',' + i +');', 1000);
	}
}

// show/hide loading indicator
BxDolCmts.prototype._loading = function (e, bShow)
{
    if (bShow && !$(e).next('b').length)
        $(e).after(' <b>Loading...</b>');
    else if (!bShow && $(e).next('b').length)
        $(e).next('b').remove();
}

// show/hide error message
BxDolCmts.prototype._err = function (e, bShow, s)
{
	if (bShow && !$(e).next('.cmt-err').length)
        $(e).after(' <b class="cmt-err">' + s + '</b>');
    else if (!bShow && $(e).next('.cmt-err').length)
        $(e).next('.cmt-err').remove();
}

// confirm message
BxDolCmts.prototype._confirm = function ()
{
	return confirm(this._sConfirmMsg);
}

// standart form variables
BxDolCmts.prototype._getDefaultActions = function ()
{
    return { 
        	'sys'   : this._sSystem,
        	'id'    : this._iObjId
    	};
}
