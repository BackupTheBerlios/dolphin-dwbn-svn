<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="/">

		<div style="padding:10px;">
			<h1>
				[L[New topic created]]
			</h1>
			<xsl:element name="a">
				<xsl:attribute name="href">javascript:void(0);</xsl:attribute>
				<xsl:attribute name="onclick">f.selectForum('<xsl:value-of select="forum/uri"/>', 0)</xsl:attribute>
				[L[return to forum index]]
			</xsl:element>
		</div>

</xsl:template>

</xsl:stylesheet>


