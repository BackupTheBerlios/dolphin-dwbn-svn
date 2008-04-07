<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="urls" />

<xsl:template match="cat">

	<div class="wnd_box">

			<div style="display:none;" id="js_code">
				var f = document.forms['orca_edit_cat'];
				orca_admin.editCatSubmit (f.elements['cat_id'].value, f.elements['cat_name'].value);
			</div>

			<div class="wnd_title">
				<h2>
					<xsl:if test="@cat_id &gt; 0">Edit group</xsl:if>
					<xsl:if test="0 = @cat_id">New group</xsl:if>
				</h2>
			</div>			

			<div class="wnd_content">

			<form name="orca_edit_cat" onsubmit="var x=document.getElementById('js_code').innerHTML; eval(x); return false;">

				<div>
					<input class="sh" type="text" name="cat_name" value="{cat_name}" />
				</div>

				<input type="hidden" name="cat_id" value="{@cat_id}" />
				<input type="hidden" name="action" value="edit_category_submit" />
				
				<div style="margin:0px; margin-right:50px; margin-top:12px; margin-bottom:40px;">

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


