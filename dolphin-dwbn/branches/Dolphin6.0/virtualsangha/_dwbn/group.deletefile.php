<?php
$img = (int)$_REQUEST['img'];

$query  = "SELECT `member_id`, `realname` FROM `tfk_files`";
$query .= " WHERE `id`= $img AND parent_type = 'group'";
$query .= " AND `member_id` = $memberID";

$isAuthor = db_res($query);
if ($arrGroup['member_id'] == $memberID || mysql_num_rows($isAuthor)) {
    $arrFile = mysql_fetch_array($isAuthor);

    if ($img) {
        db_res("DELETE FROM tfk_files WHERE id = $img AND parent_type = 'group'");
        @unlink($arrFile['realpath']);
    }
    Header("Location: {$site['url']}group_files.php?ID=$groupID");
    exit;
}
$_page['header']      = _t("_Group file delete");
$_page['header_text'] = _t("_Group file delete");

$_page_cont[$_ni]['page_main_code'] = _t("_You cannot delete file because you are not group creator");
?>
