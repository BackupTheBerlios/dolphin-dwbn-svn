<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="form_inputs.xsl" />

<xsl:template match="urls" />

<xsl:template match="join">

	<div class="wnd_box">

			<div style="display:none;" id="js_code">

					var f = document.forms['orca_join_form'];
					var ret = true;
				
					<xsl:for-each select="join_form/*">								
						var val<xsl:value-of select="name()" /> = f.elements['<xsl:value-of select="name()" />'].value;
						if (!val<xsl:value-of select="name()" />.match(<xsl:value-of select="regexp" />))
						{
							var e = document.getElementById('f_err_<xsl:value-of select="name()" />');
							e.innerHTML = '<xsl:value-of select="err" />';
							e.style.display = 'inline';
							ret = false;
						}
						else
						{
							var e = document.getElementById('f_err_<xsl:value-of select="name()" />');
							e.style.display = 'none';
						}
					</xsl:for-each>
					
					if (ret)
					{
						orca_login.joinFormSubmit (
						<xsl:for-each select="join_form/*">						
							f.elements['<xsl:value-of select="name()" />'].value<xsl:if test="position() != last()">,</xsl:if>
						</xsl:for-each>);
					}

					ret = false;
				

			</div>			

			<div class="wnd_title">
				<h2>Join</h2>
			</div>

			<div class="wnd_content">

				<form name="orca_join_form" onsubmit="var s=document.getElementById('js_code').innerHTML; eval(s); return ret;">

					<div>
					
						<xsl:apply-templates select="join_form" />

					</div>

					<input type="submit" name="sbmt" value="sbmt" style="display:none;" />

					<input type="hidden" name="action" value="join_form_submit" />

					<div style="margin:30px; margin-right:100px; margin-top:0px;">				

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">				
							<a href="javascript:void(0);" onclick="f.hideHTML(); return false;"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_cancel.gif" />
							<b>Cancel</b>
						</div>

						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
							<a href="javascript:void(0);" onclick="var s=document.getElementById('js_code').innerHTML; eval(s); return ret;"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>Submit</b>
						</div>

					</div>
					
				</form>
		
				<br />

			</div>

		</div>

</xsl:template>

<xsl:template match="join_form">

	<xsl:for-each select="*">	

		<xsl:choose>

			<xsl:when test="type = 'text'">
				<xsl:call-template name="text" />
			</xsl:when>

		</xsl:choose>

	</xsl:for-each>

</xsl:template>

</xsl:stylesheet>


