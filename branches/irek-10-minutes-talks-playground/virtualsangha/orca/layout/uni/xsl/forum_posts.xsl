<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="default_access_denied.xsl" />
<xsl:include href="breadcrumbs.xsl" />

<xsl:template match="urls" />
<xsl:template match="forum" />
<xsl:template match="topic" />
<xsl:template match="logininfo" />

<xsl:template match="posts">

    <xsl:call-template name="breadcrumbs">
        <xsl:with-param name="link1">
            <a href="?action=goto&amp;cat_id={cat/id}" onclick="return f.selectForumIndex({cat/id})"><xsl:value-of select="cat/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>
        <xsl:with-param name="link2">
            <a href="?action=goto&amp;forum_id={forum/id}&amp;start=0" onclick="return f.selectForum({forum/id}, 0);"><xsl:value-of select="forum/title" disable-output-escaping="yes" /></a>
        </xsl:with-param>        
    </xsl:call-template>


	<div id="f_header">
        <h2 id="forum_title"><xsl:value-of select="topic/title" disable-output-escaping="yes" /></h2> 
	</div>

    <div id="f_desc_no">&#160;</div>
		
		<div class="f_buttons">
			
			<xsl:choose>
			<xsl:when test="forum/id != 0 and topic/id != 0">

				<xsl:variable name="onclick" select="concat('return f.newTopic(', forum/id, ');')" />
				<div title="New Topic" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">
					<a href="javascript:void(0);" onclick="{$onclick}"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_new_topic.gif" />
					<b>New Topic</b>
				</div>

                <xsl:if test="0 = topic/locked">
				<xsl:variable name="onclick2" select="concat('return f.postReply(', forum/id, ',', topic/id, ');')" />
				<div title="Post Reply" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'">				
					<a href="javascript:void(0);" onclick="{$onclick2}"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_reply.gif" />
					<b>Post Reply</b>
				</div>
                </xsl:if>

    			<div title="Flag/Unflag Topic" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
					<a href="javascript:void(0);" onclick="return f.flag({topic/id});"><img src="{/root/urls/img}button_l.gif" /></a>
					<img src="{/root/urls/img}btn_icon_flag.gif" />
                    <b>Flag/Unflag</b>
				</div>

                <xsl:if test="1 = /root/logininfo/admin">
	    			<div title="Lock/Unlock Topic" class="btn" onmouseover="this.style.backgroundPosition='0 25px'" onmouseout="this.style.backgroundPosition='0 0'" >
                    <a href="javascript:void(0);" onclick="return orca_admin.lock('{topic/id}', '{topic/locked}');"><img src="{/root/urls/img}button_l.gif" /></a>
                    <xsl:if test="0 = topic/locked">
                        <img id="btn_lock_topic" src="{/root/urls/img}btn_icon_unlocked.gif" />
                    </xsl:if>
                    <xsl:if test="1 = topic/locked">
                        <img id="btn_lock_topic" src="{/root/urls/img}btn_icon_locked.gif" />
                    </xsl:if>                    
                        <b>&#160;Lock/Unlock</b>
                </div>
                </xsl:if>

                <div class="f_buttons_icn">

			        <div title="permalink" class="icn" onmouseover="this.style.backgroundPosition='0 24px'" onmouseout="this.style.backgroundPosition='0 0'" >
		        		<a href="index.php?action=goto&amp;topic_id={topic/id}" target="_blank"><img src="{/root/urls/img}button_l.gif" /></a>
	        			<img src="{/root/urls/img}btn_icon_plink.gif" />
                    </div>

                </div>

			</xsl:when>
			<xsl:otherwise>
				&#160;
			</xsl:otherwise>
			</xsl:choose>
		</div>



	<div id="reply_container">&#160;</div>

	<div id="f_tbl" style="background-color:#ffffff;">
		<ul class="tbl_hh">
			<li class="tbl_hp_author">Author</li>
			<li class="tbl_hp_msg">Message</li>
		</ul>
		<div style="height:1px; overflow:hidden;">&#160;</div>
		<xsl:apply-templates select="post" />
	</div>
	<iframe name="post_actions" width="1" height="1" frameborder="1" style="border:none;">&#160;</iframe>


</xsl:template>

<xsl:template match="force_show_post">

	<xsl:call-template name="post_row_box" />

</xsl:template>

<xsl:template match="post">

	<div id="post_row_{@id}">
		<xsl:call-template name="post_row_box" />
	</div>

</xsl:template>

<xsl:template name="post_row_box">
	<xsl:element name="div">
		
		<xsl:attribute name="class">tbl_p_msg</xsl:attribute>
		<xsl:attribute name="style">
			background-color:transparent;
			<xsl:if test="((points &lt; min_point) or (vote_user_point = -1)) and (0 = @force_show)">height:20px; padding:0; overflow:hidden; width:100%;</xsl:if>
		</xsl:attribute>

			<xsl:call-template name="post_row_content" />

	</xsl:element>
</xsl:template>

<xsl:template name="post_row_content">
	<xsl:choose>
		<xsl:when test="((points &lt; min_point) or (vote_user_point = -1)) and (0 = @force_show)">
			<div class="tbl_p_author" style="height:18px; padding-left:8px; padding-top:2px;">
				<img src="{/root/urls/img}stranger.gif" />
				<span style="position:relative; top:-4px; left:3px;"><b><xsl:value-of select="user/@name" /></b></span>
			</div>

			<xsl:call-template name="post_row_actions" />
		</xsl:when>				
		<xsl:otherwise>
			<div class="tbl_p_author">
<!--			### PERMALINK	<xsl:element name="img">
					<xsl:attribute name="name"><xsl:value-of select="concat('post', @id)"/></xsl:attribute>
				</xsl:element> -->
				<xsl:if test="string-length(user/avatar) &gt; 0 and string-length(user/@name) &gt; 0"> 
					<div class="avatar">
						<xsl:element name="img">
							<xsl:attribute name="onload">document.f.alignPost(this, <xsl:value-of select="points" />)</xsl:attribute>
							<xsl:attribute name="src"><xsl:value-of select="user/avatar" /></xsl:attribute>
						</xsl:element>
					</div>
				</xsl:if> 
				<xsl:choose>
					<xsl:when test="string-length(user/url) &gt; 0 and string-length(user/@name) &gt; 0">
						<b><a target="_blank" href="{user/url}" onclick="{user/onclick}"><xsl:value-of select="user/@name" /></a></b>
					</xsl:when>
					<xsl:otherwise>
                        <b><xsl:if test="'' = user/@name">anonymous</xsl:if><xsl:if test="'' != user/@name"><xsl:value-of select="user/@name" /></xsl:if></b>
					</xsl:otherwise>					
				</xsl:choose>
				<br />
				posts: <xsl:value-of select="user/@posts" /> <br />
			</div>
		
			<xsl:call-template name="post_row_actions" />
						
            <div id="{@id}" class="post_text">
				<xsl:choose>
                    <xsl:when test="/root/urls/xsl_mode = 'server'">
						<xsl:value-of select="text" disable-output-escaping="yes" />
					</xsl:when>
					<xsl:otherwise>
                        <xsl:choose>
                            <xsl:when test="system-property('xsl:vendor')='Transformiix'">
                                <div id="{@id}_foo" style="display:none;"><xsl:value-of select="text" /></div>
                                <script type="text/javascript">
                                    var id = '<xsl:value-of select="@id" />';
                                    <![CDATA[
                                    var s = document.getElementById(id + '_foo').innerHTML;
                                    s = s.replace(/&#160;/gm, ' ');
                                    s = s.replace(/\x26gt;/gm, '\x3e');
                                    s = s.replace(/\x26lt;/gm, '\x3c');
                                    document.getElementById(id).innerHTML = s;
                                    ]]>
                                </script>
                            </xsl:when>
                            <xsl:when test="system-property('xsl:vendor')='Microsoft'">
                                <xsl:value-of select="text" disable-output-escaping="yes" />
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="text" disable-output-escaping="yes" />
                            </xsl:otherwise>
                        </xsl:choose>
					</xsl:otherwise>
				</xsl:choose>
			</div>

		</xsl:otherwise>
	</xsl:choose>

</xsl:template>

<xsl:template name="post_row_actions">


		<div class="actions">

			<span id="report_{@id}" class="report">
					<xsl:choose>
					<xsl:when test="'' = vote_user_point">
						<a title="report this post" href="javascript:void(0);" onclick="f.report({@id}); return f.voteBad({@id});" style="margin-right:3px;"><img style="border:none;" src="{/root/urls/img}report.gif" /></a> 
					</xsl:when>
					<xsl:otherwise>
						<a title="report this post" href="javascript:void(0);" style="margin-right:3px;"><img style="border:none;" src="{/root/urls/img}report_gray.gif" /></a> 
					</xsl:otherwise>					
					</xsl:choose>
				</span>

				<span class="posted">
					<xsl:value-of select="when" />
				</span>

				<xsl:if test="(allow_del + allow_edit) = 0">
					<span style="color:white;">&#160;</span>
				</xsl:if>
				
				<xsl:if test="allow_del = 1">
					<xsl:element name="a">
						<xsl:attribute name="style">margin-right:5px;</xsl:attribute>
						<xsl:attribute name="href">javascript:void(0);</xsl:attribute>
						<xsl:attribute name="onclick">f.deletePost(<xsl:value-of select="@id" />, <xsl:value-of select="../forum/id" />, <xsl:value-of select="../topic/id" />, true);</xsl:attribute>Delete</xsl:element>
				</xsl:if>

				<xsl:if test="allow_edit = 1">
					<xsl:element name="a">
						<xsl:attribute name="onclick">f.editPost(<xsl:value-of select="@id" />);</xsl:attribute>
						<xsl:attribute name="href">javascript:void(0);</xsl:attribute>Edit</xsl:element>
				</xsl:if>


                <xsl:if test="(not((points &lt; min_point) or (vote_user_point = -1))) and 0 = ../topic/locked">			
					&#160;
					<xsl:element name="a">
						<xsl:attribute name="onclick">return f.postReplyWithQuote(<xsl:value-of select="../forum/id" />, <xsl:value-of select="../topic/id" />, <xsl:value-of select="@id" />);</xsl:attribute>
						<xsl:attribute name="href">javascript:void(0);</xsl:attribute>Quote</xsl:element>				
				</xsl:if>


				<div class="rate" id="rate_{@id}">
					<span class="rate_text">

						<xsl:if test="((points &lt; min_point) or (vote_user_point = -1))">			
							This post is hidden (
								<xsl:choose>
									<xsl:when test="1 = @force_show">
										<a href="javascript:void(0);" onclick="f.hideHiddenPost({@id})">hide post</a>
									</xsl:when>		
									<xsl:otherwise>							
										<a href="javascript:void(0);" onclick="f.showHiddenPost({@id})">show post</a>
									</xsl:otherwise>																
								</xsl:choose>
								)
						</xsl:if>

						Points: 
						<span id="points_{@id}"><xsl:value-of select="points" /></span>
						&#160;
						Vote
					</span>
					<xsl:choose>
						<xsl:when test="'' = vote_user_point">
							<a href="javascript:void(0);" onclick="return f.voteGood({@id});" style="margin-right:3px;"><img class="vote_good" src="{/root/urls/img}vote_good.gif" /></a>
							<a href="javascript:void(0);" onclick="return f.voteBad({@id});"><img class="vote_bad" src="{/root/urls/img}vote_bad.gif" /></a>
						</xsl:when>
						<xsl:otherwise>					
							<a href="javascript:void(0);" style="margin-right:3px;"><img class="vote_good" src="{/root/urls/img}vote_good_gray.gif" /></a>
							<a href="javascript:void(0);"><img class="vote_bad" src="{/root/urls/img}vote_bad_gray.gif" /></a>
						</xsl:otherwise>
					</xsl:choose>					
				</div>	

			</div>

</xsl:template>

</xsl:stylesheet>


