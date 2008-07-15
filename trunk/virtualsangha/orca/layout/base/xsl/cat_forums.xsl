<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="rewrite.xsl" />

<xsl:template match="urls" />

<xsl:template match="forums/forum">

		<ul>
			<li class="tbl_f_forum">

				<xsl:choose>
					<xsl:when test="1 = @new">
						<img class="forum_icon" src="{/root/urls/img}forum_new.gif" />
					</xsl:when>
					<xsl:otherwise>
						<img class="forum_icon" src="{/root/urls/img}forum.gif" />
					</xsl:otherwise>
				</xsl:choose>

                <a style="display:block;" onclick="return f.selectForum('{uri}', 0);"><xsl:attribute name="href"><xsl:value-of select="$rw_forum" /><xsl:value-of select="uri" /><xsl:value-of select="$rw_forum_page" />0<xsl:value-of select="$rw_forum_ext" /></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a>
                <xsl:value-of select="desc" disable-output-escaping="yes" />


			</li>
			<li class="tbl_f_topic"><xsl:value-of select="topics" /></li>
			<li class="tbl_f_date"><xsl:value-of select="last" /></li>
		</ul>

</xsl:template>

</xsl:stylesheet>


