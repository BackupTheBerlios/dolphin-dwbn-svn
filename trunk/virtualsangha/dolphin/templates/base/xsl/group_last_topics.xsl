<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="urls" />

<!--Only for admin login-->
<xsl:template match="forum_access">
	<div style="text-align:center;">
		Please follow to the forum to view all forum topics
	</div>
</xsl:template>

<xsl:template match="topics">

	<table class="group_topics">
		<tr class="group_topics_header">
			<th class="group_topics_col_topics">Latest topics</th>
			<th class="group_topics_col_posts">Posts</th>
			<th class="group_topics_col_author">Author</th>
			<th class="group_topics_col_reply">Latest reply</th>
		</tr>
		<xsl:apply-templates select="topic" />
	</table>

</xsl:template>

<xsl:template match="topic">
		
		<xsl:element name="tr">
			<xsl:attribute name="class">
				group_topic_row_<xsl:value-of select="@topic_class" />
			</xsl:attribute>
			
			<td class="group_topics_col_topics">		
				<b>
					<xsl:element name="a">
                        <xsl:attribute name="href">groups/orca/topic/<xsl:value-of select="uri" />.htm</xsl:attribute>
						<xsl:value-of select="title" /> 
					</xsl:element>
				</b>
				<br />
				<xsl:value-of select="desc" /> 
			</td>
			
			<td class="group_topics_col_posts"><xsl:value-of select="count" /></td>
			
			<td class="group_topics_col_author">
				<b><xsl:value-of select="first_u" /></b>
				<br />
				<xsl:value-of select="first_d" />
			</td>
			
			<td class="group_topics_col_reply">
				<b><xsl:value-of select="last_u" /></b>
				<br />
				<xsl:value-of select="last_d" />
			</td>
		</xsl:element>

</xsl:template>

</xsl:stylesheet>


