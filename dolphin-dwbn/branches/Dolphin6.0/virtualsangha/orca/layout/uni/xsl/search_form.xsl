<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="default_access_denied.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />

<xsl:template match="search">

    <xsl:call-template name="breadcrumbs" />

	<div id="f_header">
		<h2>Search The Forum</h2>
	</div>


    <form name="new_search" method="post" action="index.php" target="search" onsubmit="return f.search (this.search_text.value, (this.search_type[0].checked ? this.search_type[0].value : this.search_type[1].value), this.search_forum.value, this.search_author.value, (this.search_display[0].checked ? this.search_display[0].value : this.search_display[1].value));">

        <div class="search_box">

					<input type="hidden" name="action" value="search" />


					<div class="search_field">
						Search for: 						
						<input class="sh" type="text" name="search_text" size="50" style="position:absolute;left:120px;" />
						<span class="err" style="display:none" id="err_topic_subject">Please enter from 3 to 50 symbols</span>
					</div>

					<br /><br />

					<div class="search_field">
						Where to Search:  
						<span class="search_input">
							<input type="radio" name="search_type" value="tlts" style="position:static;" checked="checked"/> <label>Topic Titles</label>
							&#160; &#160; &#160;
							<input type="radio" name="search_type" value="msgs" style="position:static;" /> <label>Messages</label>
						</span>
					</div>

					<br /><br />

					<div class="search_field">
						Forum:  
						<span class="search_input">
							<select class="sh" name="search_forum"> 
								<option value="0">Whole Forum</option>
								<xsl:apply-templates select="categs" />
							</select>
						</span>
					</div>

					<br /><br />

					<div class="search_field">
						Author: <input class="sh" type="text" name="search_author" size="50" style="position:absolute;left:120px;" />
					</div>

					<br /><br />

					<div class="search_field">
						Display:  
						<span class="search_input">
							<input type="radio" name="search_display" value="topics" style="position:static;" checked="checked"/> <label>Topics</label>
							&#160; &#160; &#160;
							<input type="radio" name="search_display" value="posts" style="position:static;" /> <label>Posts</label>
						</span>
					</div>

					<br /><br />

					<div class="search_field">
						&#160;

						<div class="search_button">
						<div class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
							<a href="javascript:void(0);" onclick="var ff=document.forms['new_search'].elements; return f.search (ff.search_text.value, (ff.search_type[0].checked ? ff.search_type[0].value : ff.search_type[1].value), ff.search_forum.value, ff.search_author.value, (ff.search_display[0].checked ? ff.search_display[0].value : ff.search_display[1].value));"><img src="{/root/urls/img}button_l.gif" /></a>
							<img src="{/root/urls/img}btn_icon_submit.gif" />
							<b>Submit</b>
						</div>
						</div>						
						<br /><br />

					</div>

					<br /><br />


			<iframe width="1" height="1" border="0" name="search" style="border:none;" />

		</div>

    </form>

</xsl:template>


<xsl:template match="categ">
	<xsl:element name="optgroup">
		<xsl:attribute name="label"><xsl:value-of select="title" /></xsl:attribute>
        <xsl:apply-templates select="forums/forum" />
	</xsl:element>
</xsl:template>

<xsl:template match="forum">
	<xsl:element name="option">
		<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
		<xsl:value-of select="title" />
    </xsl:element>
</xsl:template>

</xsl:stylesheet>
