<?xml version="1.0" encoding="utf-8"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"><xsl:output method="html" encoding="utf-8" indent="no"/><xsl:template match="br"><br/></xsl:template><xsl:template match="et|i|st"/><xsl:template match="T1"><b><xsl:choose><xsl:when test="1"><xsl:attribute name="foo">FOO</xsl:attribute></xsl:when><xsl:otherwise><i>!</i></xsl:otherwise></xsl:choose><xsl:choose><xsl:when test="0"><xsl:attribute name="bar"/></xsl:when><xsl:otherwise><i>!</i></xsl:otherwise></xsl:choose><i>?</i></b></xsl:template></xsl:stylesheet>