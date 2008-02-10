<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="urls" />
<xsl:template match="logininfo" />

<xsl:template match="page">

	<div id="f_header">
        <h2>
            Manage Forum
            <span>(<a href="javascript: f.selectForumIndex(); void(0);">Forums Index</a>)</span>
        </h2> 
    </div>

    <xsl:if test="invalid_license">
        
        <div id="warn" style="background-color:#CC3333; color:#FFFFFF; padding:25px; font-size:12px;">

            <h2 style="font-size:25px;">This Copy Of Orca Is Not Registered</h2>

            <br/>

            Please, go to your <a style="color:#FFFFFF;" href="http://www.boonex.com/unity/">Unity Account</a> to generate a free license. At Unity 
            you may track your licenses, promote your site and download new 
            software - all for free.
            <br/>
            
            <div style="font-size:130%; font-weight:bold;">

                <br/><br/>
                <a style="color:#FFFFFF;" href="http://www.boonex.com/unity/">Go To Unity</a> To Generate Free License
                <br/><br/>

                <a style="color:#FFFFFF;" href="https://www.boonex.com/payment.php?product=Orca">Buy Link-Free License</a> For One Year
                <br/><br/>

                <a style="color:#FFFFFF;" href="javascript:void(0);" onclick="document.getElementById('warn').style.display = 'none';">Continue</a> Using Unregistered Orca
                <br /><br />

                <iframe width="1" height="1" border="0" name="register_orca" style="border:none;">&#160;</iframe>
                <form method="post" action="" target="register_orca">
                    Input License:
                    <input type="hidden" name="action" value="register_orca" />
                    <input type="text" size="10" name="license_code" />
                    <input type="submit" value="Register" />
                </form>

            </div>


        </div>

    </xsl:if>

    <div class="f_buttons">
		<div class="f_buttons" style="top:1px;">			
			<div title="new group" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">
				<a href="javascript:void(0);" onclick="orca_admin.newCat()"><img src="{/root/urls/img}button_l.gif" /></a>
				<img src="{/root/urls/img}btn_icon_new_cat.gif" />
				<b>New Group</b>
			</div>
		</div>
	</div>

	<div id="f_tbl">
		<ul class="tbl_hh">
			<li class="tbl_h_forum">Forums</li>
			<li class="tbl_h_topic">Topics</li>
			<li class="tbl_h_date">Latest Post</li>
		</ul>
		<xsl:apply-templates select="categs" />
	</div>
	
</xsl:template>

<xsl:template match="categ">		

	<xsl:element name="ul">
		<xsl:attribute name="style">height:52px;</xsl:attribute>
		<xsl:attribute name="id">cat<xsl:value-of select="@id" /></xsl:attribute>

		<li class="tbl_c_forum">
            <a href="?action=goto&amp;cat_id={@id}&amp;admin=1" onclick="return orca_admin.selectCat({@id}, 'cat{@id}');">				
				<xsl:element name="div">
					<xsl:attribute name="class">colexp</xsl:attribute>
					<xsl:if test="count(forum) &gt; 0">
						<xsl:attribute name="style">background-position:0px -32px</xsl:attribute>
					</xsl:if>
					&#160;
				</xsl:element>
			</a>			
            <a href="?action=goto&amp;cat_id={@id}&amp;admin=1" onclick="return orca_admin.selectCat({@id}, 'cat{@id}');"><xsl:value-of select="title" disable-output-escaping="yes" /></a>

			<div style="position:absolute; right:8px; top:13px; width:180px;">			

				<div title="edit" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="orca_admin.editCat ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_edit.gif" />
				</div>

				<div title="delete" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="orca_admin.delCat ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_delete.gif" />
				</div>

				<div title="new forum" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="orca_admin.newForum ({@id})"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_new_forum.gif" />
				</div>

				<div title="move up" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="orca_admin.moveCat ({@id}, 'up')"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_up.gif" />
				</div>

				<div title="move down" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="orca_admin.moveCat ({@id}, 'down')"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_down.gif" />
				</div>

			</div>

		</li>
	</xsl:element>
		

</xsl:template>

</xsl:stylesheet>


