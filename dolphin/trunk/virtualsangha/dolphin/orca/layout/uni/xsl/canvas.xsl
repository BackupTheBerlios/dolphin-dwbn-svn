<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="canvas_includes.xsl" />
<xsl:include href="../../base/xsl/canvas_init.xsl" />

<xsl:template match="root">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
	<xsl:choose>
		<xsl:when test="string-length(/root/page/posts/topic/title) &gt; 0">
			<xsl:value-of select="/root/page/posts/topic/title" /> [L[Orca Forum]]
		</xsl:when>
		<xsl:when test="string-length(/root/page/topics/forum/title) &gt; 0">
			<xsl:value-of select="/root/page/topics/forum/title" /> [L[Orca Forum]]
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="title" />
		</xsl:otherwise>
	</xsl:choose>
</title>	

<xsl:element name="base">
	<xsl:attribute name="href"><xsl:value-of select="base"/></xsl:attribute>
</xsl:element>

<xsl:call-template name="canvas_includes" />

</head>
<xsl:element name="body">
	<xsl:attribute name="onload">if(!document.body) { document.body = document.getElementById('body'); }; h = new BxHistory(); document.h = h; return h.init('h'); </xsl:attribute>
    <xsl:attribute name="id">body</xsl:attribute>

    <xsl:call-template name="canvas_init" />


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
