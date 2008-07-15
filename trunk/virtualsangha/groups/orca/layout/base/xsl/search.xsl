<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="default_access_denied.xsl" />
<xsl:include href="default_error2.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />

<xsl:template match="search">

    <xsl:call-template name="breadcrumbs">
        <xsl:with-param name="link1">
            <a href="javascript:void(0);" onclick="return f.showSearch()">[L[Search]]</a> 
        </xsl:with-param>
        <xsl:with-param name="link0">
            <a href="javascript:void(0);" onclick="return f.showSearch()">[L[New Search]]</a> 
        </xsl:with-param>
    </xsl:call-template>

	<div id="f_header">
		<h2>[L[Search Results For:]] '<xsl:value-of select="search_text" />'</h2>
	</div>

		<div class="sr_box">

			<xsl:if test="0 = count(sr)">
				<div style="text-align:center;">
					[L[There are no search results.]] <br />
					[L[Please try search again.]]
				</div>
			</xsl:if>
			<xsl:if test="0 != count(sr)">
				<div id="f_tbl">
					<ul class="tbl_hh">
						<li class="tbl_h_forum" style="width:515px;">[L[Topic]]</li>
						<li class="tbl_h_topic" style="width:80px;">[L[Author]]</li>
						<li class="tbl_h_date">[L[Date]]</li>
					</ul>
					<xsl:apply-templates select="sr" />
					<ul style="height:1px; overflow:hidden;">
						<li>&#160;</li>
					</ul>
				</div>
			</xsl:if>

		</div> 

</xsl:template>


<xsl:template match="sr">
	<ul style="height:37px;">
		<li class="tbl_f_forum" style="width:490px; overflow:hidden; padding:0; padding-left:27px; height:36px;">

			<xsl:if test="0 != string-length(p)">
                <xsl:element name="a">
                    <xsl:attribute name="class">colexp2</xsl:attribute>
					<xsl:attribute name="href">javascript: void(0);</xsl:attribute>
					<xsl:attribute name="onclick">return f.expandPost('p_<xsl:value-of select="p/@id" />');</xsl:attribute>
					<xsl:element name="div">
						<xsl:attribute name="style">top:5px; left:7px;</xsl:attribute>
						<xsl:attribute name="class">colexp2</xsl:attribute>
						<xsl:if test="count(forum) &gt; 0">
							<xsl:attribute name="style">background-position:0px -13px</xsl:attribute>
						</xsl:if>
						&#160;
					</xsl:element>
				</xsl:element>
			</xsl:if>
			
			<xsl:value-of select="c" /> 
			<xsl:if test="0 = string-length(p)">
				&#160;<img src="{/root/urls/img}a.gif" style="width:auto; height:auto; border:none;" />&#160; 
			</xsl:if>
			<xsl:value-of select="f" /> 

			<br />
			<xsl:element name="a">
				<xsl:attribute name="style">font-size:12px; display:block; margin-bottom:6px;</xsl:attribute>
				<xsl:attribute name="href">?action=goto&amp;topic_id=<xsl:value-of select="t/@uri" /></xsl:attribute>
				<xsl:attribute name="onclick">return f.selectTopic(<xsl:value-of select="t/@uri" />);</xsl:attribute>
							
                <xsl:choose>
                    <xsl:when test="(/root/page or /root/urls/xsl_mode = 'server') and /root/urls/xsl_mode != 'client'">
                        <xsl:value-of select="t" disable-output-escaping="yes" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:copy-of select="t" />
                    </xsl:otherwise>
				</xsl:choose>
			
			</xsl:element>

			<div id="p_{p/@id}" class="post_text_sr">

				<xsl:choose>
                    <xsl:when test="/root/urls/xsl_mode = 'server'">
						<xsl:value-of select="p" disable-output-escaping="yes" />
					</xsl:when>
                    <xsl:otherwise>
                        <xsl:choose>
                            <xsl:when test="system-property('xsl:vendor')='Transformiix'">
								<div id="{p/@id}_foo" style="display:none;"><xsl:value-of select="p" /></div>
                                <script type="text/javascript">
									var id = '<xsl:value-of select="p/@id" />';
                                    <![CDATA[
                                    var s = document.getElementById(id + '_foo').innerHTML;
                                    s = s.replace(/&#160;/gm, ' ');
                                    s = s.replace(/\x26gt;/gm, '\x3e');
                                    s = s.replace(/\x26lt;/gm, '\x3c');
                                    document.getElementById('p_' + id).innerHTML = s;
                                    ]]>
                                </script>
                            </xsl:when>
                            <xsl:when test="system-property('xsl:vendor')='Microsoft'">
                                <xsl:value-of select="p" disable-output-escaping="yes" />
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="p" disable-output-escaping="yes" />
                            </xsl:otherwise>
                        </xsl:choose>
					</xsl:otherwise>
				</xsl:choose>

			</div>
		</li>
		<li class="tbl_f_topic" style="width:88px; height:36px;"><xsl:value-of select="@user" /></li>
		<li class="tbl_f_date" style="height:36px;"><xsl:value-of select="@date" /></li>
	</ul>
</xsl:template>


</xsl:stylesheet>


