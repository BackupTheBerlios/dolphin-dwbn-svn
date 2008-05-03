/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


/* Define before run following vars with your values:
var sForm = 'group_invite_form'; - name of form which contain target SELECTs
var sFrom = 'friends'; - name of SELECT which contain members for throwing
var sTo   = 'invites'; - name of SELECT which will contian thrown members */

function throwMembersFromTo()
{
	if( !document[sForm] )
		return false;
	
	eFrom = document[sForm][sFrom];
	eTo   = document[sForm][sTo];
	
	if( !eFrom || !eTo )
		return false;
	
	for( ind = 0; ind < eFrom.length; ind ++ )
	{
		if( eFrom[ind].selected )
		{
			alreadyAdded = false;
			
			for( ind2=0; ind2 < eTo.length; ind2++ )
				if( eTo[ind2].value == eFrom[ind].value )
					alreadyAdded = true;
			
			if( !alreadyAdded )
			{
				newOption = new Option( eFrom[ind].text, eFrom[ind].value );
				eTo.options[eTo.length] = newOption;
			}
		}
	}
}

function unthrowMembersFromTo()
{
	if( !document[sForm] )
		return false;
	
	eTo = document[sForm][sTo];
	
	if( !eTo )
		return false;
	
	for( ind = (eTo.length - 1); ind >=0 ; ind -- )
	{
		if( eTo[ind].selected )
			eTo.remove( ind );
	}
}

function findMoreMembers()
{
	window.open( site_url + 'qsearch.php?handler=addMemberTo', 'qsearch', 'width='+iQSearchWindowWidth+',height='+iQSearchWindowHeight+',scrollbars=yes' );
}

function addMemberTo( ID, NickName )
{
	if( !document[sForm] )
		return false;
	
	eTo = document[sForm][sTo];
	
	if( !eTo )
		return false;
	
	alreadyAdded = false;
	
	for( ind2=0; ind2 < eTo.length; ind2++ )
		if( eTo[ind2].value == ID )
			alreadyAdded = true;
	
	if( !alreadyAdded )
	{
		newOption = new Option( NickName, ID );
		eTo.options[eTo.length] = newOption;
	}
	
}

function checkThrowerForm()
{
	if( !document[sForm] )
		return false;
	
	eTo = document[sForm][sTo];
	
	if( !eTo )
		return false;
	
	if( !eTo.length )
	{
		alert( lang_you_should_specify_member );
		return false;
	}
	
	for( ind = 0; ind < eTo.length; ind ++ )
		eTo[ind].selected = true;
	
	return true;
}
