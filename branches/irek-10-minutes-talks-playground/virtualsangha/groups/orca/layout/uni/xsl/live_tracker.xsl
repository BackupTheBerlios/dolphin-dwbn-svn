<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="live_tracker">

<div id="live_title">Forums Spy <span>(a real-time view of activity)</span></div>
<div id="live_tracker" class="live_tracker">
    <xsl:apply-templates select="post" />    
	<div id="live_fade" class="live_fade" style="background-image:url({/root/urls/img}gr.png);" >
		&#160;
	</div>
</div>

<script>
	correctPNG('live_fade')
	var ret = f.livePost(<xsl:value-of select="./post/@ts" />);
</script>

</xsl:template>


<xsl:template match="post">
    <div id="live_post_{@id}" class="live_post">		
        <img class="lp_img" src="{avatar}" />
		<div class="lp_txt"><xsl:value-of select="text" disable-output-escaping="yes" /></div>
        <div class="lp_u">
            <xsl:if test="user != ''"><a href="{profile}" onclick="{onclick}"><xsl:value-of select="user" /></a></xsl:if>
                    <xsl:if test="user = ''">anonymous</xsl:if>
            said in 
            <span class="lp_bc">
                <a href="{base}?action=goto&amp;cat_id={cat/@id}" onclick="return f.selectForumIndex({cat/@id})"><xsl:value-of select="cat" /></a>
                &gt;
                <a href="{base}?action=goto&amp;forum_id={forum/@id}&amp;start=0" onclick="return f.selectForum({forum/@id})"><xsl:value-of select="forum" /></a> 
                &gt;
    			<a href="{base}?action=goto&amp;topic_id={topic/@id}" onclick="return f.selectTopic({topic/@id})"><xsl:value-of select="topic" /></a>    	
            </span>
        </div>
        <div class="lp_date"><xsl:copy-of select="date" /></div>
	</div>
</xsl:template>

</xsl:stylesheet>

