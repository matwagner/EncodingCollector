<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xlink="http://www.w3.org/1999/xlink">
	<xsl:output method="xml" version="1.0" encoding="UTF-8" standalone="yes" indent="yes" media-type="text/xml"/>

   <xsl:variable name="WikipediaBaseURL">
      <xsl:text>https://en.wikipedia.org/wiki/</xsl:text>
   </xsl:variable>

	<xsl:template match="/CharacterSetDatabase">
	   <xsl:element name="CharacterSetDatabase">
	      <xsl:apply-templates/>
	   </xsl:element>
	</xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet">
	   <xsl:element name="StagingArea-CharacterSet">
	      <xsl:element name="State"><xsl:value-of select="@state"/></xsl:element>
	      <xsl:element name="CharacterSetId"><xsl:value-of select="@number"/></xsl:element>
	      <xsl:element name="DataSource"><xsl:value-of select="@data-source"/></xsl:element>
	      <xsl:element name="Name"><xsl:value-of select="@name"/></xsl:element>
	      <xsl:element name="Identifier"><xsl:value-of select="@identifier"/></xsl:element>
	      <xsl:element name="Year"><xsl:value-of select="@year"/></xsl:element>
	      <xsl:element name="Description"><xsl:value-of select="Description/text()"/></xsl:element>
	      <xsl:element name="MSDescription"><xsl:value-of select="MSDescription/text()"/></xsl:element>
	      <xsl:element name="IBMCPDescription"><xsl:value-of select="IBMCPDescription/text()"/></xsl:element>
	      <xsl:element name="IBMCCSDescription"><xsl:value-of select="IBMCCSDescription/text()"/></xsl:element>
	      <xsl:element name="IANASource"><xsl:value-of select="IANASource/text()"/></xsl:element>
	      <xsl:element name="IANAURL"><xsl:value-of select="@iana-url"/></xsl:element>
	      <xsl:element name="ICUName"><xsl:value-of select="@icu-name"/></xsl:element>
	      <xsl:element name="ICUURL"><xsl:value-of select="@icu-url"/></xsl:element>
	      <xsl:element name="WikipediaURL"><xsl:value-of select="@wikipedia-url"/></xsl:element>
	      <xsl:element name="Standard"><xsl:value-of select="@standard"/></xsl:element>
	      <xsl:element name="Platform"><xsl:value-of select="@platform"/></xsl:element>
	      <xsl:element name="Language"><xsl:value-of select="@language"/></xsl:element>
	      <xsl:element name="Domain"><xsl:value-of select="@domain"/></xsl:element>
	      <xsl:element name="Width"><xsl:value-of select="@width"/></xsl:element>
	      <xsl:element name="MinWidth"><xsl:value-of select="@min-width"/></xsl:element>
	      <xsl:element name="MaxWidth"><xsl:value-of select="@max-width"/></xsl:element>
	      <xsl:element name="ReplacementChar"><xsl:value-of select="@replacement-char"/></xsl:element>
	      <xsl:element name="GeneratesNFC"><xsl:value-of select="@generates-nfc"/></xsl:element>
	      <xsl:element name="ContainsBidi"><xsl:value-of select="@contains-bidi"/></xsl:element>
	      <xsl:element name="MIBenum"><xsl:value-of select="@mib-enum"/></xsl:element>
	      <xsl:element name="PCL5SymbolSetId"><xsl:value-of select="@pcl5-symbol-set-id"/></xsl:element>
	      <xsl:element name="ISOIR"><xsl:value-of select="@iso-ir"/></xsl:element>
	      <xsl:element name="JavaLibrary"><xsl:value-of select="@java-library"/></xsl:element>
<!--	      <xsl:element name="CharMapFile"><xsl:value-of select="@char-map-file"/></xsl:element> -->
	      <xsl:element name="MbToWcFunc"><xsl:value-of select="@mb-to-wc-func"/></xsl:element>
	      <xsl:element name="FlushFunc"><xsl:value-of select="@flush-func"/></xsl:element>
	      <xsl:element name="WcToMbFunc"><xsl:value-of select="@wc-to-mb-func"/></xsl:element>
	      <xsl:element name="ResetFunc"><xsl:value-of select="@reset-func"/></xsl:element>
	   </xsl:element>
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet/Alias">
	   <xsl:element name="StagingArea-Alias">
	      <xsl:element name="CharacterSetId"><xsl:value-of select="../@number"/></xsl:element>
	      <xsl:element name="AliasId"><xsl:number level="single"/></xsl:element>
	      <xsl:element name="Original"><xsl:value-of select="@original"/></xsl:element>
	      <xsl:element name="Name"><xsl:value-of select="@name"/></xsl:element>
	      <xsl:element name="Simplified"><xsl:value-of select="@simplified"/></xsl:element>
	      <xsl:element name="IANA"><xsl:value-of select="@IANA"/></xsl:element>
	      <xsl:element name="GNUICONV"><xsl:value-of select="@ICONV"/></xsl:element>
	      <xsl:element name="GNULIBC"><xsl:value-of select="@LIBC"/></xsl:element>
<!--	      <xsl:element name="UTR22"><xsl:value-of select="@UTR22"/></xsl:element>
	      <xsl:element name="WINDOWS"><xsl:value-of select="@WINDOWS"/></xsl:element>
	      <xsl:element name="MIME"><xsl:value-of select="@MIME"/></xsl:element> -->
	      <xsl:element name="ICU"><xsl:value-of select="@ICU"/></xsl:element>
	      <xsl:element name="JAVA"><xsl:value-of select="@JAVA"/></xsl:element>
	      <xsl:element name="MS"><xsl:value-of select="@MS"/></xsl:element>
	      <xsl:element name="MSID"><xsl:value-of select="@MSID"/></xsl:element>
	      <xsl:element name="IBMCPID"><xsl:value-of select="@IBMCPID"/></xsl:element>
	      <xsl:element name="IBMCPURL"><xsl:value-of select="@IBMCPURL"/></xsl:element>
	      <xsl:element name="IBMCCSID"><xsl:value-of select="@IBMCCSID"/></xsl:element>
	      <xsl:element name="IBMCCSURL"><xsl:value-of select="@IBMCCSURL"/></xsl:element>
<!--	      <xsl:element name="UNTAGGED"><xsl:value-of select="@UNTAGGED"/></xsl:element> -->
	   </xsl:element>
      <xsl:apply-templates/>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet/Alias/Relation">
	   <xsl:element name="StagingArea-Relation">
	      <xsl:element name="CharacterSetId"><xsl:value-of select="../../@number"/></xsl:element>
	      <xsl:element name="Alias"><xsl:value-of select="../@original"/></xsl:element>
	      <xsl:element name="RelationId"><xsl:number level="single"/></xsl:element>
	      <xsl:element name="DataSource"><xsl:value-of select="@data-source"/></xsl:element>
	      <xsl:element name="Reference"><xsl:value-of select="@xlink:href"/></xsl:element>
      </xsl:element>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/Reference">
	   <xsl:element name="StagingArea-Reference">
	      <xsl:element name="Label"><xsl:value-of select="@xlink:label"/></xsl:element>
	      <xsl:element name="IANAURL"><xsl:value-of select="@iana-url"/></xsl:element>
	      <xsl:element name="Text"><xsl:copy-of select="text()"/></xsl:element>
	   </xsl:element>
   </xsl:template>

   <xsl:template match="text()"/>
</xsl:stylesheet>
