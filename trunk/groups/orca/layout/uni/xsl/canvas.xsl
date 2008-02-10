<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="root">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
	<xsl:choose>
		<xsl:when test="string-length(/root/page/posts/topic/title) &gt; 0">
			<xsl:value-of select="/root/page/posts/topic/title" /> :: Orca Forum
		</xsl:when>
		<xsl:when test="string-length(/root/page/topics/forum/title) &gt; 0">
			<xsl:value-of select="/root/page/topics/forum/title" /> :: Orca Forum
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="title" />
		</xsl:otherwise>
	</xsl:choose>
</title>	

<xsl:element name="base">
	<xsl:attribute name="href"><xsl:value-of select="base"/></xsl:attribute>
</xsl:element>

<link type="text/css" rel="stylesheet" href="{/root/urls/css}main.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}templates/tmpl_uni/css/general.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}templates/tmpl_uni/css/anchor.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}plugins/tiny_mce/plugins/contextmenu/css/contextmenu.css" />
<link type="text/css" rel="stylesheet" href="{/root/url_dolphin}plugins/tiny_mce/themes/advanced/css/editor_ui.css" />
<!--
<script language="javascript" type="text/javascript" src="js/util.js"></script>
<script language="javascript" type="text/javascript" src="js/BxError.js"></script>
<script language="javascript" type="text/javascript" src="js/BxXmlRequest.js"></script>
<script language="javascript" type="text/javascript" src="js/BxXslTransform.js"></script>
<script language="javascript" type="text/javascript" src="js/BxForum.js"></script>
<script language="javascript" type="text/javascript" src="js/BxEditor.js"></script>
<script language="javascript" type="text/javascript" src="js/BxHistory.js"></script>
<script language="javascript" type="text/javascript" src="js/BxLogin.js"></script>
<xsl:if test="1 = /root/logininfo/admin"><script language="javascript" type="text/javascript" src="js/BxAdmin.js"></script></xsl:if>
-->
<script language="javascript" type="text/javascript" src="js/loader.php"></script>
<script language="javascript" type="text/javascript" src="{/root/url_dolphin}plugins/tiny_mce/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript" src="{/root/url_dolphin}inc/js/functions.js"></script>
</head>
<xsl:element name="body">
	<xsl:attribute name="onload">if(!document.body) { document.body = document.getElementById('body'); }; h = new BxHistory(); document.h = h; return h.init('h'); </xsl:attribute>
    <xsl:attribute name="id">body</xsl:attribute>

    <script type="text/javascript">

        <xsl:if test="'client' = /root/urls/xsl_mode">
        document.write = function (s) { };
        </xsl:if>

		tinyMCE_GZ.loadFile = function(u) 
		{
			var x, ex;
            if ( window.XMLHttpRequest )
            {
                x = new XMLHttpRequest();
            }
            else
            {
    			try {
	    			x = new ActiveXObject("Microsoft.XMLHTTP");
		    	} catch (ex) {
			    	x = new ActiveXObject("Msxml2.XMLHTTP");
    			}
            }
			x.open("GET", u.replace(/%2C/g, ','), false);
			x.send(null);
			this.scriptData = x.responseText;			
		};


        tinyMCE_GZ.init({
        	plugins : 'table,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,searchreplace,print,contextmenu',
        	themes : 'advanced',
        	languages : 'en',
        	disk_cache : true,
        	debug : false
        });
        eval(tinyMCE_GZ.scriptData);
        
    </script>

	<script language="javascript" type="text/javascript">

		function orcaSetupContent (id, body, doc) {	}

		tinyMCE.init({
            entity_encoding : "raw",
			mode : "exact",
			elements : "tinyEditor",
			theme : "advanced",
			gecko_spellcheck : true,
			content_css : "<xsl:value-of select="/root/urls/css" />blank.css",

			remove_linebreaks : true,

			setupcontent_callback : "orcaSetupContent",

			plugins : "table,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,searchreplace,print,contextmenu",
			theme_advanced_buttons1_add : "fontsizeselect,separator,forecolor,backcolor",
			theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom",
			theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
			theme_advanced_buttons3_add_before : "tablecontrols,separator",
			theme_advanced_buttons3_add : "emotions,iespell,flash,separator,print",
			theme_advanced_disable : "charmap",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_path_location : "bottom",
			plugin_insertdate_dateFormat : "%Y-%m-%d",
			plugin_insertdate_timeFormat : "%H:%M:%S",
			extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
			});

	</script>

	<script language="javascript" type="text/javascript">

		var urlXsl = '<xsl:value-of select="/root/urls/xsl" />';
		var urlImg = '<xsl:value-of select="/root/urls/img" />';
        var defTitle = '<xsl:value-of select="/root/title" />';
        var isLoggedIn = '<xsl:value-of select="/root/logininfo/username" />'.length ? true : false;

        var xsl_mode = '<xsl:value-of select="/root/urls/xsl_mode" />';

		var f = new Forum ('<xsl:value-of select="base"/>', <xsl:value-of select="min_point"/>);
		document.f = f;
		var orca_login = new Login ('<xsl:value-of select="base"/>', f);
		document.orca_login = orca_login;
		<xsl:if test="1 = /root/logininfo/admin">
			var orca_admin = new Admin ('<xsl:value-of select="base"/>', f);
			document.orca_admin = orca_admin;
		</xsl:if>
        
	</script>

	<xsl:value-of select="/root/header" disable-output-escaping="yes" /> 

				<div id="orca_main">

						<xsl:if test="not(string-length(page/onload))">
						<xsl:apply-templates select="page" />
						</xsl:if>

				</div>
			
	<xsl:value-of select="/root/footer" disable-output-escaping="yes" /> 

</xsl:element>
</html>
</xsl:template>

</xsl:stylesheet>
