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
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolDatabaseBackup.php');

$logged['admin'] = member_auth( 1, true, true );

$_page['header'] = "Database tools";
$_page['header_text'] = "Manage Database";

set_time_limit( 36000 );

$status_text .= '';
if (isset($_POST['TablesBackup'])) { ##Block of table backup create
		//echo "For: Tables Tools". $_POST['tbl_op'] . ' Table - ' . $_POST['tbl'] . ' Show param - ' . $_POST['savetype'] ; 

		$OutPutType  = preg_replace("/[^0-9]/", '', $_POST['tbl_op']);
		$oNewBackup = new BxDolDatabaseBackup();
		$oNewBackup -> _getTableStruct($_POST['tbl'],  $OutPutType); 
		
		if ($_POST['savetype'] == 'client') {
				$sqlfile = date("Y-m-d_H:i:s").'_'.$_POST['tbl'].'.sql';
				header("Content-Type: text/plain");
				header("Content-Disposition: attachment;filename=\"".$sqlfile."\"");
				echo $oNewBackup -> sInputs;
				exit();
			}
		if ($_POST['savetype'] == 'server') {
					$sqlfile = $dir['root'].'backup/'.date("Y-m-d_H-i-s").'_'.$_POST['tbl'].'.sql';
					$file = fopen($sqlfile, 'w');
					fputs($file, $oNewBackup -> sInputs);
					$status_text .= "<hr size=1 /><font color='green'><center>Data succefully dumped into file <b>{$sqlfile}</b></center></font>\n";
					fclose($file);
			}	
		if ($_POST['savetype'] == 'show') {
				$status_text = "<center><textarea cols='100' rows='30' name='content' style='font-family: Arial; font-size: 11px' readonly='readonly'>" . $oNewBackup -> sInputs ."</textarea></center>";
			}		
		
 	 }

if (isset($_POST['DatabasesBackup'])) {
		$OutPutType  = preg_replace("/[^0-9]/", '', $_POST['db_op']); 
		$oNewBackup = new BxDolDatabaseBackup();
		$oNewBackup ->  _getAllTables($OutPutType); 
		
		if ($_POST['savetype'] == 'show') {
				$status_text = "<center><textarea cols='100' rows='30' name='content' style='font-family: Arial; font-size: 11px' readonly='readonly'>" . $oNewBackup -> sInputs ."</textarea></center>";
		}		
		if ($_POST['savetype'] == 'server') {
					$sqlfile = $dir['root'].'backup/'.date("Y-m-d_H-i-s").'_all.sql';
					$file = fopen($sqlfile, 'w');
					fputs($file, $oNewBackup -> sInputs);
					$status_text .= "<hr size=1 /><font color='green'><center>Data succefully dumped into file <b>{$sqlfile}</b></center></font>\n";
					fclose($file);
		}	
		if ($_POST['savetype'] == 'client') {
				$sqlfile = date("Y-m-d_H:i:s").'_all.sql';
				header("Content-Type: text/plain");
				header("Content-Disposition: attachment;filename=\"".$sqlfile."\"");
				echo $oNewBackup -> sInputs;
				exit();
		}
}	

 if (isset($_POST['DatabasesRestore'])) { 
	if ($_POST['savetype'] == 'delete') {
		if(is_file($dir['root'].'backup/'.$_POST['dump_file'])) {
		   @unlink($dir['root'].'backup/'.$_POST['dump_file']);
		   $status_text .= "<hr size=1 /><font color='green'><center>Dump file  succefully deleted <b>{$sqlfile}</b></center></font>\n";
		}
		else $status_text .= "<hr size=1 /><font color='red'><center>Please select dump file  <b>{$sqlfile}</b></center></font>\n";	
    }
	if ($_POST['savetype'] == 'restore') {
		
		if(is_file($dir['root'].'backup/'.$_POST['dump_file'])) {
		  		$oNewBackup = new BxDolDatabaseBackup();
				$oNewBackup ->	_restoreFromDumpFile($dir['root'].'backup/'.$_POST['dump_file']); 
				$status_text .= "<hr size=1 /><font color='green'><center>Data succefully restored from server dump</center></font>\n";
		 }
		 else  $status_text .= "<hr size=1 /><font color='red'><center>Please select dump file  <b>{$sqlfile}</b></center></font>\n";
		 
    }
  }	

if (isset($_FILES['sqlfile'])) {
	  if (preg_match("/.sql/", $_FILES['sqlfile']['name'])) { #it is correct
	      $oNewBackup = new BxDolDatabaseBackup();
		  $oNewBackup ->	_restoreFromDumpFile($_FILES['sqlfile']['tmp_name'] ); 
		  @unlink($_FILES['sqlfile']['tmp_name']);
		  $status_text .= "<hr size=1 /><font color='green'><center>Data succefully restored from your PS </center></font>\n";
	  }
	 else  $status_text .= "<hr size=1 /><font color='red'><center>Please select correct dump file (only *.sql)</center></font>\n";
}
	
TopCodeAdmin();

ContentBlockHead('Tables backup tools');
?>

<center>

<form style="padding: 0px; margin: 0px;" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" name="TablesBackup" value="YES" />
	<table width="100%" border="0" cellspacing="2" cellpadding="3" class="text">
		<tr>
			<td align="center" colspan="2">Choose operation and table:</td>
		</tr>
		<tr class="table">
			<td align="right" width="50%">
				<select name="tbl_op">
					<option value="2">Backup structure and content</option>
					<option value="0">Backup structure only</option>
					<option value="1">Backup content only</option>
				</select>
			</td>
			<td align="left" width="50%">
				<select name="tbl">
				<?php
				$tbls = db_list_tables(); ##Draw aviable tables in Database
				while ($tbl = mysql_fetch_row($tbls)) echo "<option value=\"{$tbl['0']}\">{$tbl['0']}</option>";
				?>	
				</select>
			</td>
		</tr>
		<tr class="table">
			<td colspan="2" align="center" width="50%">
				<input type="radio" name="savetype" value="server" id="table_savetype_server" checked="checked" style="vertical-align: middle" /><label for="table_savetype_server">Save to server</label>&nbsp;
				<input type="radio" name="savetype" value="client" id="table_savetype_client" style="vertical-align: middle" /><label for="table_savetype_client">Save to your PC</label>&nbsp;
				<input type="radio" name="savetype" value="show" id="table_savetype_show" style="vertical-align: middle" /><label for="table_savetype_show">Show on the screen</label>
			</td>
		</tr>
		<tr>
		<td colspan="2" align="center"><input type="submit" value="Backup table" class="no" /></td>
		</tr>
		
		<?php
		if ($status_text and isset($_POST['TablesBackup'])) {
			?>
		<tr>
			<td colspan="2"><?= $status_text ?></td>
		</tr>
			<?
		}
		?>
	
	</table>
</form>
   
</center>


<?php
ContentBlockFoot();
ContentBlockHead('Database backup tools');
?>

<center>
<form style="padding: 0px; margin: 0px;" method="post" action="<?=  $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" name="DatabasesBackup" value="YES" />
<table width="100%" cellspacing="2" cellpadding="3" class="text">
	<tr>
		<td align="right" width="50%">Choose operation:</td>
		<td align="left" width="50%">
			<select name="db_op">
				<option value="2">Backup structure and content</option>
				<option value="0">Backup structure only</option>
			</select>
		</td>
	</tr>
	<tr class="table">
		<td colspan="2" align="center" width="50%">
			<input type="radio" name="savetype" value="server" id="db_savetype_server" checked="checked" style="vertical-align: middle" /><label for="db_savetype_server">Save to server</label>&nbsp;
			<input type="radio" name="savetype" value="client" id="db_savetype_client" style="vertical-align: middle" /><label for="db_savetype_client">Save to your PC</label>&nbsp;
			<input type="radio" name="savetype" value="show" id="db_savetype_show" style="vertical-align: middle" /><label for="db_savetype_show">Show on the screen</label>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="Backup database" class="no" />
		</td>
	</tr>
	<?
	if ($status_text and isset($_POST['DatabasesBackup'])) {
		?>
	<tr>
		<td colspan="2"><?= $status_text ?></td>
	</tr>
		<?
	}
	?>
</table>
</form>	
</center>

<?php
 ContentBlockFoot(); 
 ContentBlockHead('Database Restore');
?>

<center>
<table width="100%" border="0" cellspacing="2" cellpadding="3" class="text">
	<form style="padding: 0px; margin: 0px;" method="post" action="<?=  $_SERVER['PHP_SELF'] ?>">
	<tr>
		<td align="center">
			Select dump file:
			<select name="dump_file">
			
			<?php
			if ( $handle = @opendir($dir['root'].'backup/') ) {
				while ( $file = readdir($handle) ) { 
					if ( preg_match("/.sql/", $file) )
						echo "<option>{$file}</option>";
				}
			}
			?>
			
			</select>
		</td>
	</tr>
	<tr class="table">
		<td colspan="2" align="center" width="50%">
			<input type="radio" name="savetype" value="restore" id="db_restore" checked="checked" style="vertical-align: middle" /><label for="db_restore">Restore data from dump</label>&nbsp;
			<input type="radio" name="savetype" value="delete" id="db_delete" style="vertical-align: middle" /><label for="db_delete">Delete dump from server</label>&nbsp;
		</td>
	</tr>
	<tr class="table">
		<td align="center" colspan="2">
			<input type="submit" value="Submit" class="no" />
			<input type="hidden" name="DatabasesRestore" value="YES" />
		</td>
	</tr>
	</form>
	<tr class="table">
		<td colspan=2>
			<hr size="1" />
		</td>
	</tr>	
	<tr class="panel">
		<td colspan="2" align="center"><font size="2px"><b>Database Restore from your PC</b></font></td>
	</tr>
	<tr class="table">
		<td colspan="2" align="center">
			<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
				Select dump file:
				<input type="file" name="sqlfile" size="27"  />
				<input type="submit" value="Send files" />
			</form>
		</td>
	</tr>
	
	<?
	if ($status_text and isset($_POST['DatabasesRestore'])  or isset($_FILES['sqlfile']) ) {
		?>
	<tr>
		<td colspan="2"><?= $status_text ?></td>
	</tr>	
		<?
	}
	
	?>
	
</table>

</center>
<?php
ContentBlockFoot();		
BottomCode();
?>