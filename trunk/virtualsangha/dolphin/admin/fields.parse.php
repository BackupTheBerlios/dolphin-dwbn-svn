<?

require_once( '../inc/header.inc.php' );
require_once( $dir['inc'] . 'db.inc.php' );
require_once( $dir['inc'] . 'design.inc.php' );
require_once( $dir['classes'] . 'BxDolPFM.php' );
require_once( $dir['plugins'] . 'Services_JSON.php' );

send_headers_page_changed();

$logged['admin'] = member_auth( 1, true, true );

switch( $_REQUEST['action'] )
{
	case 'getArea':
		genAreaJSON( (int)$_REQUEST['id'] );
	break;
	case 'createNewBlock':
		createNewBlock();
	break;
	case 'createNewItem':
		createNewItem();
	break;
	case 'savePositions':
		savePositions( (int)$_REQUEST['id'] );
	break;
	case 'loadEditForm':
		showEditForm( (int)$_REQUEST['id'], (int)$_REQUEST['area'] );
	break;
	case 'dummy':
		echo 'Dummy!';
	break;
	case 'Save'://save item
		saveItem( (int)$_POST['area'], $_POST );
	break;
	case 'Delete'://delete item
		deleteItem( (int)$_POST['id'], (int)$_POST['area'] );
	break;
}

function createNewBlock() {
	$oFields = new BxDolPFM( 1 );
	$iNewID = $oFields -> createNewBlock();
	header('Content-Type:text/javascript');
	echo '{id:' . $iNewID . '}';
}

function createNewItem() {
	$oFields = new BxDolPFM( 1 );
	$iNewID = $oFields -> createNewField();
	
	header('Content-Type:text/javascript');
	echo '{id:' . $iNewID . '}';
}

function genAreaJSON( $iAreaID ) {
	$oFields = new BxDolPFM( $iAreaID );
	
	header( 'Content-Type:text/javascript' );
	echo $oFields -> genJSON();
}

function savePositions( $iAreaID ) {
	$oFields = new BxDolPFM( $iAreaID );
	
	header( 'Content-Type:text/javascript' );
	$oFields -> savePositions( $_POST );

	$oCacher = new BxDolPFMCacher();
	$oCacher -> createCache();
}

function saveItem( $iAreaID, $aData ) {
	$oFields = new BxDolPFM( $iAreaID );
	$oFields -> saveItem( $_POST );

	$oCacher = new BxDolPFMCacher();
	$oCacher -> createCache();
}

function deleteItem( $iItemID, $iAreaID ) {
	$oFields = new BxDolPFM( $iAreaID );
	$oFields -> deleteItem( $iItemID );

	$oCacher = new BxDolPFMCacher();
	$oCacher -> createCache();
}

function showEditForm( $iItemID, $iAreaID ) {
	$oFields = new BxDolPFM( $iAreaID );
	
	?>
	<form name="fieldEditForm" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" target="fieldFormSubmit" onsubmit="clearFormErrors( this )" onreset="hideEditForm();">
		<table class="popup_form_wrapper">
			<tr>
				<td class="corner"><img src="images/op_cor_tl.png"/></td>
				<td class="side_ver"><img src="images/spacer.gif" alt="" /></td>
				<td class="corner"><img src="images/op_cor_tr.png"/></td>
			</tr>
			<tr>
				<td class="side"><img src="images/spacer.gif" alt="" /></td>
				<td class="container">
					<div class="edit_item_table_cont">
	<?
	$oFields -> genFieldEditForm( $iItemID );
	?>
					</div>
				</td>
				<td class="side"><img src="images/spacer.gif" alt="" /></td>
			</tr>
			<tr>
				<td class="corner"><img src="images/op_cor_bl.png"/></td>
				<td class="side_ver"><img src="images/spacer.gif" alt="" /></td>
				<td class="corner"><img onload="if( navigator.appName == 'Microsoft Internet Explorer' && version >= 5.5 && version < 7 ) png_fix();" src="images/op_cor_br.png"/></td>
			</tr>
		</table>
	</form>
	
	<iframe height="100" width="100" scrolling="no" frameborder="1" name="fieldFormSubmit" src="<?= $_SERVER['PHP_SELF'] ?>?action=dummy" style="display:none;">
		Your browser doesn't support IFRAMEs. We recommend upgrading your browser for correct work of the builder.
	</iframe>
	<?
}

?>