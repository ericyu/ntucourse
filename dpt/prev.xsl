<?xml version="1.0"	encoding="utf-8"?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="ntu">
<ul class="makeMenu">
	<xsl:apply-templates/>
</ul>
</xsl:template>

<xsl:template match="//*[not(self::ntu) and not(self::name)]">
<li>
	<xsl:if test="count(*)>1">
	</xsl:if>
	<xsl:if test="not(self::institution)">
	<span class="id"><xsl:value-of select="@id"/></span>
		&#160;-&#160;
	</xsl:if>
	<span class="n"><xsl:value-of select="name"/></span>
	<xsl:if test="count(*)>1">
		<span class="expand">&#62;&#62;</span>
		<ul>
			<xsl:apply-templates select="*[not(self::name)]"/>
		</ul>
	</xsl:if>
</li>
</xsl:template>
</xsl:stylesheet>
