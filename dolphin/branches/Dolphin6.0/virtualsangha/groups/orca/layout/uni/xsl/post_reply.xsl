<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="default_access_denied.xsl" />
<xsl:include href="smiles.xsl" />

<xsl:template match="urls" />

<xsl:template match="new_topic">

		<div class="p_reply">

			<h2>Post Reply:</h2>

			<form method="post" action="index.php" name="post_reply" target="post_reply" onsubmit="return f.checkPostTopicValues(null, this.topic_text, false)">

					<input type="hidden" name="action" value="post_reply" />

					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">forum_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="forum/id" /></xsl:attribute>
					</xsl:element>

					<xsl:element name="input">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">topic_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="topic/id" /></xsl:attribute>		
					</xsl:element>

					<span class="err" style="display:none" id="err_topic_text">Please enter from 5 to 640000 symbols</span>

					<!-- <input type="hidden" name="topic_text" /> -->

					<table>
					<tr>
					<td valign="top">

						<textarea id="tinyEditor" name="topic_text" style="width:740px; height:316px;">&#160;</textarea>

						<!--	
						<xsl:element name="iframe">
							<xsl:attribute name="style">width:540px; height:216px;</xsl:attribute>
							<xsl:attribute name="id">edit</xsl:attribute>
							<xsl:attribute name="name">edit</xsl:attribute>
							<xsl:attribute name="src">src.html</xsl:attribute>
							<xsl:attribute name="onload">ed.init()</xsl:attribute>
							.
						</xsl:element>
						-->
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
							<a href="javascript:void(0);" onclick="return f.cancelReply()"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_cancel.gif" />
							<b>Cancel</b>
						</div>

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
							<a href="javascript:void(0);" onclick="tinyMCE.triggerSave(); document.forms['post_reply'].submit();"><img src="{/root/urls/img}button_l.gif" /></a>							
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>Submit</b>
						</div>
						
					</div>
					<br />
					<br />
<!--				</xsl:if> -->

			</form>

			<iframe width="1" height="1" border="0" name="post_reply" style="border:none;" ></iframe>

		</div>

</xsl:template>

</xsl:stylesheet>


