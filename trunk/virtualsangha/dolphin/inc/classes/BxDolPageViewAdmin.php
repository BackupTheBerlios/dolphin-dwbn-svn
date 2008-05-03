<?

require_once( BX_DIRECTORY_PATH_ROOT . 'plugins/Services_JSON.php' );

class BxDolPageViewAdmin {
	var $aPages = array();
	var $oPage;
	var $sPage_db; //name of current page, used form database manipulations
	var $sDBTable; //used database table
	var $bAjaxMode = false;
	var $aAliases = array(
	    'index' => 'Homepage',
	    'music' => 'Shared Music',
	    'video' => 'Shared Video',
	    'photo' => 'Shared Photo',
	    'ads'   => 'Classifieds',
	    'member' => 'Account',
	    'profile' => 'Profile'
	);
	
	
	function BxDolPageViewAdmin( $sDBTable, $sCacheFile ) {
		$this -> sDBTable = $sDBTable;
		$this -> sCacheFile = $sCacheFile;
		
		$sPage = process_pass_data( isset( $_REQUEST['Page'] ) ? trim( $_REQUEST['Page'] ) : '' );
		
		$this -> getPages();
		
		if( strlen($sPage) )
			/* @var $this->oPage BxDolPVAPage */
			$this -> oPage = new BxDolPVAPage( $sPage, $this );
		
		$this -> checkAjaxMode();
		
		if( $this -> bAjaxMode and $this -> oPage ) {
			$this -> sPage_db = addslashes( $this -> oPage -> sName );
			
			switch( $_REQUEST['action'] ) {
				case 'load':
					header( 'Content-type:text/javascript' );
					send_headers_page_changed();
					echo $this -> oPage -> getJSON();
				break;
				
				case 'saveColsWidths':
					if( is_array( $_POST['widths'] ) ) {
						$this -> saveColsWidths( $_POST['widths'] );
						$this -> createCache();
					}
				break;
				
				case 'saveBlocks':
					if( is_array( $_POST['columns'] ) ) {
						$this -> saveBlocks( $_POST['columns'] );
						$this -> createCache();
					}
				break;
				
				case 'loadEditForm':
					if( $iBlockID = (int)$_POST['id'] )
						$this -> showPropForm( $iBlockID );
				break;
				
				case 'saveItem':
					if( (int)$_POST['id'] ) {
						$this -> saveItem( $_POST );
						$this -> createCache();
					}
				break;
				
				case 'deleteBlock':
					if( $iBlockID = (int)$_REQUEST['id'] ) {
						$this -> deleteBlock( $iBlockID );
						$this -> createCache();
					}
				break;
				
				case 'checkNewBlock':
					if( $iBlockID = (int)$_REQUEST['id'] )
						$this -> checkNewBlock( $iBlockID );
				break;
				
				case 'savePageWidth':
					if( $sPageWidth = process_pass_data( $_POST['width'] ) ) {
						$this -> savePageWidth( $sPageWidth );
						$this -> createCache();
						
						if( $this -> oPage -> sName == 'index' ) {
							if( $sPageWidth == '100%' )
								setParam( 'promoWidth', '960' );
							else
								setParam( 'promoWidth', (int)$sPageWidth );
							
							ResizeAllPromos();
						}
					}
				break;
				
				case 'saveOtherPagesWidth':
					if( $sWidth = $_REQUEST['width'] ) {
						setParam( 'main_div_width', $sWidth );
						echo 'OK';
					}
				break;
				
				case 'resetPage':
					$this -> resetPage();
					$this -> createCache();
				break;
			}
			
			exit;
		} else {
			$this -> showMainPage();
		}
	}
	
	function savePageWidth( $sPageWidth ) {
		$sPageWidth = addslashes( $sPageWidth );
		$sQuery = "UPDATE `{$this -> sDBTable}` SET `PageWidth` = '$sPageWidth' WHERE `Page` = '{$this -> sPage_db}'";
		db_res( $sQuery );
		
		echo 'OK';
	}
	
	function createCache() {
		$oCacher = new BxDolPageViewCacher( $this -> sDBTable, $this -> sCacheFile );
		$oCacher -> createCache();
	}

	
	function checkNewBlock( $iBlockID ) {
		$sQuery = "SELECT `Desc`, `Caption`, `Func`, `Content`, `Visible` FROM `{$this -> sDBTable}` WHERE `ID` = $iBlockID";
		$aBlock = db_assoc_arr( $sQuery );
		
		if( $aBlock['Func'] == 'Sample' ) {
			$sQuery = "
				INSERT INTO `{$this -> sDBTable}` SET
					`Desc`    = '" . addslashes( $aBlock['Desc']    ) . "',
					`Caption` = '" . addslashes( $aBlock['Caption'] ) . "',
					`Func`    = '{$aBlock['Content']}',
					`Visible` = '{$aBlock['Visible']}',
					`Page`    = '{$this -> sPage_db}'
				";
			db_res( $sQuery );
			
			echo mysql_insert_id();
			
			$this -> createCache();
		}
	}
	
	function deleteBlock( $iBlockID ) {
		$sQuery = "DELETE FROM `{$this -> sDBTable}` WHERE `Page` = '{$this -> sPage_db}' AND `ID` = $iBlockID";
		db_res( $sQuery );
	}
	
	function resetPage() {
		if( $this -> oPage -> bResetable ) {
			$sQuery = "DELETE FROM `{$this -> sDBTable}` WHERE `Page` = '{$this -> sPage_db}'";
			db_res($sQuery);
			execSqlFile( $this -> oPage -> sDefaultSqlFile );
			
			if( $this -> oPage -> sName == 'index' ) {
				setParam( 'promoWidth', '960' );
				ResizeAllPromos();
			}
		}
		
		echo (int)$this -> oPage -> bResetable;
	}
	
	function saveItem( $aData ) {
		$iID = (int)$aData['id'];
		
		$sQuery = "SELECT `Func` FROM `{$this -> sDBTable}` WHERE `ID` = $iID";
		$sFunc  = db_value( $sQuery );
		if( !$sFunc )
			return;
		
		$sCaption = process_db_input($aData['Caption']);
		$sVisible = implode( ',',    $aData['Visible']);
		
		if( $sFunc == 'RSS' )
			$sContentUpd = "`Content` = '" . process_db_input($aData['Url']) . '#' . (int)$aData['Num'] . "',";
		elseif( $sFunc == 'Echo' )
			$sContentUpd = "`Content` = '" . process_db_input($aData['Content']) . "',";
		else
			$sContentUpd = '';
		
		$sQuery = "
			UPDATE `{$this -> sDBTable}` SET
				`Caption` = '$sCaption',
				$sContentUpd
				`Visible` = '$sVisible'
			WHERE `ID` = $iID
		";
		
		db_res( $sQuery );
		
		echo _t( process_pass_data($aData['Caption']) );
	}
	
	function saveColsWidths( $aWidths ) {
		$iCounter = 0;
		foreach( $aWidths as $iWidth ) {
			$iCounter ++;
			$iWidth = (int)$iWidth;
			
			$sQuery = "UPDATE `{$this -> sDBTable}` SET `ColWidth` = $iWidth WHERE `Page` = '{$this -> sPage_db}' AND `Column` = $iCounter";
			db_res( $sQuery );
		}
		
		echo 'OK';
	}
	
	function saveBlocks( $aColumns ) {
		//reset blocks on this page
		$sQuery = "UPDATE `{$this -> sDBTable}` SET `Column` = 0, `Order` = 0 WHERE `Page` = '{$this -> sPage_db}'";
		db_res( $sQuery );
		
		$iColCounter = 0;
		foreach( $aColumns as $sBlocks ) {
			$iColCounter ++;
			
			$aBlocks = explode( ',', $sBlocks );
			foreach( $aBlocks as $iOrder => $iBlockID ) {
				$iBlockID = (int)$iBlockID;
				$sQuery = "UPDATE `{$this -> sDBTable}` SET `Column` = $iColCounter, `Order` = $iOrder WHERE `ID` = $iBlockID AND `Page` = '{$this -> sPage_db}'";
				db_res( $sQuery );
			}
		}
		
		echo 'OK';
	}
	
	function showMainPage() {
		global $_page;
		global $site;
		
		$_page['header']   = 'Page Builder';
		$_page['css_name'] = 'pageBuilder.css';
		$_page['extraCodeInHead'] = <<<BLAH
				
				<script type="text/javascript" src="{$site['plugins']}jquery/jquery.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/jquery.dimensions.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/jquery.form.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.mouse.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.draggable.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.draggable.ext.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.droppable.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.sortable.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.sortable.ext.js"></script>
				<script type="text/javascript" src="{$site['plugins']}jquery/ui.slider.js"></script>
				
				<script type="text/javascript" language="javascript" src="{$site['url']}inc/js/classes/BxDolPageBuilder.js"></script>
				
				<!-- tinyMCE gz -->
				<script type="text/javascript" src="{$site['plugins']}tiny_mce/tiny_mce_gzip.js"></script>
				<script type="text/javascript">
					tinyMCE_GZ.init({
						plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
						themes : "simple,advanced",
						languages : "en",
						disk_cache : true,
						debug : false
					});
				</script>
			
				<script language="javascript" type="text/javascript">
					tinyMCE.init({
						mode : "textareas",
						theme : "advanced",
						
						editor_selector : "form_input_html",
						content_css : "{$site['plugins']}tiny_mce/dolphin.css",
						
						plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras",
						relative_urls : false,
						
						theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "forecolor,backcolor,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,hr,|,sub,sup,|,insertdate,inserttime,|,styleprops",
						theme_advanced_buttons3 : "charmap,emotions,|,cite,abbr,acronym,attribs,|,preview,removeformat,|,code,help",
						theme_advanced_buttons4 : "table,row_props,cell_props,delete_col,delete_row,delete_table,col_after,col_before,row_after,row_before,row_after,row_before,split_cells,merge_cells",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "center",
						valid_elements : "*[*]"
					});
				</script>
BLAH;
		
		$_page['extraCodeInBody'] = <<<BLAH
				<div id="editFormWrapper"
				  style="display:none;" onclick="if ( ( event.target || event.srcElement ) == this ) $(this).hide();">
					<div id="editFormCont">
						
					</div>
				</div>
BLAH;
		TopCodeAdmin();
		
		$this -> showBuildZone();
		
		BottomCode();
	}
	
	function showBuildZone() {
		global $site;
		?>
			<div id="buildZoneWrapper">
		<?
		
		$this -> showPageSelector();
		
		if( $this -> oPage ) {
			?>
				<div id="buildAreasWrapper">
					<div class="block_head">Page Width</div>
					<div class="block_cont_nd">
						
						<div id="pageWidthValue"></div>
						
						<div id="pageWidthSlider">
							<div></div>
						</div>
						
						<div class="clear_both"></div>
					</div>
					
					<div class="block_head">Active Blocks</div>
					<div class="block_cont" id="activeAreaWrapper">
						<div id="pageControls">
			<?
			if( !isset( $this -> aAliases[ $this -> oPage -> sName ] ) ) {
				?>
							<a href="<?= $site['url'] ?>viewPage.php?ID=<?= htmlspecialchars( $this -> oPage -> sName ) ?>"
							  target="_blank">View Page</a>
				<?
			}
			
			if( $this -> oPage -> bResetable ) {
				?>
							<a href="#" onclick="oPB.resetPage(); return false;">Reset Page</a>
				<?
			}
			?>
							
						</div>
						
						<div id="activeBlocksArea" class="buildArea">
							Loading...
						</div>
					</div>
					
					<div id="columnsSlider">
						<div></div>
					</div>

					<div class="block_head">Inactive Blocks</div>
					<div class="block_cont">
						<div id="inactiveBlocksArea" class="buildArea">
							Loading...
						</div>
					</div>
					
					<div class="block_head">Samples</div>
					<div class="block_cont">
						<div id="samplesBlocksArea" class="buildArea">
							Loading...
						</div>
					</div>
					
					<div class="block_head">Other Pages Width</div>
					<div class="block_cont_nd">
						
						<div id="pageWidthValue1"></div>
						
						<div id="pageWidthSlider1">
							<div></div>
						</div>
						
						<div class="clear_both"></div>
					</div>
					
				</div>
				
				<script language="javascript" type="text/javascript">
					$( document ).ready( function(){
						oPB = new BxDolPageBuilder( {
							parser: '<?= $_SERVER['PHP_SELF'] ?>',
							page: '<?= addslashes( $this -> oPage -> sName ) ?>',
							minCols: 1,
							maxCols: 4,
							pageWidth: '<?= $this -> oPage -> iPageWidth ?>',
							otherPagesWidth: '<?= getParam( 'main_div_width' ) ?>'
						} );
					} );
				</script>
			<?
		}
		
		?>
			</div>
		<?
	}
	
	function showPageSelector() {
		?>
		<div>
			<a href="#" onclick="oPB.newPage(); return false;" id="newPageLink">New Page</a>
			
			<ul id="pageSelector">
		<?
		foreach( $this -> aPages as $sPage ) {
			$sSelected = ( $this -> oPage -> sName == $sPage ) ? 'class="current"' : '';
			
			?>
				<li <?= $sSelected ?>>
					<a href="<?= $_SERVER['PHP_SELF'] ?>?Page=<?= htmlspecialchars_adv( urlencode($sPage) ) ?>">
						<?= htmlspecialchars( isset($this -> aAliases[$sPage]) ? $this -> aAliases[$sPage] : $sPage ) ?>
					</a>
				</li>
			<?
		}
		
		?>
			</ul>
		</div>
		<?
	}
	
	function getPages() {
		$sPagesQuery = "SELECT DISTINCT `Page` FROM `{$this -> sDBTable}` WHERE `Page` != '' ORDER BY `Page`";
		$rPages = db_res( $sPagesQuery );
		while( $aPage = mysql_fetch_assoc($rPages) )
			$this -> aPages[] = $aPage['Page'];
	}
	
	function checkAjaxMode() {
		if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
			$this -> bAjaxMode = true;
	}
	
	function showPropForm( $iBlockID ) {
		$sQuery = "SELECT * FROM `{$this -> sDBTable}` WHERE `Page` = '{$this -> sPage_db}' AND `ID` = $iBlockID";
		$aItem = db_assoc_arr($sQuery);
		if( !$aItem ) {
			?>
			<div style="text-align:center;color:red;">This block has no properties</div>
			<?
			return ;
		}
		
		?>
<form name="formItemEdit" id="formItemEdit" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
	<input type="hidden" name="Page" value="<?= htmlspecialchars($this->oPage->sName) ?>" />
	<input type="hidden" name="id" value="<?= $iBlockID ?>" />
	<input type="hidden" name="action" value="saveItem" />
	
	<table class="popup_form_wrapper">
		<tr>
			<td class="corner"><img src="images/op_cor_tl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" /></td>
			<td class="corner"><img src="images/op_cor_tr.png" /></td>
		</tr>
		<tr>
			<td class="side"><img src="images/spacer.gif" /></td>
			
			<td class="container">
				<div class="edit_item_table_cont">
				
					<table class="edit_item_table" >
						<tr>
							<td class="form_label">Type:</td>
							<td>
								<?
									switch( $aItem['Func'] ) {
										case 'PFBlock': echo 'Profile Fields'; break;
										case 'Echo':    echo 'HTML Block'; break;
										case 'RSS':     echo 'RSS Feed'; break;
										default:        echo 'Special Block';
									}
								?>
							</td>
						</tr>
						<tr>
							<td class="form_label">Description:</td>
							<td><?= $aItem['Desc'] ?></td>
						</tr>
						<tr>
							<td class="form_label">Caption Lang Key:</td>
							<td>
								<input type="text" class="form_input_text" name="Caption" value="<?= $aItem['Caption'] ?>" />
							</td>
						</tr>
						<tr>
							<td class="form_label">Visible for:</td>
							<td>
								<label>
									<input type="checkbox" name="Visible[]" value="non"
									  <?= ( ( strpos( $aItem['Visible'], 'non'  ) === false ) ? '' : 'checked="checked"' ) ?> />
									Guest
								</label>
								
								<label>
									<input type="checkbox" name="Visible[]" value="memb"
									  <?= ( ( strpos( $aItem['Visible'], 'memb' ) === false ) ? '' : 'checked="checked"' ) ?> />
									Member
								</label>
							</td>
						</tr>
	<?
	if( $aItem['Func'] == 'Echo' ) {
		?>
						<tr>
							<td class="form_label">HTML-content:</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="form_colspan" colspan="2">
								<textarea class="form_input_html" id="form_input_html" name="Content"><?= htmlspecialchars_adv( $aItem['Content'] ) ?></textarea>
							</td>
						</tr>
		<?
	} elseif( $aItem['Func'] == 'RSS' ) {
		list( $sUrl, $iNum ) = explode( '#', $aItem['Content'] );
		$iNum = (int)$iNum;
		
		?>
						<tr>
							<td class="form_label">Url of RSS feed:</td>
							<td><input type="text" class="form_input_text" name="Url" value="<?= $sUrl ?>" /></td>
						</tr>
						<tr>
							<td class="form_label">Number of RSS items (0 - all):</td>
							<td><input type="text" class="form_input_text" name="Num" value="<?= $iNum ?>" /></td>
						</tr>
		<?
	}
	?>
						<tr>
							<td class="form_colspan" colspan="2">
								<input type="submit" value="Save" />
	<?
	if( $aItem['Func'] == 'RSS' or $aItem['Func'] == 'Echo' ) {
		?>
								<input type="reset" value="Delete" name="Delete" />
		<?
	}
	?>
								<input type="reset" value="Cancel" name="Cancel" />
							</td>
						</tr>
					</table>
				
				</div>
			</td>
			
			<td class="side"><img src="images/spacer.gif" alt="" /></td>
		</tr>
		<tr>
			<td class="corner"><img src="images/op_cor_bl.png" /></td>
			<td class="side_ver"><img src="images/spacer.gif" alt="" /></td>
			<td class="corner"><img src="images/op_cor_br.png" onload="if( navigator.appName == 'Microsoft Internet Explorer' && version >= 5.5 && version < 7 ) png_fix();" /></td>
		</tr>
	</table>
</form>


		<?
	}
	
}

class BxDolPVAPage {
	var $sName;
	var $sName_db;
	var $oParent;
	var $aColsWidths     = array();
	var $aBlocks         = array();
	var $aBlocksInactive = array();
	var $aBlocksSamples  = array();
	var $aMinWidths      = array();
	var $iPageWidth;
	var $bResetable; //defines if the page can be reset
	var $sDefaultSqlFile; //file containing default setting for reset
	
	var $bNew = false;
	
	function BxDolPVAPage( $sPage, &$oParent ) {
		global $dir;
		global $admin_dir;
		
		$this -> sName   = $sPage;
		$this -> sName_db = addslashes( $this -> sName );
		
		/* @var $this->oParent BxDolPageViewAdmin */
		$this -> oParent = &$oParent;
		
		$this -> sDefaultSqlFile = "{$dir['root']}{$admin_dir}/default_builders/{$this -> oParent -> sDBTable}_{$this -> sName}.sql";
		$this -> bResetable = file_exists( $this -> sDefaultSqlFile );
				
		$this -> loadContent();
	}
	
	function loadContent() {
		if( in_array( $this -> sName, $this -> oParent -> aPages ) ) {
			//get page width
			$sQuery = "SELECT `PageWidth` FROM `{$this -> oParent -> sDBTable}` WHERE `Page` = '{$this -> sName_db}' LIMIT 1";
			$this -> iPageWidth = db_value( $sQuery );
			
			
			//get columns widths
			$sQuery = "
				SELECT
					`Column`,
					`ColWidth`
				FROM `{$this -> oParent -> sDBTable}`
				WHERE
					`Page` = '{$this -> sName_db}' AND
					`Column` != 0
				GROUP BY `Column`
				ORDER BY `Column`
			";
			$rColumns = db_res( $sQuery );
			while( $aColumn = mysql_fetch_assoc( $rColumns ) ) {
				$iColumn                       = (int)$aColumn['Column'];
				$this -> aColsWidths[$iColumn] = (int)$aColumn['ColWidth'];
				$this -> aBlocks[$iColumn]     = array();
				
				//get active blocks
				$sQueryActive = "
					SELECT
						`ID`,
						`Caption`
					FROM `{$this -> oParent -> sDBTable}`
					WHERE
						`Page` = '{$this -> sName_db}' AND
						`Column` = $iColumn
					ORDER BY `Order`
					";
				
				$rBlocks = db_res( $sQueryActive );
				
				while( $aBlock  = mysql_fetch_assoc( $rBlocks ) )
					$this -> aBlocks[$iColumn][ (int)$aBlock['ID'] ] = _t( $aBlock['Caption'] );
			}
			
			// load minimal widths
			$sQuery = "SELECT `ID`, `MinWidth` FROM `{$this -> oParent -> sDBTable}` WHERE `MinWidth` > 0 AND `Page`= '{$this -> sName_db}'";
			$rBlocks = db_res( $sQuery );
			while( $aBlock = mysql_fetch_assoc( $rBlocks ) )
				$this -> aMinWidths[ (int)$aBlock['ID'] ] = (int)$aBlock['MinWidth'];
			
			
			$this -> loadInactiveBlocks();
			
		} else {
			$this -> bNew = true;
			$this -> oParent -> aPages[] = $this -> sName;
			$this -> loadInactiveBlocks();
			//load from post
		}
	}
	
	function loadInactiveBlocks() {
		//get inactive blocks and samples
		$sQueryInactive = "
			SELECT
				`ID`,
				`Caption`
			FROM `{$this -> oParent -> sDBTable}`
			WHERE
				`Page` = '{$this -> sName_db}' AND
				`Column` = 0
		";
		
		$sQuerySamples = "
			SELECT
				`ID`,
				`Caption`
			FROM `{$this -> oParent -> sDBTable}`
			WHERE
				`Func` = 'Sample'
		";
		
		$rInactive = db_res( $sQueryInactive );
		$rSamples  = db_res( $sQuerySamples );
		
		while( $aBlock = mysql_fetch_assoc( $rInactive ) )
			$this -> aBlocksInactive[ (int)$aBlock['ID'] ] = _t( $aBlock['Caption'] );
		
		while( $aBlock = mysql_fetch_assoc( $rSamples ) )
			$this -> aBlocksSamples[ (int)$aBlock['ID'] ] = _t( $aBlock['Caption'] );
	}
	
	function getJSON() {
		$oPVAPageJSON = new BxDolPVAPageJSON( $this );
		$oJson = new Services_JSON();
		return $oJson -> encode($oPVAPageJSON);
	}
	
}

/* temporary JSON object */
class BxDolPVAPageJSON {
	var $active;
	var $inactive;
	var $samples;
	var $widths;
	var $min_widths;
	
	function BxDolPVAPageJSON( $oParent ) {
		$this -> widths     = $oParent -> aColsWidths;
		$this -> min_widths = $oParent -> aMinWidths;
		$this -> active     = $oParent -> aBlocks;
		$this -> inactive   = $oParent -> aBlocksInactive;
		$this -> samples    = $oParent -> aBlocksSamples;
	}
}


class BxDolPageViewCacher {
	var $sCacheFile;
	
	function BxDolPageViewCacher( $sDBTable, $sCacheFile ) {
		$this -> sDBTable = $sDBTable;
		$this -> sCacheFile = BX_DIRECTORY_PATH_INC . "db_cached/$sCacheFile";
	}
	
	function createCache() {
		$sCacheString = '';
		
		$rCacheFile = @fopen( $this -> sCacheFile, 'w' );
		if( !$rCacheFile ) {
			echo '<br /><b>Warning!</b> Cannot open Page View cache file (' . $this -> sCacheFile . ') for write.';
			return false;
		}
		
		fwrite( $rCacheFile, "// cache of Page View composer\n\nreturn array(\n  //pages\n" );
		
		//get pages
		$sQuery = "SELECT `Page`,`PageWidth` FROM `{$this -> sDBTable}` WHERE `Page` != '' GROUP BY `Page`";
		$rPages = db_res( $sQuery );
		
		while( $aPage = mysql_fetch_assoc( $rPages ) ) {
			$sPageName = $aPage['Page'];
			
			fwrite( $rCacheFile, "  '$sPageName' => array(\n" );
			fwrite( $rCacheFile, "    'Width' => '{$aPage['PageWidth']}',\n" );
			fwrite( $rCacheFile, "    'Columns' => array(\n" );
			
			//get columns
			$sQuery = "
				SELECT
					`Column`,
					`ColWidth`
				FROM `{$this -> sDBTable}`
				WHERE
					`Page` = '$sPageName' AND
					`Column` > 0
				GROUP BY `Column`
				ORDER BY `Column`
			";
			$rColumns = db_res( $sQuery );
			
			while( $aColumn = mysql_fetch_assoc( $rColumns ) ) {
				$iColumn = $aColumn['Column'];
				$iColWidth  = $aColumn['ColWidth'];
				
				fwrite( $rCacheFile, "      $iColumn => array(\n" );
				fwrite( $rCacheFile, "        'Width'  => $iColWidth,\n" );
				fwrite( $rCacheFile, "        'Blocks' => array(\n" );
				
				//get blocks of column
				$sQuery = "
					SELECT
						`ID`,
						`Caption`,
						`Func`,
						`Content`,
						`DesignBox`,
						`Visible`
					FROM `{$this -> sDBTable}`
					WHERE
						`Page` = '$sPageName' AND
						`Column` = $iColumn
					ORDER BY `Order` ASC
				";
				$rBlocks = db_res( $sQuery );
				
				while( $aBlock = mysql_fetch_assoc( $rBlocks ) ) {
					fwrite( $rCacheFile, "          {$aBlock['ID']} => array(\n" );
					
					fwrite( $rCacheFile, "            'Func'      => '{$aBlock['Func']}',\n" );
					fwrite( $rCacheFile, "            'Content'   => '" . $this -> addSlashes( $aBlock['Content'] ) . "',\n" );
					fwrite( $rCacheFile, "            'Caption'   => '" . $this -> addSlashes( $aBlock['Caption'] ) . "',\n" );
					fwrite( $rCacheFile, "            'Visible'   => '{$aBlock['Visible']}',\n" );
					fwrite( $rCacheFile, "            'DesignBox' => {$aBlock['DesignBox']}\n" );
					
					fwrite( $rCacheFile, "          ),\n" ); //close block
				}
				fwrite( $rCacheFile, "        )\n" ); //close blocks
				fwrite( $rCacheFile, "      ),\n" ); //close column
			}
			
			fwrite( $rCacheFile, "    )\n" ); //close columns
			fwrite( $rCacheFile, "  ),\n" ); //close page
		}
		
		fwrite( $rCacheFile, ");\n" ); //close main array
		
		fclose( $rCacheFile );
		return true;
	}
	
	function addSlashes( $sText ) {
		$sText = str_replace( '\\', '\\\\', $sText );
		$sText = str_replace( '\'', '\\\'', $sText );
		
		return $sText;
	}
}