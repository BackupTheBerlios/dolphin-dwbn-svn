<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="default_access_denied.xsl" />
<xsl:include href="smiles.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />

<xsl:template match="new_topic">


    <xsl:call-template name="breadcrumbs">
        <xsl:with-param name="link1">
            <a href="?action=goto&amp;cat_id={cat/id}" onclick="return f.selectForumIndex({cat/id})"><xsl:value-of select="cat/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
        <xsl:with-param name="link2">
            <a href="?action=goto&amp;forum_id={forum/id}&amp;start=0" onclick="return f.selectForum({forum/id}, 0);"><xsl:value-of select="forum/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
    </xsl:call-template>

	<div id="f_header">
        <h2><xsl:value-of select="forum/title" disable-output-escaping="yes" /></h2> 
        <span><xsl:value-of select="forum/desc" disable-output-escaping="yes" /></span>
	</div>


		<div style="padding-top:15px; margin-left:5px; margin-right:0px; margin-top:20px; border-top:1px dotted #B5B5B5; width:757px;">						

				<form name="new_topic" method="post" action="index.php" target="post_new_topic" onsubmit="return f.checkPostTopicValues(this.topic_subject, this.topic_text, true);">

					<input type="hidden" name="action" value="post_new_topic" />

					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">forum_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="forum/id" /></xsl:attribute>
					</xsl:element>

					Topic subject:  <input class="sh" type="text" name="topic_subject" size="50" /> 
					<span class="err" style="display:none" id="err_topic_subject">Please enter <a href="javascript:void(0);" onclick="f.showValidChars()">valid characters</a> from 5 to 50 symbols</span>
					
					<xsl:if test="1 = @sticky">
					<span class="sticky"><input type="checkbox" name="topic_sticky" id="sticky" /><label for="sticky">Sticky</label></span>
					</xsl:if>

					<br /><br />


					Topic text: <span class="err" style="display:none" id="err_topic_text">Please enter from 5 to 640000 symbols</span>
					<br />
					
					<!-- <input type="hidden" name="topic_text" /> -->

					<table>
					<tr>
					<td valign="top">


						<textarea id="tinyEditor" name="topic_text" style="width:750px; height:316px;">&#160;</textarea>

					</td>
					<!--
					<td valign="top">
						<div class="smiles">
							To add a smiley drag it to the text field
							<xsl:apply-templates select="/root/urls/smiles" />
						</div>
					</td>
					-->
					</tr>					
					</table>

					<br /><br />					

					<div style="height:25px; width:210px; position:relative;">

						&#160;						

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">				
							<a href="javascript:void(0);" onclick="return f.cancelNewTopic ({forum/id}, 0)"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_cancel.gif" />
							<b>Cancel</b>
						</div>

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
							<a href="javascript:void(0);" onclick="tinyMCE.triggerSave(); if (!f.checkPostTopicValues(document.forms['new_topic'].topic_subject, document.forms['new_topic'].topic_text, true)) return false; document.forms['new_topic'].submit();"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>Submit</b>
						</div>
						
					</div>
					<br />
					<br />

				</form>

			<iframe width="1" height="1" border="0" name="post_new_topic" style="border:none;" />

		</div>

</xsl:template>

</xsl:stylesheet>


