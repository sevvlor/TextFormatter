<?xml version="1.0" encoding="utf-8"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"><xsl:output method="xml" encoding="utf-8" indent="no" omit-xml-declaration="yes"/><xsl:template match="br"><br/></xsl:template><xsl:template match="et|i|st"/><xsl:template match="T17"><b><xsl:value-of select="@a"/><xsl:value-of select="@a"/><xsl:value-of select="@a"/></b></xsl:template></xsl:stylesheet>