<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template name="breadcrumbs">

    <xsl:param name="link0" />
    <xsl:param name="link1" />
    <xsl:param name="link2" />

    <div id="f_breadcrumb">

        <a href="../../">Home</a>

	    <img src="{/root/urls/img}a.gif" /> 
        <a href="../../grp.php">Groups</a>

        <xsl:if test="string-length($link1)">
            <img src="{/root/urls/img}a.gif" />
            <xsl:copy-of select="$link1" />
        </xsl:if>

        <xsl:if test="string-length($link2)">
            <img src="{/root/urls/img}a.gif" />
            <xsl:copy-of select="$link2" />
        </xsl:if>

        <xsl:if test="string-length($link0)">
    		<span class="permalink"><xsl:copy-of select="$link0" /></span>
        </xsl:if>

    </div>    

</xsl:template>

</xsl:stylesheet>


