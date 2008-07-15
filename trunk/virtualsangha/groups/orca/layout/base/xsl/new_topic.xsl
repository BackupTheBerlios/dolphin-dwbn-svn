<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="rewrite.xsl" />
<xsl:include href="default_access_denied.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />

<xsl:template match="new_topic">


    <xsl:call-template name="breadcrumbs">
        <xsl:with-param name="link1">
            <a href="{$rw_cat}{cat/uri}{$rw_cat_ext}" onclick="return f.selectForumIndex('{cat/uri}')"><xsl:value-of select="cat/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
        <xsl:with-param name="link2">
            <a href="{$rw_forum}{forum/uri}{$rw_forum_page}0{$rw_forum_ext}" onclick="return f.selectForum('{forum/uri}', 0);"><xsl:value-of select="forum/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
    </xsl:call-template>

	<div id="f_header">
        <h2><xsl:value-of select="forum/title" disable-output-escaping="yes" /></h2> 
        <span><xsl:value-of select="forum/desc" disable-output-escaping="yes" /></span>
	</div>


		<div style="padding-top:15px; margin-left:5px; margin-right:0px; margin-top:20px; border-top:1px dotted #B5B5B5; ">

				<form name="new_topic" method="post" action="index.php" target="post_new_topic" onsubmit="return f.checkPostTopicValues(this.topic_subject, this.topic_text, true);">

					<input type="hidden" name="action" value="post_new_topic" />

					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">forum_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="forum/id" /></xsl:attribute>
					</xsl:element>

					[L[Topic subject:]]  <input class="sh" type="text" name="topic_subject" size="50" /> 
					<span class="err" style="display:none" id="err_topic_subject">[L[Topic subject Error]]</span>
					
					<xsl:if test="1 = @sticky">
					<span class="sticky"><input type="checkbox" name="topic_sticky" id="sticky" /><label for="sticky">[L[Sticky]]</label></span>
					</xsl:if>

					<br /><br />


					[L[Topic text:]] <span class="err" style="display:none" id="err_topic_text">[L[Topic text Error]]</span>
					<br />

					<table>
					<tr>
					<td valign="top">


						<textarea id="tinyEditor" name="topic_text" style="width:750px; height:316px;">&#160;</textarea>

					</td>

					</tr>					
					</table>

					<div style="height:25px; margin:10px 0 20px 0; position:relative;">

						<div style="float:left;" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
							<a href="javascript:void(0);" onclick="tinyMCE.triggerSave(); if (!f.checkPostTopicValues(document.forms['new_topic'].topic_subject, document.forms['new_topic'].topic_text, true)) return false; document.forms['new_topic'].submit();"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>[L[Submit]]</b>
						</div>

						<div style="float:left;" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">
							<a href="javascript:void(0);" onclick="return f.cancelNewTopic ('{forum/uri}', 0)"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_cancel.gif" />
							<b>[L[Cancel]]</b>
						</div>

					</div>

				</form>

            <iframe frameborder="0" border="0" name="post_new_topic" style="border:none; padding:0; margin:0; background-color:transparent; width:0px; height:0px;">&#160;</iframe>
		</div>

</xsl:template>

</xsl:stylesheet>


