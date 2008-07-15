addEvent( window, 'load', initMenu );

function initMenu( e )
{
	if( typeof( sNewItemTitle ) == 'undefined' )
		sNewItemTitle = 'NEW ITEM';
	
	oMenu = new BxDolMenu( topParentID, parserUrl, aTopItems, aCustomItems, aSystemItems, aAllItems, aCoords, e )
}

function createNewItem( type, source )
{
	if( source )
		var urlSource = '&source=' + source;
	else
		var urlSource = '&source=0';
	
	var objXmlHttp = createXmlHttpObj();
	if( !objXmlHttp )
		return false;
	
	var url = parserUrl + '&action=create_item&type=' + type + urlSource;
	url += '&r=' + Math.random();
	var iNewID = 0;
	
	
	var handler = function()
	{
			//alert( objXmlHttp.responseText );
			iNewID = parseInt( objXmlHttp.responseText );
	}
	
	objXmlHttp.open( "GET", url, false );
	
	if( window.ActiveXObject )
	{
		objXmlHttp.onreadystatechange = function()
		{
			if ( objXmlHttp.readyState == 4 && objXmlHttp.status == 200 )
			{
				handler();
			}
		}
	}
	else if( window.XMLHttpRequest )
		objXmlHttp.onload = handler;
	
	objXmlHttp.send( null );
	
	return iNewID;
}


function deactivateItem( id )
{
	var objXmlHttp = createXmlHttpObj();
	if( !objXmlHttp )
		return false;
	
	var url = parserUrl + '&action=deactivate_item&id=' + id;
	url += '&r=' + Math.random();
	
	objXmlHttp.open( "GET", url );
	objXmlHttp.onreadystatechange = function()
	{
		if ( objXmlHttp.readyState == 4 && objXmlHttp.status == 200 )
		{
			//alert( objXmlHttp.responseText );
		}
	}
	objXmlHttp.send( null );
}

function showItemEditForm( id )
{
	var editFormWrap = document.getElementById( 'edit_form_wrapper' );
	
	editFormWrap.style.width   = document.body.clientWidth + 30 + "px";
	editFormWrap.style.height  = (window.innerHeight ? (window.innerHeight + 30) : screen.height) + "px";			
	editFormWrap.style.left    = getHorizScroll() - 30 + "px";
	editFormWrap.style.top     = getVertScroll() - 30 + "px";
	editFormWrap.style.display = 'block';
	
	getHtmlData( 'edit_form_cont', parserUrl + '&action=edit_form&id=' + id );
}

function getHorizScroll()
{
	if (navigator.appName == "Microsoft Internet Explorer")
		return document.documentElement.scrollLeft;
	else
		return window.pageXOffset;
}

function getVertScroll()
{
	if (navigator.appName == "Microsoft Internet Explorer")
		return document.documentElement.scrollTop;
	else
		return window.pageYOffset;
}


function hideEditForm()
{
	editFormWrap = document.getElementById( 'edit_form_wrapper' );
	editFormWrap.style.display = 'none';
}

function saveItem( id )
{
	_form = document.forms.formItemEdit;
	if( !_form )
		return false;
	
	if( _form.Caption )
	{
		if( !_form.Caption.value.length )
		{
			alert( 'Please enter Language Key' );
			_form.Caption.focus();
			return false;
		}
	}
	
	if( _form.LangCaption )
	{
		if( !_form.LangCaption.value.length )
		{
			alert( 'Please enter Default Name' );
			_form.LangCaption.focus();
			return false;
		}
	}
	
	var sRequest = '';
	for( ind = 0; ind < _form.elements.length; ind ++ )
	{
		var _el = _form.elements[ind];
		switch( _el.type )
		{
			case 'text':
			case 'textarea':
			case 'select-one':
				sRequest += '&' + _el.name + '=' + encodeURIComponent( _el.value );
		}
	}
	
	if( _form.Target )
	{
		for( i = 0; i < _form.Target.length; i++ )
			if( _form.Target[i].checked )
				sTarget = _form.Target[i].value;
	}
	else
		sTarget = '';
	
	var sVisible_non  = ( ( _form.Visible_non  && _form.Visible_non.checked  ) ? '1' : '0' );
	var sVisible_memb = ( ( _form.Visible_memb && _form.Visible_memb.checked ) ? '1' : '0' );
	
	var sRequestUrl = parserUrl + '&action=save_item&id=' + id + sRequest +
		'&Target=' + sTarget +
		'&Visible_non=' + sVisible_non +
		'&Visible_memb=' + sVisible_memb;
	
	getHtmlData( 'edit_form_cont', sRequestUrl );
}


function saveItemByPost( id )
{
	_form = document.forms.formItemEdit;
	var oXMLHttpReq = createXmlHttpObj();
	var elemCont = document.getElementById( 'edit_form_cont' );
	
	if( !_form )
		return false;
	
	if( !oXMLHttpReq )
		return false;
	
	if( !elemCont )
		return false;
	
	if( _form.Caption )
	{
		if( !_form.Caption.value.length )
		{
			alert( 'Please enter Language Key' );
			_form.Caption.focus();
			return false;
		}
	}
	
	if( _form.LangCaption )
	{
		if( !_form.LangCaption.value.length )
		{
			alert( 'Please enter Default Name' );
			_form.LangCaption.focus();
			return false;
		}
	}
	
	var sRequest = '';
	
	for( ind = 0; ind < _form.elements.length; ind ++ )
	{
		var _el = _form.elements[ind];
		switch( _el.type )
		{
			case 'text':
			case 'textarea':
			case 'select-one':
				sRequest += '&' + _el.name + '=' + encodeURIComponent( _el.value );
		}
	}
	
	if( _form.Target )
	{
		for( i = 0; i < _form.Target.length; i++ )
			if( _form.Target[i].checked )
				sTarget = _form.Target[i].value;
	}
	else
		sTarget = '';
	
	var sVisible_non  = ( ( _form.Visible_non  && _form.Visible_non.checked  ) ? '1' : '0' );
	var sVisible_memb = ( ( _form.Visible_memb && _form.Visible_memb.checked ) ? '1' : '0' );
	
	var sRequestUrl = 'action=save_item&id=' + id + sRequest +
		'&Target=' + sTarget +
		'&Visible_non=' + sVisible_non +
		'&Visible_memb=' + sVisible_memb;
	
	
	elemCont.innerHTML = '<div class="loading"><img src="'+urlIconLoading+'"></div>';
	
	oXMLHttpReq.open("POST", parserUrl + '&r=' + Math.random() );
	oXMLHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	
	oXMLHttpReq.onreadystatechange = function() 
	{
		if (oXMLHttpReq.readyState == 4 && oXMLHttpReq.status == 200) {
			sNewText = oXMLHttpReq.responseText;
			elemCont.innerHTML = sNewText;
			
			// parse javascripts and run them
			aScrMatches = sNewText.match(/<script[^>]*javascript[^>]*>([^<]*)<\/script>/ig);
			if( aScrMatches )
			{
				for( ind = 0; ind < aScrMatches.length; ind ++ )
				{
					sScr = aScrMatches[ind];
					iOffset = sScr.match(/<script[^>]*javascript[^>]*>/i)[0].length;
					sScript = sScr.substring( iOffset, sScr.length - 9 );
					
					eval( sScript );
				}
			}
		}
	}
	
	oXMLHttpReq.send( sRequestUrl );
}

function updateItem( id, title )
{
	oMenu.updateItem( id, title );
}

function deleteItem( id )
{
	if( confirm( 'Are you sure want delete this item?' ) )
	{
		var objXmlHttp = createXmlHttpObj();
		if( !objXmlHttp )
			return false;
		
		var url = parserUrl + '&action=delete_item&id=' + id;
		url += '&r=' + Math.random();
		
		objXmlHttp.open( "GET", url );
		objXmlHttp.onreadystatechange = function()
		{
			if ( objXmlHttp.readyState == 4 && objXmlHttp.status == 200 )
			{
				if( objXmlHttp.responseText == 'OK' )
				{
					oMenu.deleteItem( id );
					hideEditForm();
				}
				else
				{
					alert( objXmlHttp.responseText );
				}
			}
		}
		objXmlHttp.send( null );
		return true;
	}
	else
		return false;
}

function saveItemsOrders( sTopItems, aCustomItems )
{
	var objXmlHttp = createXmlHttpObj();
	if( !objXmlHttp )
		return false;
	
	var url = parserUrl + '&action=save_orders&top=' + sTopItems;
	
	for( id in aCustomItems )
	{
		var sCustomStr = aCustomItems[id];
		if( sCustomStr.length == 0)
			continue;
		
		url += '&custom[' + id + ']=' + sCustomStr;
	}
	
	url += '&r=' + Math.random();
	
	objXmlHttp.open( "GET", url );
	objXmlHttp.onreadystatechange = function()
	{
		if ( objXmlHttp.readyState == 4 && objXmlHttp.status == 200 )
		{
			if( objXmlHttp.responseText != 'OK' )
				alert( objXmlHttp.responseText );
		}
	}
	objXmlHttp.send( null );
}

function resetItems()
{
	if( confirm( 'Reset Will Restore The Builder To Factory Settings :). Are You Sure?' ) )
		location = parserUrl + '&action=reset';
}