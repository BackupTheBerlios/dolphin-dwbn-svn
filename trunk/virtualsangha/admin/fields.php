<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );


$logged['admin'] = member_auth( 1, true, true );

$_page['css_name'] = 'fields.css';

$_page['header'] = 'Fields builder';

$_page['extraCodeInHead'] = <<<EOJ
<script type="text/javascript" src="{$site['plugins']}jquery/jquery.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/ui.tabs.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/jquery.dimensions.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/ui.mouse.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/ui.draggable.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/ui.draggable.ext.js"></script>
<script type="text/javascript" src="{$site['plugins']}jquery/ui.sortable.js"></script>
<script type="text/javascript" src="fields.js"></script>
<script type="text/javascript">
	oPFM = new BxDolPFM();
	
	oPFM.config.areas  = 11;
	oPFM.config.parserUrl = 'fields.parse.php';
	
	// run the script
	$(document).ready(function() {
		$('#tabs_switcher').tabs();
		$('#mode_switcher_1').tabs();
		$('#mode_switcher_2').tabs();
		$('#mode_switcher_3').tabs();
		oPFM.init();
	});
</script>
EOJ;

$_page['extraCodeInBody'] = <<<EOJ
	<div id="fieldFormWrap" style="display:none;"
	  onclick="e = event; t = ( e.target || e.srcElement ); if ( t.id == this.id ) hideEditForm();">
		<div id="edit_form_cont"></div>
	</div>
EOJ;

TopCodeAdmin();
?>
	<div id="main_container">
		<div id="tabs_container">
        	<ul id="tabs_switcher">
                <li><a href="#m1"        ><img src="images/join.png"   />Join Form       </a></li>
                <li><a href="#edit_tab"  ><img src="images/edit.png"   />Edit Profile    </a></li>
                <li><a href="#view_tab"  ><img src="images/view.png"   />View Profile    </a></li>
                <li><a href="#search_tab"><img src="images/search.png" />Search Profiles </a></li>
         	</ul>
            
            <div id="m1">
				<div class="build_container">Loading...</div>
            </div>
            <div id="edit_tab">
                <ul id="mode_switcher_1">
                    <li><a href="#m2">Owner     </a></li>
                    <li><a href="#m3">Admin     </a></li>
                    <li><a href="#m4">Moderator </a></li>
                </ul>
                
                <div id="m2">
                    <div class="build_container"></div>
                </div>
                <div id="m3">
                    <div class="build_container"></div>
                </div>
                <div id="m4">
                    <div class="build_container"></div>
                </div>
            </div>
            <div id="view_tab">
                <ul id="mode_switcher_2">
                    <li><a href="#m5">Admin     </a></li>
                    <li><a href="#m6">Member    </a></li>
                    <li><a href="#m7">Moderator </a></li>
                    <li><a href="#m8">Visitor   </a></li>
                </ul>

                <div id="m5">
                    <div class="build_container"></div>
                </div>
                <div id="m6">
                    <div class="build_container"></div>
                </div>
                <div id="m7">
                    <div class="build_container"></div>
                </div>
                <div id="m8">
                    <div class="build_container"></div>
                </div>
            </div>
            <div id="search_tab">
                <ul id="mode_switcher_3">
                    <li><a href="#m9">Simple     </a></li>
                    <li><a href="#m10">Quick    </a></li>
                    <li><a href="#m11">Advanced </a></li>
                </ul>

                <div id="m9">
                    <div class="build_container"></div>
                </div>
                <div id="m10">
                    <div class="build_container"></div>
                </div>
                <div id="m11">
                    <div class="build_container"></div>
                </div>
            </div>
            
        </div>
    </div>

<?
BottomCode();
