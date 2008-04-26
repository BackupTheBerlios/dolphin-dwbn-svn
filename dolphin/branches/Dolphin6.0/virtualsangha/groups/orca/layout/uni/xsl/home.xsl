<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="cat_forums.xsl" />

<!--<xsl:include href="live_tracker.xsl" />-->
<xsl:template match="urls" />
<xsl:template match="logininfo" />

<xsl:template match="page">

	<div id="f_header">
		<h2>Forums Index</h2>
	</div>

	<div id="f_tbl">
		<ul class="tbl_hh">
			<li class="tbl_h_forum">Forums</li>
			<li class="tbl_h_topic">Topics</li>
			<li class="tbl_h_date">Latest Post</li>
		</ul>
		<xsl:apply-templates select="categs" />
	</div>

	<!--<xsl:apply-templates select="live_tracker" />-->
	
</xsl:template>


<xsl:template match="categ">		

	<xsl:element name="ul">
		<xsl:attribute name="style">height:52px;</xsl:attribute>
		<xsl:attribute name="id">cat<xsl:value-of select="@id" /></xsl:attribute>
		<li class="tbl_c_forum">
            <a href="?action=goto&amp;cat_id={@id}" onclick="return f.selectCat({@id}, 'cat{@id}');">
				<div class="colexp">
                    <xsl:if test="count(forums/forum) &gt; 0">
						<xsl:attribute name="style">background-position:0px -32px</xsl:attribute>
					</xsl:if>
					&#160;
				</div>
			</a>
            <a href="?action=goto&amp;cat_id={@id}" onclick="return f.selectCat({@id}, 'cat{@id}');"><xsl:value-of select="title" disable-output-escaping="yes" /></a>
		</li>
	</xsl:element>
		
    <xsl:if test="count(forums/forum) &gt; 0">
        <div><xsl:apply-templates select="forums/forum" /></div>
	</xsl:if>

</xsl:template>


</xsl:stylesheet>
