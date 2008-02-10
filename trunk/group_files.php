<?php

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : 2008-02-10
*     copyright            : (C) 2006 BoonEx Group, (C) 2008 Till Klampaeckel
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

require_once 'inc/header.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'design.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'groups.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'utils.inc.php';

// --------------- page variables and login


$_page['name_index'] = 75;
$_page['css_name']   = 'groups.css';


if($logged['member'] = member_auth(0, false)) {
	$memberID = (int)$_COOKIE['memberID'];
} else {
	$memberID = 0;
	$logged['admin'] = member_auth( 1, false );
}
	
$groupID = (int)$_REQUEST['ID'];

if (!$groupID) {
	Header( "Location: {$site['url']}groups_home.php" );
	exit;
}

$bucketID = (int)$_REQUEST['bucket'];

$bcd = getParam('breadCrampDivider');

$_page['header_text'] = _t( "_Group files" );

$_page['header'] = _t( "_Group files" );
$_ni             = $_page['name_index'];

if ($arrGroup = getGroupInfo($groupID)) {
	$arrGroup['Name_html'] = htmlspecialchars_adv( $arrGroup['Name'] );
	
	if ((int)$arrGroup['hidden_group'] and !isGroupMember( $memberID, $groupID ) and !$logged['admin']) {
		$_page_cont[$_ni]['page_main_code'] = _t( "_You cannot view files while not a group member" );
	} else {
		if($arrGroup['status'] == 'Active' or $arrGroup['creatorID'] == $memberID or $logged['admin']) {
			$_page['header'] = _t( "_Group files" );

			$_page_cont[$_ni]['page_main_code'] = PageCompMainCode();
		} else {
			$_page['name_index']  = 0;
			$_page['header']      = _t( "_Group is suspended" );
			$_page['header_text'] = _t( "_Group is suspended" );

			$_page_cont[0]['page_main_code'] = _t( "_Sorry, group is suspended" );
		}
	}
} else {
	$_page_cont[$_ni]['page_main_code'] = _t( "_Group not found_desc" );
}
// --------------- page components

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompMainCode()
{
	global $memberID;
	global $groupID;
	global $arrGroup;
	global $site;
	global $bcd;
    global $bucketID;

    $bucketCrumb = '';
    if (!empty($bucketID)) {
        $bucketCrumb .= "<a href=\"{$site['url']}group_files.php?ID=$groupID\">__Group files__</a>";
        $bucketCrumb .= " $bcd Bucket &quot;" . $bucketID . '&quot;';
    } else {
        $bucketCrumb .= '<span class="active_link">__Group files__</span>';
    }
	
	$breadCrumbs = <<<EOJ
		<div class="groups_breadcrumbs">
			<a href="{$site['url']}">{$site['title']}</a> $bcd
			<a href="{$site['url']}groups_home.php">__Groups__</a> $bcd
			<a href="{$site['url']}group.php?ID=$groupID">{$arrGroup['Name_html']}</a> $bcd
            {$bucketCrumb}
		</div>
EOJ;
	
	$breadCrumbs = str_replace( "__Groups__", _t( "_Groups" ), $breadCrumbs );
	$breadCrumbs = str_replace( "__Group gallery__", _t( "_Group gallery" ), $breadCrumbs );
	
	ob_start();
	echo $breadCrumbs;
	
	$query = "
        SELECT tbl1.*, tbl2.NickName
        FROM tfk_files AS tbl1, `Profiles` AS tbl2
        WHERE 
        tbl1.parent_type = 'group'
        AND tbl1.parent_id = $groupID
        AND tbl1.member_id = tbl2.ID
		";

    if (!empty($bucketID)) {
        $query .= " AND tbl1.bucket_id = " . $bucketID;
    } else {
        $query .= " AND (tbl1.bucket_id IS NULL OR tbl1.bucket_id = 0)";
    }
	$resPics = db_res( $query );
	
	?>
		<div class="group_gallery_wrapper">
			<div class="clear_both"></div>
	<?php
	while($arrPic = mysql_fetch_assoc($resPics)) {
        $_file = "{$arrPic['parent_id']}_{$arrPic['id']}_{$arrPic['seed']}.{$arrPic['extension']}";
        $_type = '';
        switch (strtolower($arrPic['extension'])) {
            case 'jpg':
            case 'gif':
            case 'png':
            case 'bmp':
                $_type = _t('_Image');
                break;

            case 'pdf':
                $_type = 'PDF';
                break;

            case 'txt':
                $_type = 'text';
                break;

            case 'mp3':
                $_type = _t('_Audio');
                break;

            case 'zip':
            case 'tar':
            case 'gz':
            case 'bz2':
            case 'sit':
            case 'rar':
                $_type = _t('Archive');
                break;

            default:
                $_type = _t('_Unknown');
                break;
        }
		?>
			<div class="group_gallery_pic" style="">
                <?=$_type?>:
				<a href="/groups/files/<?=$_file?>"><?=substr($arrPic['realname'], 0, 15); ?>&hellip;</a><br />
                (<?=_t('_Uploaded by').' '.htmlspecialchars_adv($arrPic['NickName'])?>)
		<?php
		if($arrGroup['creatorID'] == $memberID or $arrPic['by'] == $memberID) {
		?>
				<br />
				<a href="<?="{$site['url']}group_actions.php?ID=$groupID&amp;a=delFile&img={$arrPic['id']}"?>" class="group_set_thumb" onclick="return confirm('<?=_t('_Are you sure want to delete this image?')?>');"><?=_t('_Delete file')?></a>
			<?php
		}
		?>
			</div>
		<?php
	}

    if (empty($bucketID)) {
    ?>
            <div class="clear_both"></div>
    <?php
        require_once dirname(__FILE__) . '/_dwbn/libs/tfk_upload_files.class.php';
        $upload  = tfk_upload_files::factory($fileVault, $groupID, $memberID);
        $buckets = $upload->getBuckets('group');
        //var_dump($buckets);
        foreach ($buckets AS $bucket) {
            $_browse  = $site['url'] . 'group_files.php?ID=' . $groupID;
            $_browse .= '&amp;bucket=' . $bucket['id'];
    ?>
        <div class="group_gallery_pic" style="">
            <?php echo '<a href="' . $_browse . '">' . $bucket['name']; ?></a>
        </div>
    <?php
        }
    }
    ?>
            <div class="clear_both"></div>
		</div>
	<?php
	if (((int)$arrGroup['members_post_images'] and isGroupMember($memberID, $groupID))
        or $arrGroup['creatorID'] == $memberID) {
		?>
		<a href="<?="{$site['url']}group_actions.php?a=uploadFile&ID=$groupID"?>" class="actions"><?=_t('_Upload a file')?></a>
		<?php
	}
	return ob_get_clean();
}

?>
