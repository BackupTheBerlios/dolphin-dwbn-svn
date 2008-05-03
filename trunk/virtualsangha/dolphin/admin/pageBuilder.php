<?

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'images.inc.php' );
require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolPageViewAdmin.php' );

$logged['admin'] = member_auth( 1, true, true );

$oPVAdm = new BxDolPageViewAdmin( 'PageCompose', 'PageView.inc' );
