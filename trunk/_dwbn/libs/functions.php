<?php
/**
 * This file contains copies of Boonex function which I had to "adjust"
 * and "semi-overwrite" this way. ;-)
 */
function tfk_genUploadForm( $groupID, $back_home = false, $set_def = false, $action = 'upload' )
{
    global $site;

    ob_start();
    ?>
        <div class="group_upload_form">
            <form action="<?=$site['url']?>group_actions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="ID" value="<?=$groupID?>" />
                <input type="hidden" name="a" value="<?=$action?>" />
    <?php
    if( $back_home )
    {
        ?>
                <input type="hidden" name="back" value="home" />
        <?php
    }

    if( $set_def )
    {
        ?>
                <input type="hidden" name="set_def" value="yes" />
        <?php
    }
    ?>
                <?=_t( '_Select file' )?><br />
                <input type="file" name="file" />
                <input type="submit" name="do_submit" value="<?=_t('_Submit')?>" />
            </form>
        </div>
    <?php
    return ob_get_clean();
}
