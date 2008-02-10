<?php
/**
 * This file is for handling fileuploads to groups
 * @author Till Klampaeckel <till@php.net>
 */

/**
 * @var string $fileVault The location of files.
 */
$fileVault = dirname(__FILE__) . '/../groups/files/';

if (!file_exists($fileVault) || !is_writable($fileVault)) {
    echo 'Config error, please check $fileVault.';
    exit;
}

/**
 * functions...
 */
require_once dirname(__FILE__) . '/libs/functions.php';

/**
 * tfk_upload_files
 */
require_once dirname(__FILE__) . '/libs/tfk_upload_files.class.php';

if (isGroupMember($memberID, $groupID)) {

    if ( $_SERVER['REQUEST_METHOD'] == 'GET') {

        $_page['header']      = _t( "Upload a file to group" );
        $_page['header_text'] = _t( "Upload a file to group" );

        $_page_cont[$_ni]['page_main_code']  =  _t('This form allows you to attach files to this group.');
        $_page_cont[$_ni]['page_main_code'] .= tfk_genUploadForm($groupID, false, false, 'uploadFile');

    } else {

        try {

            $upload = tfk_upload_files::factory($fileVault, $groupID, $memberID);
            $upload->handle('group');

            $_page['header']      = _t("Your file was uploaded");
            $_page['header_text'] = _t("Your file was uploaded");

            $_page_cont[$_ni]['page_main_code']  = _t('Yeah! Your file upload was succesfull.');
            $_page_cont[$_ni]['page_main_code'] .= $getBackCode;

        } catch (Exception $e) {

            switch ($e->getCode()) {
            case 404:
                $_rData = tfk_upload_files::error($_page,
                    $_page_cont, 'gallery', $e->getMessage(), $_ni);
                break;

            case 500:
                $_rData = tfk_upload_files::error($_page,
                    $_page_cont, 'gallery', $e->getMessage(), $_ni);
                break;

            default:
                $_rData = tfk_upload_files::error($_page,
                    $_page_cont, 'gallery', $e->getMessage(), $_ni);
                break;
            }

            $_page      = $_rData['_page'];
            $_page_cont = $_rData['_page_cont'];            
        } 
    }
} else {

    $_rData = tfk_upload_files::error($_page,
        $_page_cont, 'gallery',
        "_You cannot upload files because you're not group member",
        $_ni);

    $_page      = $_rData['_page'];
    $_page_cont = $_rData['_page_cont'];

}