<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xlink="http://www.w3.org/1999/xlink">
   <xsl:output method="html" version="5.0" encoding="UTF-8" standalone="yes" indent="yes" media-type="text/html" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

   <xsl:variable name="WikipediaBaseURL">
      <xsl:text>https://en.wikipedia.org/wiki/</xsl:text>
   </xsl:variable>
   <xsl:variable name="WikipediaBaseURLRedlink">
      <xsl:text>https://en.wikipedia.org/w/index.php?title=</xsl:text>
   </xsl:variable>
   <xsl:variable name="WikipediaTrailerRedlink">
      <xsl:text>&amp;action=edit&amp;redlink=1</xsl:text>
   </xsl:variable>
   <xsl:variable name="iso-ir">
      <xsl:text>https://www.itscj.ipsj.or.jp/iso-ir/</xsl:text>
   </xsl:variable>

   <xsl:template match="/">
      <xsl:element name="html">
         <xsl:attribute name="xml:lang">
            <xsl:text>en</xsl:text>
         </xsl:attribute>
         <xsl:element name="head">
            <xsl:element name="title">
               <xsl:text>Character Sets</xsl:text>
            </xsl:element>
            <xsl:element name="style">
               <xsl:attribute name="type">
                  <xsl:text>text/css</xsl:text>
               </xsl:attribute>
               <xsl:comment>
                  <xsl:text>
            body {
              font-family:      Arial;
              font-size:        10pt;
            }
            b {
              font-weight:      bold;
            }
            caption {
              font-size:        24pt;
            }
            table {
              border-collapse:  collapse;
              border-style:     solid;
              border-color:     #000000;
              border-width:     1px;
            }
            th {
              border-collapse:  collapse;
              border-style:     solid;
              border-color:     #C0C0FF;
              border-width:     1px;
              background-color: #E0E0FF;
              font-size:        8pt;
            }
            td {
              empty-cells:      show;
              border-collapse:  collapse;
              border-style:     solid;
              border-color:     #C0C0FF;
              border-width:     1px;
              vertical-align:   top;
              font-size:        8pt;
            }
            .error {
              color:            #FF0000;
            }
            .checked {
              background-color: #E0FFE0;
            }
            .unchecked {
              background-color: #FFE0E0;
            }
            .unsave {
              background-color: #FFFFE0;
            }
            .new {
              background-color: #FFFF00;
            }
                  </xsl:text>
               </xsl:comment>
            </xsl:element>
         </xsl:element>
         <xsl:element name="body">
            <xsl:apply-templates select="/CharacterSetDatabase"/>
         </xsl:element>
      </xsl:element>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase">
      <xsl:element name="table">
         <xsl:attribute name="summary">
            <xsl:text>Character sets</xsl:text>
         </xsl:attribute>
         <xsl:element name="caption">
            <xsl:text>Character Sets</xsl:text>
         </xsl:element>
         <xsl:element name="thead">
            <xsl:element name="tr">
               <xsl:element name="th">
                  <xsl:text>No</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Official Name</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Description</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Standard</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Platform</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Language</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Aliases</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>IANA</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>ICONV</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>ICU</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>JAVA</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>MS</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>IBMCP</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>IBMCCS</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>References</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Domain</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Type</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>#Min</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>#Max</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Repl.</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>NFC</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>BIDI</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Functions</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>MIBenum</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>PCL5</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>ISO-IR</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Java Library</xsl:text>
               </xsl:element>
            </xsl:element>
         </xsl:element>
         <xsl:element name="tbody">
            <xsl:apply-templates select="CharacterSet"/>
         </xsl:element>
         <xsl:element name="tfoot">
            <xsl:element name="tr">
               <xsl:element name="td">
                  <xsl:attribute name="colspan">
                     <xsl:text>27</xsl:text>
                  </xsl:attribute>
                  <xsl:value-of select="count(CharacterSet)"/>
                  <xsl:text> characters sets reported.</xsl:text>
               </xsl:element>
            </xsl:element>
         </xsl:element>
      </xsl:element>
      <xsl:element name="p">
      </xsl:element>
      <xsl:element name="table">
         <xsl:attribute name="summary">
            <xsl:text>References</xsl:text>
         </xsl:attribute>
         <xsl:element name="caption">
            <xsl:text>References</xsl:text>
         </xsl:element>
         <xsl:element name="thead">
            <xsl:element name="tr">
               <xsl:element name="th">
                  <xsl:text>Reference</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Link</xsl:text>
               </xsl:element>
               <xsl:element name="th">
                  <xsl:text>Description</xsl:text>
               </xsl:element>
            </xsl:element>
         </xsl:element>
         <xsl:element name="tbody">
            <xsl:apply-templates select="Reference"/>
         </xsl:element>
         <xsl:element name="tfoot">
            <xsl:element name="tr">
               <xsl:element name="td">
                  <xsl:attribute name="colspan">
                     <xsl:text>3</xsl:text>
                  </xsl:attribute>
                  <xsl:value-of select="count(Reference)"/>
                  <xsl:text> references reported.</xsl:text>
               </xsl:element>
            </xsl:element>
         </xsl:element>
      </xsl:element>
      <xsl:element name="p">
      </xsl:element>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet">
      <xsl:variable name="rowspan" select="count(Alias)"/>
      <xsl:element name="tr">
         <xsl:attribute name="class">
            <xsl:value-of select="@state"/>
         </xsl:attribute>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@number"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:element name="b">
               <xsl:value-of select="@name"/>
            </xsl:element>
            <xsl:element name="br"/>
            <xsl:value-of select="@identifier"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@year"/>
            <xsl:element name="br"/>
            <xsl:copy-of select="Description/node()"/>
            <xsl:if test="IANASource">
               <xsl:element name="br"/>
               <xsl:text>IANA: </xsl:text>
               <xsl:copy-of select="IANASource"/>
            </xsl:if>
            <xsl:if test="string-length(@iana-url)!=0">
               <xsl:element name="br"/>
               <xsl:if test="string-length(IANASource/text())=0">
                  <xsl:text>IANA: </xsl:text>
               </xsl:if>
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="@iana-url"/>
                  </xsl:attribute>
                  <xsl:value-of select="@iana-url"/>
               </xsl:element>
            </xsl:if>
            <xsl:if test="IBMCPDescription">
               <xsl:element name="br"/>
               <xsl:text>IBMCP:</xsl:text>
               <xsl:for-each select="IBMCPDescription">
                  <xsl:element name="br"/>
                  <xsl:value-of select="@cpid"/>
                  <xsl:text>: </xsl:text>
                  <xsl:copy-of select="node()"/>
               </xsl:for-each>
            </xsl:if>
            <xsl:if test="IBMCCSDescription">
               <xsl:element name="br"/>
               <xsl:text>IBMCCS:</xsl:text>
               <xsl:for-each select="IBMCCSDescription">
                  <xsl:element name="br"/>
                  <xsl:value-of select="@ccsid"/>
                  <xsl:text>: </xsl:text>
                  <xsl:copy-of select="node()"/>
               </xsl:for-each>
            </xsl:if>
            <xsl:if test="MSDescription">
               <xsl:element name="br"/>
               <xsl:text>MS: </xsl:text>
               <xsl:copy-of select="MSDescription/node()"/>
            </xsl:if>
            <xsl:if test="string-length(@icu-url)!=0">
               <xsl:element name="br"/>
               <xsl:text>ICU: </xsl:text>
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="@icu-url"/>
                  </xsl:attribute>
                  <xsl:value-of select="@icu-name"/>
               </xsl:element>
            </xsl:if>
            <xsl:if test="string-length(@wikipedia-url)!=0">
               <xsl:element name="br"/>
               <xsl:text>WIKI: </xsl:text>
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="@wikipedia-url"/>
                  </xsl:attribute>
                  <xsl:choose>
                     <xsl:when test="contains(@wikipedia-url,'redlink')">
                        <xsl:value-of select="substring-before(substring-after(@wikipedia-url,$WikipediaBaseURLRedlink),$WikipediaTrailerRedlink)"/>
                     </xsl:when>
                     <xsl:otherwise>
                        <xsl:value-of select="substring-after(@wikipedia-url,$WikipediaBaseURL)"/>
                     </xsl:otherwise>
                     </xsl:choose>
               </xsl:element>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@standard"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@platform"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@language"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:value-of select="Alias[1]/@original"/>
            <xsl:text> (</xsl:text>
            <xsl:value-of select="Alias[1]/@simplified"/>
            <xsl:text>)</xsl:text>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="Alias[position()=1 and @IANA='yes']">
               <xsl:text>X</xsl:text>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="Alias[position()=1 and @ICONV='yes']">
               <xsl:text>X</xsl:text>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="Alias[position()=1 and @ICU='yes']">
               <xsl:text>X</xsl:text>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="Alias[position()=1 and @JAVA='yes']">
               <xsl:text>X</xsl:text>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:value-of select="Alias[1]/@MSID"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="string-length(Alias[1]/@IBMCPID)!=0">
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="Alias[1]/@IBMCPURL"/>
                  </xsl:attribute>
                  <xsl:value-of select="Alias[1]/@IBMCPID"/>
               </xsl:element>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="string-length(Alias[1]/@IBMCCSID)!=0">
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="Alias[1]/@IBMCCSURL"/>
                  </xsl:attribute>
                  <xsl:value-of select="Alias[1]/@IBMCCSID"/>
               </xsl:element>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td">
            <xsl:call-template name="ListRelations">
               <xsl:with-param name="relationlist" select="Alias[1]/Relation"/>
            </xsl:call-template>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@domain"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@width"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@min-width"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@max-width"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@replacement-char"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@generates-nfc"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@contains-bidi"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@mb-to-wc-func"/>
            <xsl:element name="br"/>
            <xsl:value-of select="@flush-func"/>
            <xsl:element name="br"/>
            <xsl:value-of select="@wc-to-mb-func"/>
            <xsl:element name="br"/>
            <xsl:value-of select="@reset-func"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@mib-enum"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@pcl5-symbol-set-id"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:element name="a">
               <xsl:attribute name="href">
                  <xsl:value-of select="$iso-ir"/>
                  <xsl:value-of select="@iso-ir"/>
                  <xsl:text>.pdf</xsl:text>
               </xsl:attribute>
               <xsl:value-of select="@iso-ir"/>
            </xsl:element>
         </xsl:element>
         <xsl:element name="td">
            <xsl:attribute name="rowspan">
               <xsl:value-of select="$rowspan"/>
            </xsl:attribute>
            <xsl:value-of select="@java-library"/>
         </xsl:element>
      </xsl:element>
      <xsl:apply-templates select="Alias"/>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/CharacterSet/Alias">
      <xsl:if test="position()>1">
         <xsl:element name="tr">
            <xsl:attribute name="class">
               <xsl:value-of select="../@state"/>
            </xsl:attribute>
            <xsl:element name="td">
               <xsl:value-of select="@original"/>
               <xsl:text> (</xsl:text>
               <xsl:value-of select="@simplified"/>
               <xsl:text>)</xsl:text>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="@IANA='yes'">
                  <xsl:text>X</xsl:text>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="@ICONV='yes'">
                  <xsl:text>X</xsl:text>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="@ICU='yes'">
                  <xsl:text>X</xsl:text>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="@JAVA='yes'">
                  <xsl:text>X</xsl:text>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:value-of select="@MSID"/>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="string-length(@IBMCPID)!=0">
                  <xsl:element name="a">
                     <xsl:attribute name="href">
                        <xsl:value-of select="@IBMCPURL"/>
                     </xsl:attribute>
                     <xsl:value-of select="@IBMCPID"/>
                  </xsl:element>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:if test="string-length(@IBMCCSID)!=0">
                  <xsl:element name="a">
                     <xsl:attribute name="href">
                        <xsl:value-of select="@IBMCCSURL"/>
                     </xsl:attribute>
                     <xsl:value-of select="@IBMCCSID"/>
                  </xsl:element>
               </xsl:if>
            </xsl:element>
            <xsl:element name="td">
               <xsl:call-template name="ListRelations">
                  <xsl:with-param name="relationlist" select="Relation"/>
               </xsl:call-template>
            </xsl:element>
         </xsl:element>
      </xsl:if>
   </xsl:template>

   <xsl:template name="ListRelations">
      <xsl:param name="relationlist"/>
      <xsl:for-each select="$relationlist">
         <xsl:if test="position()>1">
            <xsl:element name="br"/>
         </xsl:if>
         <xsl:element name="a">
            <xsl:attribute name="href">
               <xsl:text>#</xsl:text>
               <xsl:value-of select="@xlink:href"/>
            </xsl:attribute>
            <xsl:value-of select="@xlink:href"/>
            <xsl:text> (</xsl:text>
            <xsl:choose>
               <xsl:when test="@data-source='IANA'">
                  <xsl:text>IANA</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='GNU_ICONV'">
                  <xsl:text>ICONV</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='IBM_ICU'">
                  <xsl:text>ICU</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='ORACLE_JAVA'">
                  <xsl:text>JAVA</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='MS'">
                  <xsl:text>MS</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='IBMCP'">
                  <xsl:text>IBMCP</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='IBMCCS'">
                  <xsl:text>IBMCCS</xsl:text>
               </xsl:when>
               <xsl:when test="@data-source='SELF'">
                  <xsl:text>SELF</xsl:text>
               </xsl:when>
            </xsl:choose>
            <xsl:text>)</xsl:text>
         </xsl:element>
      </xsl:for-each>
   </xsl:template>

   <xsl:template match="/CharacterSetDatabase/Reference">
      <xsl:element name="tr">
         <xsl:element name="td">
            <xsl:element name="a">
               <xsl:attribute name="name">
                  <xsl:value-of select="@xlink:label"/>
               </xsl:attribute>
            </xsl:element>
            <xsl:value-of select="@xlink:label"/>
         </xsl:element>
         <xsl:element name="td">
            <xsl:if test="string-length(@URL)!=0">
               <xsl:element name="a">
                  <xsl:attribute name="href">
                     <xsl:value-of select="@URL"/>
                  </xsl:attribute>
                  <xsl:value-of select="@URL"/>
               </xsl:element>
            </xsl:if>
         </xsl:element>
         <xsl:element name="td"><xsl:value-of select="./text()"/></xsl:element>
      </xsl:element>
   </xsl:template>
   <xsl:template match="text()"/>
</xsl:stylesheet>
