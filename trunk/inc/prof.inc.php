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

$prof['countries'] = array();
$res = db_res( "SELECT `ISO2`, `Country` FROM `Countries` ORDER BY `Country`" );
while ( $arr = mysql_fetch_assoc($res) )
{
	$prof['countries'][$arr['ISO2']] = $arr['Country'];
}

$prof['height'][0] = "I prefer not to say";
$prof['height'][1] = "4'7\" (140cm) or below";
$prof['height'][2] = "4'8\" - 4'11\" (141-150cm)";
$prof['height'][3] = "5'0\" - 5'3\" (151-160cm)";
$prof['height'][4] = "5'4\" - 5'7\" (161-170cm)";
$prof['height'][5] = "5'8\" - 5'11\" (171-180cm)";
$prof['height'][6] = "6'0\" - 6'3\" (181-190cm)";
$prof['height'][7] = "6'4\" (191cm) or above";


$prof['bodytype'][0] = "I prefer not to say";
$prof['bodytype'][1] = "Average";
$prof['bodytype'][2] = "Ample";
$prof['bodytype'][3] = "Athletic";
$prof['bodytype'][4] = "Cuddly";
$prof['bodytype'][5] = "Slim";
$prof['bodytype'][6] = "Very Cuddly";


$prof['religion'][0] = "I prefer not to say";
$prof['religion'][1] = "Adventist";
$prof['religion'][2] = "Agnostic";
$prof['religion'][3] = "Atheist";
$prof['religion'][4] = "Baptist";
$prof['religion'][5] = "Buddhist";
$prof['religion'][6] = "Caodaism";
$prof['religion'][7] = "Catholic";
$prof['religion'][8] = "Christian";
$prof['religion'][9] = "Hindu";
$prof['religion'][10] = "Iskcon";
$prof['religion'][11] = "Jainism";
$prof['religion'][12] = "Jewish";
$prof['religion'][13] = "Methodist";
$prof['religion'][14] = "Mormon";
$prof['religion'][15] = "Moslem";
$prof['religion'][16] = "Orthodox";
$prof['religion'][17] = "Pentecostal";
$prof['religion'][18] = "Protestant";
$prof['religion'][19] = "Quaker";
$prof['religion'][20] = "Scientology";
$prof['religion'][21] = "Shinto";
$prof['religion'][22] = "Sikhism";
$prof['religion'][23] = "Spiritual";
$prof['religion'][24] = "Taoism";
$prof['religion'][25] = "Wiccan";
$prof['religion'][26] = "Other";


$prof['ethnicity'][0] = "I prefer not to say";
$prof['ethnicity'][1] = "African";
$prof['ethnicity'][2] = "African American";
$prof['ethnicity'][3] = "Asian";
$prof['ethnicity'][4] = "Caucasian";
$prof['ethnicity'][5] = "East Indian";
$prof['ethnicity'][6] = "Hispanic";
$prof['ethnicity'][7] = "Indian";
$prof['ethnicity'][8] = "Latino";
$prof['ethnicity'][9] = "Mediterranean";
$prof['ethnicity'][10] = "Middle Eastern";
$prof['ethnicity'][11] = "Mixed";


$prof['maritalstatus'][0] = "I prefer not to say";
$prof['maritalstatus'][1] = "Single";
$prof['maritalstatus'][2] = "Attached";
$prof['maritalstatus'][3] = "Divorced";
$prof['maritalstatus'][4] = "Married";
$prof['maritalstatus'][5] = "Separated";
$prof['maritalstatus'][6] = "Widow";


$prof['language'][0] = "English";
$prof['language'][1] = "Afrikaans";
$prof['language'][2] = "Arabic";
$prof['language'][3] = "Bulgarian";
$prof['language'][4] = "Burmese";
$prof['language'][5] = "Cantonese";
$prof['language'][6] = "Croatian";
$prof['language'][7] = "Danish";
$prof['language'][8] = "Dutch";
$prof['language'][9] = "Esperanto";
$prof['language'][10] = "Estonian";
$prof['language'][11] = "Finnish";
$prof['language'][12] = "French";
$prof['language'][13] = "German";
$prof['language'][14] = "Greek";
$prof['language'][15] = "Gujrati";
$prof['language'][16] = "Hebrew";
$prof['language'][17] = "Hindi";
$prof['language'][18] = "Hungarian";
$prof['language'][19] = "Icelandic";
$prof['language'][20] = "Indian";
$prof['language'][21] = "Indonesian";
$prof['language'][22] = "Italian";
$prof['language'][23] = "Japanese";
$prof['language'][24] = "Korean";
$prof['language'][25] = "Latvian";
$prof['language'][26] = "Lithuanian";
$prof['language'][27] = "Malay";
$prof['language'][28] = "Mandarin";
$prof['language'][29] = "Marathi";
$prof['language'][30] = "Moldovian";
$prof['language'][31] = "Nepalese";
$prof['language'][32] = "Norwegian";
$prof['language'][33] = "Persian";
$prof['language'][34] = "Polish";
$prof['language'][35] = "Portuguese";
$prof['language'][36] = "Punjabi";
$prof['language'][37] = "Romanian";
$prof['language'][38] = "Russian";
$prof['language'][39] = "Serbian";
$prof['language'][40] = "Spanish";
$prof['language'][41] = "Swedish";
$prof['language'][42] = "Tagalog";
$prof['language'][43] = "Taiwanese";
$prof['language'][44] = "Tamil";
$prof['language'][45] = "Telugu";
$prof['language'][46] = "Thai";
$prof['language'][47] = "Tongan";
$prof['language'][48] = "Turkish";
$prof['language'][49] = "Ukrainian";
$prof['language'][50] = "Urdu";
$prof['language'][51] = "Vietnamese";
$prof['language'][52] = "Visayan";


$prof['education'][0] = "I prefer not to say";
$prof['education'][1] = "High School graduate";
$prof['education'][2] = "Some college";
$prof['education'][3] = "College student";
$prof['education'][4] = "AA (2 years college)";
$prof['education'][5] = "BA/BS (4 years college)";
$prof['education'][6] = "Some grad school";
$prof['education'][7] = "Grad school student";
$prof['education'][8] = "MA/MS/MBA";
$prof['education'][9] = "PhD/Post doctorate";
$prof['education'][10] = "JD";


$prof['income'][0] = "I prefer not to say";
$prof['income'][1] = "$10,000/year and less";
$prof['income'][2] = "$10,000-$30,000/year";
$prof['income'][3] = "$30,000-$50,000/year";
$prof['income'][4] = "$50,000-$70,000/year";
$prof['income'][5] = "$70,000/year and more";


$prof['smoker'][0] = "I prefer not to say";
$prof['smoker'][1] = "No";
$prof['smoker'][2] = "Rarely";
$prof['smoker'][3] = "Often";
$prof['smoker'][4] = "Very often";


$prof['drinker'][0] = "I prefer not to say";
$prof['drinker'][1] = "No";
$prof['drinker'][2] = "Rarely";
$prof['drinker'][3] = "Often";
$prof['drinker'][4] = "Very often";

?>
