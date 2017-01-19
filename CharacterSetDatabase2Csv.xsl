<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xlink="http://www.w3.org/1999/xlink">
   <xsl:output method="text" encoding="US-ASCII" standalone="yes" indent="no" media-type="text/plain"/>

   <xsl:template match="/">
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase">
      <xsl:text>#State;</xsl:text>
      <xsl:text>No;</xsl:text>
      <xsl:text>DataSource;</xsl:text>
      <xsl:text>Official Name;</xsl:text>
      <xsl:text>Identifier;</xsl:text>
      <xsl:text>Year;</xsl:text>
      <xsl:text>Description;</xsl:text>
      <xsl:text>MSDescription;</xsl:text>
      <xsl:text>IBMCPDescription;</xsl:text>
      <xsl:text>IBMCCSDescription;</xsl:text>
      <xsl:text>IANA Source;</xsl:text>
      <xsl:text>IANA URL;</xsl:text>
      <xsl:text>ICU;</xsl:text>
      <xsl:text>Wikipedia;</xsl:text>
      <xsl:text>Standard;</xsl:text>
      <xsl:text>Platform;</xsl:text>
      <xsl:text>Language;</xsl:text>
      <xsl:text>Alias;</xsl:text>
      <xsl:text>Uppercased;</xsl:text>
      <xsl:text>Simplified;</xsl:text>
      <xsl:text>IANA;</xsl:text>
      <xsl:text>ICONV;</xsl:text>
      <xsl:text>ICU;</xsl:text>
      <xsl:text>JAVA;</xsl:text>
      <xsl:text>MS;</xsl:text>
      <xsl:text>MSID;</xsl:text>
      <xsl:text>IBMCPID;</xsl:text>
      <xsl:text>IBMCPURL;</xsl:text>
      <xsl:text>IBMCCSID;</xsl:text>
      <xsl:text>IBMCCSURL;</xsl:text>
      <xsl:text>Reference;</xsl:text>
      <xsl:text>From;</xsl:text>
      <xsl:text>Domain;</xsl:text>
      <xsl:text>Type;</xsl:text>
      <xsl:text>#Min;</xsl:text>
      <xsl:text>#Max;</xsl:text>
      <xsl:text>Repl.;</xsl:text>
      <xsl:text>NFC;</xsl:text>
      <xsl:text>BIDI;</xsl:text>
      <xsl:text>MIBenum;</xsl:text>
      <xsl:text>PCL5;</xsl:text>
      <xsl:text>ISO-IR;</xsl:text>
      <xsl:text>Java Library;</xsl:text>
      <xsl:text>mb-to-wc-Function;</xsl:text>
      <xsl:text>flush-Function;</xsl:text>
      <xsl:text>wc-to-mb-Function;</xsl:text>
      <xsl:text>reset-Function;</xsl:text>
      <xsl:text>
</xsl:text>
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet/Alias/Relation">
      <xsl:variable name="alias" select=".."/>
      <xsl:variable name="charset" select="../.."/>
      <xsl:value-of select="$charset/@state"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@number"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@data-source"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@name"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@identifier"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@year"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/Description/text()"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/MSDescription/text()"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/IBMCPDescription/text()"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/IBMCCSDescription/text()"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/IANASource/text()"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@iana-url"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@icu-url"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@wikipedia-url"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@standard"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@platform"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@language"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@original"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@name"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@simplified"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@IANA"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@ICONV"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@ICU"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@JAVA"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@MS"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@MSID"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@IBMCPID"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@IBMCPURL"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@IBMCCSID"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$alias/@IBMCCSURL"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="@xlink:href"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="@data-source"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@domain"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@width"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@min-width"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@max-width"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@replacement-char"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@generates-nfc"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@contains-bidi"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@mib-enum"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@pcl5-symbol-set-id"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@iso-ir"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@java-library"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@mb-to-wc-func"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@flush-func"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@wc-to-mb-func"/>
      <xsl:text>;</xsl:text>
      <xsl:value-of select="$charset/@reset-func"/>
      <xsl:text>
</xsl:text>
   </xsl:template>

   <xsl:template match="text()"/>
</xsl:stylesheet>
