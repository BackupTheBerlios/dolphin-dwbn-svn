<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="rewrite.xsl" />
<xsl:include href="default_access_denied.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />

<xsl:template match="topics">

    <xsl:call-template name="breadcrumbs">
        <xsl:with-param name="link1">
            <a href="{$rw_cat}{cat/uri}{$rw_cat_ext}" onclick="return f.selectForumIndex('{cat/uri}')"><xsl:value-of select="cat/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
    </xsl:call-template>

	<div id="f_header">
        <h2 id="forum_title"><xsl:value-of select="forum/title" disable-output-escaping="yes" /></h2>
	</div>
    <div id="f_desc"><xsl:value-of select="forum/desc" disable-output-escaping="yes" /></div>

    <xsl:if test="count(cat)">		

		<div class="f_buttons">
            
    		<xsl:variable name="onclick" select="concat('return f.newTopic(&#34;', forum/uri, '&#34;);')" />
	    	<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">
		    	<a href="javascript:void(0);" onclick="{$onclick}"><img src="{/root/urls/img}button_l.gif" /></a>
			    <img src="{/root/urls/img}btn_icon_new_topic.gif" />
				<b>[L[New Topic]]</b>
    		</div>

            <div class="f_buttons_icn">
			    <div title="permalink" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
		    		<a href="{/root/base}{$rw_forum}{forum/uri}{$rw_forum_page}0{$rw_forum_ext}" target="_blank"><img src="{/root/urls/img}button_l.gif" /></a>
	    			<img src="{/root/urls/img}btn_icon_plink.gif" />
    			</div>
			    <div title="rss feed" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
                    <a href="{/root/base}{$rw_rss_forum}{forum/uri}{$rw_rss_forum_ext}" target="_blank"><img src="{/root/urls/img}button_l.gif" /></a>
	    			<img src="{/root/urls/img}btn_icon_rss.gif" />
                </div>
            </div>

        </div>

    </xsl:if>

	<div id="reply_container">&#160;</div>

	<div id="f_tbl">
		<ul class="tbl_hh">
			<li class="tbl_hh_topic">[L[Topics]]</li>
			<li class="tbl_hh_posts">[L[Posts]]</li>
			<li class="tbl_hh_date">[L[Author]]</li>
			<li class="tbl_hh_date2">[L[Latest Reply]]</li>
		</ul>
		<xsl:apply-templates select="topic" />
	</div>

	<div class="pages">
		[L[Pages:]] <xsl:apply-templates select="pages" />
	</div>

</xsl:template>

<xsl:template match="topic">

<ul>
	<li class="tbl_ff_topic">		

		<xsl:choose>
			<xsl:when test="1 = @new and 0 &lt; @sticky">
				<img src="{/root/urls/img}sticky_topic_new.gif" />
			</xsl:when>
			<xsl:when test="0 = @new and 0 &lt; @sticky">
				<img src="{/root/urls/img}sticky_topic.gif" />
			</xsl:when>
			<xsl:when test="1 = @new and 0 = @sticky and 0 = @locked">
				<img src="{/root/urls/img}topic_new.gif" />
            </xsl:when>
			<xsl:when test="1 = @new and 0 = @sticky and 1 = @locked">
				<img src="{/root/urls/img}locked_topic_new.gif" />
            </xsl:when>            
			<xsl:when test="0 = @new and 0 = @sticky and 1 = @locked">
				<img src="{/root/urls/img}locked_topic.gif" />
			</xsl:when>                        
			<xsl:otherwise>
				<img src="{/root/urls/img}topic.gif" />
			</xsl:otherwise>
		</xsl:choose>

        <a href="{$rw_topic}{uri}{$rw_topic_ext}" onclick="return f.selectTopic('{uri}');"><xsl:value-of select="title" disable-output-escaping="yes" /></a>
        <br />
        <xsl:value-of select="desc" disable-output-escaping="yes" />        
	</li>
	<li class="tbl_ff_posts"><xsl:value-of select="count" /></li>
	<li class="tbl_ff_date"><xsl:value-of select="first_u" /><br /><xsl:value-of select="first_d" /></li>
	<li class="tbl_ff_date2"><xsl:value-of select="last_u" /><br /><xsl:value-of select="last_d" /></li>
</ul>

</xsl:template>

<xsl:template match="pages/p">
	&#160; 
	<xsl:if test="@c = 0">
        <a href="{$rw_forum}{../../forum/uri}{$rw_forum_page}{@start}{$rw_forum_ext}" onclick="return document.f.selectForum ('{../../forum/uri}', '@start')"><xsl:value-of select="." /></a>
	</xsl:if>
	<xsl:if test="@c = 1">
		<xsl:value-of select="." />
	</xsl:if>
</xsl:template>

</xsl:stylesheet>


