<?xml version="1.0"	encoding="utf-8"?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/">
<script language="JavaScript" type="text/javascript" src="dpt/JSCookMenu.js" />
<script language="JavaScript" type="text/javascript" src="dpt/ThemeOffice/theme.js" />
<script language="JavaScript" type="text/javascript" src="dpt/select.js" />
<script language="JavaScript" type="text/javascript">
var myMenu =
[
	<xsl:apply-templates/>
];
</script>
<div id="myMenuID"></div>
<script language="JavaScript" type="text/javascript">
	cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
	Init();
</script>
</xsl:template>

<xsl:template match="ntu">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="//*[not(self::ntu) and not(self::name)]">['<xsl:value-of select="@id"/>','<xsl:value-of select="name"/>'<xsl:if test="count(*)>1">,<xsl:apply-templates select="*[not(self::name)]"/></xsl:if>],</xsl:template>
</xsl:stylesheet>
