<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template name="canvas_includes">

<link type="text/css" rel="stylesheet" href="{/root/urls/css}main.css" /> 
<link type="text/css" rel="stylesheet" href="{/root/urls/editor}plugins/contextmenu/css/contextmenu.css" />
<link type="text/css" rel="stylesheet" href="{/root/urls/editor}themes/advanced/css/editor_ui.css" />

<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}templates/tmpl_{/root/skin_dolphin}/css/general.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}templates/tmpl_{/root/skin_dolphin}/css/anchor.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}plugins/tiny_mce/plugins/contextmenu/css/contextmenu.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}plugins/tiny_mce/themes/advanced/css/editor_ui.css" />


<script language="javascript" type="text/javascript" src="{/root/urls/js}util.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxError.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxXmlRequest.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxXslTransform.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxForum.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxEditor.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxHistory.js"></script>
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxLogin.js"></script>
<xsl:if test="1 = /root/logininfo/admin">
<script language="javascript" type="text/javascript" src="{/root/urls/js}BxAdmin.js"></script>
</xsl:if>

<!--
<script language="javascript" type="text/javascript" src="{/root/urls/js}loader.php"></script>
-->

<script language="javascript" type="text/javascript" src="{/root/urls/editor}tiny_mce_gzip.js"></script>

<script language="javascript" type="text/javascript" src="{/root/url_dolphin}inc/js/functions.js"></script>

<script language="javascript" type="text/javascript" src="{/root/url_dolphin}plugins/jquery/jquery.js"></script>
<script language="javascript" type="text/javascript" src="{/root/url_dolphin}inc/js/jquery.dolTopMenu.js"></script>

<script type="text/javascript" language="javascript">
	$(document).ready( function() { //onload
		//apply top menu functionality
		$( 'table.topMenu' ).dolTopMenu();
	} );
</script>

</xsl:template>

</xsl:stylesheet>
