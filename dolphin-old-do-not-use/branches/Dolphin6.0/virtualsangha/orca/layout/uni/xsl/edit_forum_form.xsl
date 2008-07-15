<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="urls" />

<xsl:template match="forum">

	<div class="wnd_box">

			<div style="display:none;" id="js_code">
					var f = document.forms['orca_edit_forum'];						
					orca_admin.editForumSubmit (
						f.elements['cat_id'].value, 
						f.elements['forum_id'].value, 
						f.elements['forum_title'].value,
						f.elements['forum_desc'].value,
						f.elements['forum_type'].value
					);
			</div>			

			<div class="wnd_title">
				<h2>
					<xsl:if test="@forum_id &gt; 0">Edit forum!</xsl:if>
					<xsl:if test="0 = @forum_id">New forum</xsl:if>
				</h2>
			</div>			

			<div class="wnd_content">

			<form name="orca_edit_forum" onsubmit="var x=document.getElementById('js_code').innerHTML; eval(x); return false;">

				<div>

					<fieldset class="form_field_row"><legend>Forum title:</legend>
						<input class="sh" type="text" name="forum_title" value="{title}" /> 
					</fieldset>
					<br /><br />

					<fieldset class="form_field_row"><legend>Forum description:</legend>
						<input class="sh" type="text" name="forum_desc" value="{desc}" /> 
					</fieldset>
					<br /><br />					

					<fieldset class="form_field_row"><legend>Forum type:</legend>
						<select name="forum_type">
							<xsl:element name="option">
								<xsl:attribute name="value">public</xsl:attribute>
								<xsl:if test="'public' = type">
									<xsl:attribute name="selected">selected</xsl:attribute>
								</xsl:if>
								public
							</xsl:element>
							<xsl:element name="option">
								<xsl:attribute name="value">private</xsl:attribute>
								<xsl:if test="'private' = type"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								private
							</xsl:element>
						</select>
					</fieldset>
					<br /><br />										
				</div>

				<input type="hidden" name="forum_id" value="{@forum_id}" />
				<input type="hidden" name="cat_id" value="{cat_id}" />
				<input type="hidden" name="action" value="edit_forum_submit" />

				<div style="margin:0px; margin-right:80px; margin-top:12px; margin-bottom:40px;">

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">				
							<a href="javascript:void(0);" onclick="f.hideHTML(); return false;"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_cancel.gif" />
							<b>Cancel</b>
						</div>

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">
							<a href="javascript:void(0);" onclick="var x=document.getElementById('js_code').innerHTML; eval(x); return false;"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>Submit</b>
						</div>

				</div>

			</form>

			</div>

		</div>

</xsl:template>

</xsl:stylesheet>


