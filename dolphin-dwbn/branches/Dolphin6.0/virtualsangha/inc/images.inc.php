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

require_once( 'header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'params.inc.php' );

$gdInstalled = extension_loaded( 'gd' );
$use_gd = getParam( 'enable_gd' ) == 'on' ? 1 : 0;

// Function error codes
define( 'IMAGE_ERROR_SUCCESS',					0 );
define( 'IMAGE_ERROR_SOURCE_NOT_EXISTS',		1 );
define( 'IMAGE_ERROR_WRONG_TYPE',				2 );
define( 'IMAGE_ERROR_FILE_OPEN_FAILED',			3 );
define( 'IMAGE_ERROR_IMAGEMAGICK_ERROR',		4 );
define( 'IMAGE_ERROR_GD_NOT_INSTALLED',			5 );
define( 'IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED',	6 );
define( 'IMAGE_ERROR_GD_OPEN_FAILED',			7 );
define( 'IMAGE_ERROR_GD_RESIZE_ERROR',			8 );
define( 'IMAGE_ERROR_GD_MERGE_ERROR',			9 );
define( 'IMAGE_ERROR_GD_WRITE_FAILED',			10 );
define( 'IMAGE_ERROR_GD_TTF_NOT_SUPPORTED',		11 );


// Image types for GD
// NOTE: actually these constants exist in PHP >= 4.3.0, but they are included for
//       back compatibility
define( 'IMAGE_TYPE_GIF', 		1 );
define( 'IMAGE_TYPE_JPG', 		2 );
define( 'IMAGE_TYPE_PNG', 		3 );

/**
 * Resizes image given in $srcFilename to dimensions specified with $sizeX x $sizeY and
 * saves it to $dstFilename
 *
 * @param string $srcFilename			- source image filename
 * @param string $dstFilename			- destination image filename
 * @param int $sizeX					- width of destination image
 * @param int $sizeY					- height of destination image
 * @param bool $forceJPGOutput			- always make result in JPG format
 *
 * @return int 							- zero on success, non-zero on fail
 *
 *
 * NOTE: Source image should be in GIF, JPEG or PNG format
*/
function imageResize( $srcFilename, $dstFilename, $sizeX, $sizeY, $forceJPGOutput = false )
{
	global $use_gd;
	global $CONVERT;
	global $MOGRIFY;
	global $gdInstalled;

	// input validation
	$sizeX = (int) $sizeX;
	$sizeY = (int) $sizeY;
	if ( !file_exists( $srcFilename ) )
		return IMAGE_ERROR_SOURCE_NOT_EXISTS;

	// if destination and source filenames are equivalent then change mode for destination
	if ( $srcFilename == $dstFilename )
		chmod( $dstFilename, 0666 );

	if ( $use_gd )
	{
		if ( !$gdInstalled )
			return IMAGE_ERROR_GD_NOT_INSTALLED;
		$gdInfoArray = gd_info();
		$size = getimagesize( $srcFilename );

		// only GIF, JPG and PNG allowed
		switch ( $size[2] )
		{
			case IMAGE_TYPE_GIF:
				if ( !$gdInfoArray['GIF Read Support'] || !$gdInfoArray['GIF Create Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = @imagecreatefromgif( $srcFilename );
				break;
			case IMAGE_TYPE_JPG:
				if ( !$gdInfoArray['JPG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = @imagecreatefromjpeg( $srcFilename );
				break;
			case IMAGE_TYPE_PNG:
				if ( !$gdInfoArray['PNG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = @imagecreatefrompng( $srcFilename );
				break;
			default:
				return IMAGE_ERROR_WRONG_TYPE;
		}

		if ( !$src_im )
			return IMAGE_ERROR_GD_OPEN_FAILED;

		// determ destination size
		$sourceRatio = (float) ($size[0] / $size[1]);
		$destRatio = (float) ($sizeX / $sizeY);
		if ( $sourceRatio > $destRatio )
		{
			$resizeRatio = (float) ($sizeX / $size[0]);
		}
		else
		{
			$resizeRatio = (float) ($sizeY / $size[1]);
		}
		$destX = (int) ($resizeRatio * $size[0]);
		$destY = (int) ($resizeRatio * $size[1]);

		// this is more qualitative function, but it doesn't exist in old GD and doesn't support GIF format
		if ( function_exists( 'imagecreatetruecolor' ) && $size[2] != IMAGE_TYPE_GIF )
		{
			// resize only if size is larger than needed
			if ( $size[0] > $sizeX || $size[1] > $sizeY )
			{
				$dst_im = imagecreatetruecolor( $destX, $destY );
				$convertResult = imagecopyresampled( $dst_im, $src_im, 0, 0, 0, 0,
					$destX, $destY, $size[0], $size[1] );
			}
			else
			{
				$dst_im = $src_im;
				$convertResult = true;
			}
		}
		else // this is for old GD versions and for GIF images
		{
			// resize only if size is larger than needed
			if ( $size[0] > $sizeX || $size[1] > $sizeY )
			{
				$dst_im = imagecreate( $destX, $destY );
				$convertResult = imagecopyresized( $dst_im, $src_im, 0, 0, 0, 0,
					$destX, $destY, $size[0], $size[1] );
			}
			else
			{
				$dst_im = $src_im;
				$convertResult = true;
			}
		}

		if ( !$convertResult )
			return IMAGE_ERROR_GD_RESIZE_ERROR;

		// if output always in JPG
		if ( $forceJPGOutput )
		{
			$writeResult = imagejpeg( $dst_im, $dstFilename );
		}
		else // otherwise
		{
			switch ( $size[2] )
			{
				case IMAGE_TYPE_GIF:
					$writeResult = imagegif( $dst_im, $dstFilename );
					break;
				case IMAGE_TYPE_JPG:
					$writeResult = imagejpeg( $dst_im, $dstFilename );
					break;
				case IMAGE_TYPE_PNG:
					$writeResult = imagepng( $dst_im, $dstFilename );
					break;
			}
		}

		// free memory
		if ( $dst_im != $src_im )
		{
			imagedestroy( $src_im );
			imagedestroy( $dst_im );
		}
		else
		{
			imagedestroy( $src_im );
		}

		if ( $writeResult && file_exists($dstFilename) )
			return IMAGE_ERROR_SUCCESS;
		else
			return IMAGE_ERROR_GD_WRITE_FAILED;
	}
	else
	{
		if ( $srcFilename == $dstFilename )
		{
			$cmd = "$MOGRIFY -geometry {$sizeX}\">\"x{$sizeY}\">\" $srcFilename";
			@exec( $cmd );
			$ext = strrchr($srcFilename, '.');
			$nameWithoutExt = substr( $srcFilename, 0, strrpos($srcFilename, '.') );
			if ( file_exists( "{$nameWithoutExt}.mgk" ) )
				rename( "{$nameWithoutExt}.mgk", $srcFilename );
		}
		else
		{
			$cmd = "$CONVERT $srcFilename -geometry {$sizeX}\">\"x{$sizeY}\">\" $dstFilename";
			@exec( $cmd );
		}

		if ( file_exists($dstFilename) )
			return IMAGE_ERROR_SUCCESS;
		else
			return IMAGE_ERROR_IMAGEMAGICK_ERROR;
	}
}

/**
 * Sends PNG image header to browser and produces security image with text, specified in
 * $text parameter
 *
 * @param string $text					- text to output on security image
 * @param string $hash					- MD5 hash for $text parameter
 *
 * @return int 							- zero on success, non-zero on fail
 *
 * NOTE: Source image should be in GIF, JPEG or PNG format
*/
function produceSecurityImage( $text, $hash )
{
	global $use_gd;
	global $CONVERT;
	global $gdInstalled;
	global $dir;

	// constant values
	$backgroundSizeX = 2000;
	$backgroundSizeY = 350;
	$sizeX = 200;
	$sizeY = 35;
	$fontFile = "{$dir['root']}simg/verdana.ttf";
	$textLength = strlen( $text );

	// generate random security values
	$backgroundIndex = rand( 1, 3 );
	$backgroundOffsetX = rand( 0, $backgroundSizeX - $sizeX - 1 );
	$backgroundOffsetY = rand( 0, $backgroundSizeY - $sizeY - 1 );
	$angle = rand( -3, 3 );
	$fontColorR = rand( 0, 127 );
	$fontColorG = rand( 0, 127 );
	$fontColorB = rand( 0, 127 );
	// this are library depending parameters
	if ( $use_gd )
	{
		$fontSize = rand( 14, 24 );
		$textX = rand( 0, (int)($sizeX - 0.9 * $textLength * $fontSize) ); // these coefficients are empiric
		$textY = rand( (int)(1.25 * $fontSize), (int)($sizeY - 0.2 * $fontSize) ); // don't try to learn how they were taken out
	}
	else
	{
		$fontSize = rand( 14, 28 );
		$textX = rand( 0, (int)($sizeX - 0.68 * $textLength * $fontSize) ); // these coefficients are empiric
		$textY = rand( 0, (int)($sizeY - 1.4 * $fontSize) ); // don't try to learn how they were taken out
	}

	if ( $use_gd )
	{
		if ( !$gdInstalled )
			return IMAGE_ERROR_GD_NOT_INSTALLED;
		$gdInfoArray = gd_info();
		if ( !$gdInfoArray['PNG Support'] )
			return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;

		// create image with background
		$src_im = imagecreatefrompng( "{$dir['root']}simg/images/bg{$backgroundIndex}.png" );
		if ( function_exists( 'imagecreatetruecolor' ) )
		{
			// this is more qualitative function, but it doesn't exist in old GD
			$dst_im = imagecreatetruecolor( $sizeX, $sizeY );
			$resizeResult = imagecopyresampled( $dst_im, $src_im, 0, 0, $backgroundOffsetX, $backgroundOffsetY,
				$sizeX, $sizeY,	$sizeX, $sizeY );
		}
		else
		{
			// this is for old GD versions
			$dst_im = imagecreate( $sizeX, $sizeY );
			$resizeResult = imagecopyresized( $dst_im, $src_im, 0, 0, $backgroundOffsetX, $backgroundOffsetY,
				$sizeX, $sizeY,	$sizeX, $sizeY );
		}
		if ( !$resizeResult )
			return IMAGE_ERROR_GD_RESIZE_ERROR;

		// write text on image
		if ( !function_exists( 'imagettftext' ) )
			return IMAGE_ERROR_GD_TTF_NOT_SUPPORTED;
		$color = imagecolorallocate( $dst_im, $fontColorR, $fontColorG, $fontColorB );
		imagettftext( $dst_im, $fontSize, -$angle, $textX, $textY, $color, $fontFile, $text );

		// output header
		header( "Content-Type: image/png" );

		// output image
		imagepng( $dst_im );

		// free memory
		imagedestroy( $src_im );
		imagedestroy( $dst_im );

		return IMAGE_ERROR_SUCCESS;
	}
	else
	{
		// create image with background
		$workFilename = "{$dir['root']}tmp/bg{$hash}.png";
		$cmd  = "$CONVERT {$dir['root']}simg/images/bg{$backgroundIndex}.png -crop {$sizeX}x{$sizeY}+{$backgroundOffsetX}+{$backgroundOffsetY} $workFilename";
		@exec( $cmd );
		if ( !file_exists( $workFilename ) )
			return IMAGE_ERROR_IMAGEMAGICK_ERROR;

		// write text on image
		$workFilename = "{$dir['root']}tmp/{$hash}.png";
		$color = '#' . sprintf( '%02x', $fontColorR ) . sprintf( '%02x', $fontColorG ) . sprintf( '%02x', $fontColorB );
		$textX += $backgroundOffsetX;
		$textY += $backgroundOffsetY;
		$cmd  = "$CONVERT {$dir['root']}tmp/bg{$hash}.png -font $fontFile -fill \"{$color}\" -pointsize $fontSize -gravity NorthWest -draw \"translate {$textX},{$textY} rotate {$angle} text 0,0 '{$text}'\" $workFilename";
		@exec( $cmd );
		if ( !file_exists( $workFilename ) )
			return IMAGE_ERROR_IMAGEMAGICK_ERROR;

		// output header
		header( "Content-Type: image/png" );

		// output image
		$fp = fopen( $workFilename, 'r' );
		$fsize = filesize( $workFilename );
		if ( !$fp )
			return IMAGE_ERROR_FILE_OPEN_FAILED;
		print fread( $fp, $fsize );
		fclose( $fp );

		// unlink temporary files
		unlink( "{$dir['root']}tmp/bg{$hash}.png" );
		unlink( "{$dir['root']}tmp/{$hash}.png" );

		return IMAGE_ERROR_SUCCESS;
	}
}

/**
 * Applies watermark to image given in $srcFilename with specified opacity and saves result
 * to $dstFilename
 *
 * @param string $srcFilename			- source image filename
 * @param string $dstFilename			- destination image filename
 * @param string $wtrFilename			- watermark filename
 * @param int $wtrTransparency			- watermark transparency (from 0 to 100)
 *
 * @return int 							- zero on success, non-zero on fail
 *
 *
 * NOTE: Source image should be in GIF, JPEG or PNG format
 * NOTE: if $wtrTransparency = 0 then no action will be done with source image
 *       but if $wtrTransparency = 100 then watermark will fully override source image
*/
function applyWatermark( $srcFilename, $dstFilename, $wtrFilename, $wtrTransparency )
{
	global $use_gd;
	global $COMPOSITE;
	global $gdInstalled;

	// input validation
	$wtrTransparency = (int) $wtrTransparency;
	if ( $wtrTransparency > 100 )
		$wtrTransparency = 100;
	if ( !file_exists( $srcFilename ) )
		return IMAGE_ERROR_SOURCE_NOT_EXISTS;
	if ( !file_exists( $wtrFilename ) )
		return IMAGE_ERROR_SOURCE_NOT_EXISTS;

	// if destination and source filenames are equivalent then change mode for destination
	if ( $srcFilename == $dstFilename )
		chmod( $dstFilename, 0666 );

	if ( $use_gd )
	{
		if ( !$gdInstalled )
			return IMAGE_ERROR_GD_NOT_INSTALLED;
		$gdInfoArray = gd_info();
		$size = getimagesize( $srcFilename );
		$wtrSize = getimagesize( $wtrFilename );

		// only GIF, JPG and PNG allowed
		switch ( $size[2] )
		{
			case IMAGE_TYPE_GIF:
				if ( !$gdInfoArray['GIF Read Support'] || !$gdInfoArray['GIF Create Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = imagecreatefromgif( $srcFilename );
				break;
			case IMAGE_TYPE_JPG:
				if ( !$gdInfoArray['JPG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = imagecreatefromjpeg( $srcFilename );
				break;
			case IMAGE_TYPE_PNG:
				if ( !$gdInfoArray['PNG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$src_im = imagecreatefrompng( $srcFilename );
				break;
			default:
				return IMAGE_ERROR_WRONG_TYPE;
		}
		switch ( $wtrSize[2] )
		{
			case IMAGE_TYPE_GIF:
				if ( !$gdInfoArray['GIF Read Support'] || !$gdInfoArray['GIF Create Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$wtr_im = imagecreatefromgif( $wtrFilename );
				break;
			case IMAGE_TYPE_JPG:
				if ( !$gdInfoArray['JPG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$wtr_im = imagecreatefromjpeg( $wtrFilename );
				break;
			case IMAGE_TYPE_PNG:
				if ( !$gdInfoArray['PNG Support'] )
					return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;
				$wtr_im = imagecreatefrompng( $wtrFilename );
				break;
			default:
				return IMAGE_ERROR_WRONG_TYPE;
		}

		if ( !$src_im || !$wtr_im )
			return IMAGE_ERROR_GD_OPEN_FAILED;

		$watermarkX = ($size[0] - $wtrSize[0]) / 2;
		$watermarkY = $size[1] - $wtrSize[1];

		$mergeResult = imagecopymerge( $src_im, $wtr_im, $watermarkX, $watermarkY, 0, 0,
			$wtrSize[0], $wtrSize[1], $wtrTransparency );

		if ( !$mergeResult )
			return IMAGE_ERROR_GD_MERGE_ERROR;

		switch ( $size[2] )
		{
			case IMAGE_TYPE_GIF:
				$writeResult = imagegif( $src_im, $dstFilename );
				break;
			case IMAGE_TYPE_JPG:
				$writeResult = imagejpeg( $src_im, $dstFilename );
				break;
			case IMAGE_TYPE_PNG:
				$writeResult = imagepng( $src_im, $dstFilename );
				break;
		}

		// free memory
		imagedestroy( $src_im );
		imagedestroy( $wtr_im );

		if ( $writeResult )
			return IMAGE_ERROR_SUCCESS;
		else
			return IMAGE_ERROR_GD_WRITE_FAILED;
	}
	else
	{
		$imgTransparency = 100 - $wtrTransparency;
		$cmd = "$COMPOSITE -gravity \"South\" -dissolve $imgTransparency $wtrFilename -dissolve $wtrTransparency $srcFilename $dstFilename";
		@exec( $cmd );
		if ( file_exists($dstFilename) )
			return IMAGE_ERROR_SUCCESS;
		else
			return IMAGE_ERROR_IMAGEMAGICK_ERROR;
	}
}

/**
 * Moves and resize uploaded file
 *
 * @param array $_FILES						- system array of uploaded files
 * @param string $fname						- name of "file" form
 * @param string $path_and_name				- path and name of new file to create
 * @param string $maxsize					- max available size (optional)
 * @param boolean $imResize					- call imageResize function immediately (optional)

 *
 * @return int in case of error and extention of new file
 * in case of success
 *
 * NOTE: Source image should be in GIF, JPEG, PNG or BMP format
*/
function moveUploadedImage( $_FILES, $fname, $path_and_name, $maxsize='', $imResize='true' )
{
	global $max_photo_height;
	global $max_photo_width;

	$height = $max_photo_height;
	if ( !$height )
		$height = 400;
	$width = $max_photo_width;
	if ( !$width )
		$width = 400;

	if ( $maxsize && ($_FILES[$fname]['size'] > $maxsize || $_FILES[$fname]['size'] == 0) )
	{
		if ( file_exists($_FILES[$fname]['tmp_name']) )
		{
			unlink($_FILES[$fname]['tmp_name']);
		}
		return false;
	}
	else
	{
		$scan = getimagesize($_FILES[$fname]['tmp_name']);

		if ( ($scan['mime'] == 'image/jpeg' && $ext = '.jpg' ) ||
			( $scan['mime'] == 'image/gif' && $ext = '.gif' ) ||
			( $scan['mime'] == 'image/png' && $ext = '.png' ) ) //deleted .bmp format
		{

			$path_and_name .= $ext;
			move_uploaded_file( $_FILES[$fname]['tmp_name'], $path_and_name );

			if ( $imResize )
			    imageResize( $path_and_name, $path_and_name, $width, $height );

		}
		else
		{
			return IMAGE_ERROR_WRONG_TYPE;
		}
	}

	return $ext;
}


?>