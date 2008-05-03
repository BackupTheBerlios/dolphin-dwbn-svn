<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="urls" />

<xsl:template match="edit_post">

	<form name="edit_post_{post_id}" action="index.php" method="post" target="post_actions" onreset="f.editPostCancel({post_id}); return false;" onsubmit="tinyMCE.triggerSave(); var node = document.getElementById({post_id}); if (node.parentNode.style._height) node.parentNode.style.height = node.parentNode.style._height; tinyMCE.execCommand('mceRemoveControl', false, 'tinyEditor_{post_id}'); this.post_text.style.visible = false; return true;">
		
	<div class="edit_post">

		<table>
			<tr>
			<td valign="top">

				<textarea id="tinyEditor_{post_id}" name="post_text" style="width:611px; height:216px;">&#160;</textarea>

			</td>

			</tr>					
		</table>		

		<br />
		<input type="hidden" name="action" value="edit_post" /> 
		<input type="hidden" name="post_id" value="{post_id}" /> 
		<input type="hidden" name="topic_id" value="{topic_id}" />

		<div style="height:25px; width:210px; position:relative; background-color:white;">
			&#160;

			<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">				
				<a href="javascript:void(0);" onclick="if (confirm('Are you sure ?')) document.forms['edit_post_{post_id}'].reset();"><img src="{/root/urls/img}button_l.gif" /></a>
				<img src="{/root/urls/img}btn_icon_cancel.gif" />
				<b>[L[Cancel]]</b>
			</div>

			<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
				<a href="javascript:void(0);" onclick="document.forms['edit_post_{post_id}'].onsubmit(); document.forms['edit_post_{post_id}'].submit();"><img src="{/root/urls/img}button_l.gif" /></a>
				<img src="{/root/urls/img}btn_icon_submit.gif" />
				<b>[L[Submit]]</b>
			</div>
				
		</div>		

	</div>

	</form>

</xsl:template>

</xsl:stylesheet>


