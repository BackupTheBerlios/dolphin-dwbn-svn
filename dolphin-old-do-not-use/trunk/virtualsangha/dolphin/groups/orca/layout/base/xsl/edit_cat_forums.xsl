<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="rewrite.xsl" />
<xsl:template match="urls" />

<xsl:template match="forum">

		<ul>
			<li class="tbl_f_forum">
                <a href="{$rw_forum}{uri}{$rw_forum_page}0{$rw_forum_ext}" onclick="return f.selectForum('{uri}', 0);"><xsl:value-of select="title" disable-output-escaping="yes" /></a>
				<br />
                <xsl:value-of select="desc" disable-output-escaping="yes" />                

				<div style="position:absolute; right:8px; top:5px; width:80px;">
					<div title="[L[edit]]" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
						<a href="javascript:void(0);" onclick="orca_admin.editForum({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
						<img src="{/root/urls/img}btn_icon_edit.gif" />
					</div>
					<div title="[L[delete]]" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
						<a href="javascript:void(0);" onclick="orca_admin.delForum({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
						<img src="{/root/urls/img}btn_icon_delete.gif" />
					</div>					
				</div>					

			</li>
			<li class="tbl_f_topic"><xsl:value-of select="topics" /></li>
			<li class="tbl_f_date"><xsl:value-of select="last" /></li>			
		</ul>

</xsl:template>

</xsl:stylesheet>


