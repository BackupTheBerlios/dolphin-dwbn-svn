

// site_url = 'zzz.com';

// =============================================================================
// Profile_poll functions ======================================================
// =============================================================================

function add_question_bar( item, num, focus )
{
	var num = document.getElementById( num );
	var item = document.getElementById( item );

	var newdiv = document.createElement( "div" );
	newdiv.id = "d" + num.value;

	var newinput = createNamedElement( "input", "v" + num.value );
	newinput.type = "text";
	newinput.id = "v" + num.value;

	var newtext = document.createTextNode( lang_delete );

	var newlink = document.createElement( "a" );
	newlink.href="#";
	newlink.onclick = function() { del_question_bar( item, newdiv ); return false; }
	newlink.style.marginLeft = '4px';
	newlink.appendChild( newtext );

	//var newbr = document.createElement( "br" );

	num.value++;

	//item.appendChild( newbr );
	newdiv.appendChild( newinput );
	newdiv.appendChild( newlink );

	item.appendChild( newdiv );

	if ( focus ) newinput.focus();
}

function del_question_bar( parent, child )
{
	parent.removeChild( child );
	//document.getElementById( parent ).removeChild( child );
}

function poll_status_show( id, item, status, status_change_to )
{
	var cont = document.getElementById( item );
	cont.innerHTML = '';
	
	var newtext = document.createTextNode( status );
	cont.appendChild( newtext );

	newtext = document.createTextNode( ' / ' );
	cont.appendChild( newtext );
	
	newtext = document.createTextNode( status_change_to );
	var newlink = document.createElement( "a" );
	newlink.href="#";
	newlink.onclick = function() { send_data( '', 'status', '&param=' + status_change_to, id ); poll_status_show( id, item, status_change_to, status ); return false; }
	newlink.appendChild( newtext );
	cont.appendChild( newlink );
	
	newtext = document.createTextNode( ' / ' );
	cont.appendChild( newtext );
}

function createNamedElement( type, name )
{
	var element;

	try
	{
		element = document.createElement('<'+type+' name="'+name+'">');
	}
	catch (e) { }

	if (!element || !element.name) // Cool, this is not IE !!
	{
		element = document.createElement(type)
		element.name = name;
	}

	return element;
}


// =============================================================================
// End of Profile_poll functions ===============================================
// =============================================================================
	

// =============================================================================
// Server interact part ========================================================
// =============================================================================

function send_data( container, action, param, id )
{
	//prompt( 'a','container='+container+'; action='+action+'; param='+param+'; id='+id )
	var ID = id;

	if ( container )
	{
		var container = document.getElementById( container );
		container.innerHTML = lang_loading;
	}

	var XMLHttpRequestObject = false;

	if ( window.XMLHttpRequest )
		XMLHttpRequestObject = new XMLHttpRequest();
	else if ( window.ActiveXObject )
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");

	if( XMLHttpRequestObject )
	{
		var data_source = site_url + 'dpol.php?action=' + action + '&ID=' + ID + param;
		XMLHttpRequestObject.open( "GET", data_source );
		XMLHttpRequestObject.onreadystatechange = function()
		{
			if ( XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200 )
			{
				var xmlDocument = XMLHttpRequestObject.responseXML;

				if ( 'delete' == action )
				{
					alert(lang_delete_message);
				}
				else if ( 'vote' == action )
				{
					container.innerHTML = '';
					document.getElementById('dpol_actions_'+ID).innerHTML = '';

					answers_points = xmlDocument.getElementsByTagName("answer_point");
					answers_num    = xmlDocument.getElementsByTagName("answer_num");
					answers_names  = xmlDocument.getElementsByTagName("answer_name");

					list_results();
				}
				else if ( 'questions' == action )
				{
					container.innerHTML = '';
					
					answers = xmlDocument.getElementsByTagName("answer");
					list_answers();
					question = xmlDocument.getElementsByTagName("question");
					list_question( "dpol_caption_" + ID );
				}

				//container.innerHTML = XMLHttpRequestObject.responseText;
				delete XMLHttpRequestObject;
				XMLHttpRequestObject = null;
			}
		}

		XMLHttpRequestObject.send( null );
	}


	function scrollers_display()
	{
		if ( ( container.offsetTop + container.offsetHeight ) < container.parentNode.offsetHeight )
		{
			document.getElementById( 'dpol_arr_up_' + ID ).style.display='none';
			document.getElementById( 'dpol_arr_down_' + ID ).style.display='none';
		}
		else
		{
			document.getElementById( 'dpol_arr_up_' + ID ).style.display='block';
			document.getElementById( 'dpol_arr_down_' + ID ).style.display='block';
		}
	}

	function list_answers()
	{
		var loopIndex;

		var newinput = document.createElement( "input" );
		newinput.type = "hidden";
		newinput.id = "current_vote_" + ID;
		newinput.value = '';

		container.appendChild( newinput );

		for ( loopIndex = 0; loopIndex < answers.length; loopIndex++ )
		{
			var newtext = document.createTextNode( answers[loopIndex].firstChild.data );		    

			var newdiv = document.createElement( "div" );
			//newdiv.setAttribute("style", "position:absolute;top:0px;white-space:nowrap;" );
			newdiv.style.position = "absolute";
			newdiv.style.top = "0px";
			newdiv.style.whiteSpace = "nowrap";

			newdiv.setAttribute("id", 'q_' + ID + '_' + loopIndex );
			//newdiv.setAttribute("onmouseover", "javascript: scroll_start(this,'horizontal');" );		    
			//newdiv.setAttribute("onmouseout", "javascript: scroll_stop();" );
			newdiv.onmouseover = function(){ scroll_start(this,'horizontal'); };
			newdiv.onmouseout = function(){ scroll_stop(); };		    

			newdiv.appendChild( newtext );

			var newdiv2 = document.createElement( "div" );
			//newdiv2.setAttribute("style", "position:absolute;left:25px;top:0px;width:100%;height:100%;overflow:hidden;border: solid 0px #000000;" );
			newdiv2.style.position = "absolute";
			newdiv2.style.left = "25px";		    
			newdiv2.style.top = "0px";
			newdiv2.style.width = "100%";
			newdiv2.style.height = "100%";
			newdiv2.style.overflow = "hidden";

			newdiv2.appendChild( newdiv );


			var newdiv3 = document.createElement( "div" );
			//newdiv3.setAttribute("style", "position:relative;height:20px;" );
			newdiv3.style.position = "relative";
			newdiv3.style.height = "20px";		    

			newinput = createNamedElement( "input", "vote_" + ID );
			newinput.type = "radio";
			//newinput.name = "vote";
			newinput.value = loopIndex;
			//newinput.setAttribute( "onclick", "javascript: set_vote( 'current_vote', this.value );");   
			newinput.onclick = function(){ set_vote( 'current_vote_' + ID, this.value ); };   

			newdiv3.appendChild( newinput );
			newdiv3.appendChild( newdiv2 );		    

			container.appendChild( newdiv3 );
		}

		scrollers_display();
	}


	function list_question( cont )
	{
		var cont = document.getElementById( cont );

		var newdiv = document.createElement( "div" );
		newdiv.id = "dpol_caption_text_" + ID;
		newdiv.style.position = "absolute";
		newdiv.style.whiteSpace = "nowrap";
		newdiv.onmouseover = function() { scroll_start(this,'horizontal'); };
		newdiv.onmouseout = function() { scroll_stop(); };		    

		var newtext = document.createTextNode( ' ' + question[0].firstChild.data + ' ' );

		//var newlink = document.createElement( "a" );
		//newlink.href = "#";

		//newlink.appendChild( newtext );
		//newdiv.appendChild( newlink );
		newdiv.appendChild( newtext );
		cont.appendChild( newdiv );
	}


	function list_results()
	{
		var loopIndex;

		for ( loopIndex = 0; loopIndex < answers_points.length; loopIndex++ )
		{
			draw_bar( answers_points[loopIndex].firstChild.data, answers_names[loopIndex].firstChild.data + ' ( ' + answers_num[loopIndex].firstChild.data + ' votes ): ', loopIndex );
		}

		scrollers_display();
	}


	function draw_bar( num, comment, id )
	{
		//container.innerHTML = container.innerHTML + '<div>' + comment + '</div><div id="' + num + '" onclick="alert(\'zzz\');" style="width:10px;background-color:#00FF00;">' + num + '%</div>';

		/*
		var newdiv = document.createElement( "div" );
		var newtext = document.createTextNode( comment );
		newdiv.appendChild( newtext );
		container.appendChild( newdiv );
		*/		

		var newtext = document.createTextNode( comment );

		var newdiv = document.createElement( "div" );
		newdiv.style.position = "absolute";
		newdiv.style.top = "0px";
		newdiv.style.whiteSpace = "nowrap";

		newdiv.setAttribute("id", 'r_' + ID + "_" + id );
		newdiv.onmouseover = function(){ scroll_start(this,'horizontal'); };
		newdiv.onmouseout = function(){ scroll_stop(); };		    

		newdiv.appendChild( newtext );

		var newdiv2 = document.createElement( "div" );
		newdiv2.style.position = "absolute";
		newdiv2.style.left = "2px";		    
		newdiv2.style.top = "0px";
		newdiv2.style.width = "100%";
		newdiv2.style.height = "100%";
		newdiv2.style.overflow = "hidden";

		newdiv2.appendChild( newdiv );

		var newdiv3 = document.createElement( "div" );
		newdiv3.style.position = "relative";
		newdiv3.style.height = "15px";		    

		newdiv3.appendChild( newdiv2 );

		var newdiv4 = document.createElement( "div" );
		newdiv4.setAttribute("id", 'p_' + ID + '_' + id );

		newdiv4.style.width = "10px";
		newdiv4.style.marginBottom = "10px";

		if ( "string" != typeof(dpoll_progress_bar_color) )
			dpoll_progress_bar_color = '#D7E4E5';

		newdiv4.style.backgroundColor = dpoll_progress_bar_color;

		newtext = document.createTextNode( num + '%' );
		newdiv4.appendChild( newtext );
		container.appendChild( newdiv3 );
		container.appendChild( newdiv4 );		

		penis_enlagment( 'p_' + ID + '_' + id, num );
	}
}

function penis_enlagment( item, size )
{
	var penis = document.getElementById( item );
	var width_lim = Math.floor( size * (penis.parentNode.offsetWidth / 100) );

	if ( width_lim > penis.offsetWidth )
	{
		penis.style.width = penis.offsetWidth + 2 + 'px';
		setTimeout( "penis_enlagment('"+item+"',"+size+")", 50 );
	}
}


// =============================================================================
// End of Server interact part =================================================
// =============================================================================

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// =============================================================================
// Local part ==================================================================
// =============================================================================


    function createNamedElement( type, name ) 
    {
    
        var element;
	
	try 
	{
	    element = document.createElement('<'+type+' name="'+name+'">');
	} catch (e) { }
	
	if (!element || !element.name) // Cool, this is not IE !!
	{ 
	    element = document.createElement(type)
	    element.name = name;
	}
	
	return element;
    }



    function move_left()
    {
	if (c_item.offsetLeft + c_middle > 0)
	{
	    c_item.style.left = (c_item.offsetLeft-1) + 'px';
	}
	else
	{
	    c_item.style.left = '0px';	
	}
	    
    }

/*
    function move_updown()
    {

	if ( 'up' == c_item_direction && (c_item.offsetTop + c_item.offsetHeight) <= c_item.parentNode.offsetHeight )
	{//alert("down" + c_item.style.top + ' ' + c_item.offsetTop);
	    c_item_direction = 'down';
	}
	else if ( 'down' == c_item_direction && c_item.offsetTop >= 0 )
	{//alert("up; " + c_item.style.top + ' ' + c_item.offsetTop);
	    c_item_direction = 'up';
	}


	if ( 'up' == c_item_direction )
	{
	    c_item.style.top = (c_item.offsetTop-2) + 'px';
	}
	else if ( 'down' == c_item_direction )
	{//alert(c_item.offsetTop+1);
	    c_item.style.top = (c_item.offsetTop+2) + 'px';
	}
		    
    }
*/


function move_up()
{
	if ( (c_item.offsetTop + c_item.offsetHeight) > c_item.parentNode.offsetHeight )
	{
		c_item.style.top = (c_item.offsetTop-2) + 'px';
	}
}



function move_down()
{
	if ( c_item.offsetTop < 0 )
	{
		c_item.style.top = (c_item.offsetTop+2) + 'px';
	}	
}



function scroll_start( item, dir )
{
	c_item = item;
	//	alert(c_item.id);

	if ( 'horizontal' == dir )
	{

		if ( c_item.offsetWidth <= c_item.parentNode.offsetWidth )
		return false;

		//if ( c_item.offsetWidth <= (c_item.parentNode.offsetWidth * 2) )
		if ( 1 != double_sized_items[c_item.id] )
		{
			c_item.innerHTML = c_item.innerHTML + "  " +  c_item.innerHTML;
			double_sized_items[c_item.id] = 1;
		}
		
		c_middle = c_item.offsetWidth / 2;	
		scroll_stop();
		iter = window.setInterval( 'move_left()', 20 );
	}

	if ( 'up' == dir )
	{
		scroll_stop();
		iter = window.setInterval( 'move_up()', 20 );
	}

	if ( 'down' == dir )
	{
		scroll_stop();
		iter = window.setInterval( 'move_down()', 20 );
	}
}


function scroll_stop()
{
	if ( undefined != window.iter )
	    window.clearInterval(iter);
}

function set_vote( item, val )
{
	document.getElementById( item ).value = val;
}



// array with elements witch we increased to scroll
    double_sized_items = new Array();


//    onclick="JavaScript:
//		document.body.style.cursor = 'Wait';
//		SendVote(6591,false);
//		void(0);


// =============================================================================
// End of local part ===========================================================
// =============================================================================
				
