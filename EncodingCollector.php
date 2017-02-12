<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
   <head>
      <title>Protocol of EncodingCollector.php</title>
      <style type="text/css">
      <!--
      .error {
        color:            #FF0000;
        font-weight:      bold;
      }
      .warning {
        color:            #FFC040;
        font-weight:      bold;
      }
      body {
        font-family:      Arial;
      }
      -->
      </style>
   </head>
   <body>
<?php
//============================================================================
//
//                  E n c o d i n g C o l l e c t o r . p h p
//
/// @file           EncodingCollector.php
///
/// @brief          Collects all known sources for encodings
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

set_time_limit( 10000 );          // 10 seconds should be enough :-)
error_reporting( E_ALL );         // Show all errors, warnings and informations
ini_set( "memory_limit", -1 );    // Use unlimited memory

DEFINE( "DEBUG_METHOD", false );  ///< Set to true for debug information on method invocation
DEFINE( "DEBUG_IANA",   false );  ///< Set to true for debug information on IANA processing
DEFINE( "DEBUG_ICONV",  false );  ///< Set to true for debug information on ICONV processing
DEFINE( "DEBUG_ICU",    false );  ///< Set to true for debug information on ICU processing
DEFINE( "DEBUG_JAVA",   false );  ///< Set to true for debug information on JAVA processing
DEFINE( "DEBUG_MS",     false );  ///< Set to true for debug information on Microsoft processing
DEFINE( "DEBUG_IBMCP",  false );  ///< Set to true for debug information on IBM code page processing
DEFINE( "DEBUG_IBMCCS", false );  ///< Set to true for debug information on IBM coded character set processing


//============================================================================
//
//                  D a t a   s o u r c e s
//
/// @brief          Data sources used in here
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

DEFINE( "DATASOURCE_SELF",    "SELF"        ); ///< from this project itself
DEFINE( "DATASOURCE_IANA",    "IANA"        ); ///< from IANA character-sets.txt
DEFINE( "DATASOURCE_ICONV",   "GNU_ICONV"   ); ///< from GNU libiconv encoding*.def
DEFINE( "DATASOURCE_ICU",     "IBM_ICU"     ); ///< from ICU converter list
DEFINE( "DATASOURCE_JAVA",    "ORACLE_JAVA" ); ///< from Java package java.nio.charset
DEFINE( "DATASOURCE_MS",      "MS"          ); ///< from Microsoft code page list
DEFINE( "DATASOURCE_IBMCP",   "IBMCP"       ); ///< from IBM code page list
DEFINE( "DATASOURCE_IBMCCS",  "IBMCCS"      ); ///< from IBM coded character set list


//============================================================================
//
//                  K n o w n   B i e s
//
/// @brief          Known bies used in here
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-18 Wagner First release
///
//============================================================================

DEFINE( "KNOWN_BY_SELF",     "SELF"              ); ///< this project itself
DEFINE( "KNOWN_BY_IANA",     "IANA"              ); ///< IANA character-sets.txt
DEFINE( "KNOWN_BY_ICONV",    "GNU_ICONV"         ); ///< GNU libiconv encoding*.def
DEFINE( "KNOWN_BY_LIBC",     "GNU_LIBC"          ); ///< GNU glibc charmap files
DEFINE( "KNOWN_BY_ICU",      "IBM_ICU"           ); ///< ICU project website
DEFINE( "KNOWN_BY_UTR22",    "UNICODE_UTR22"     ); ///< Unicode UTR22 (from ICU)
DEFINE( "KNOWN_BY_JAVA",     "ORACLE_JAVA"       ); ///< Java package java.nio.charset
DEFINE( "KNOWN_BY_MIME",     "MIME"              ); ///< MIME (from ICU)
DEFINE( "KNOWN_BY_IBM",      "IBM"               ); ///< IBM (from ICU)
DEFINE( "KNOWN_BY_WINDOWS",  "MS_WINDOWS"        ); ///< MS Windows (from ICU)
DEFINE( "KNOWN_BY_UNTAGGED", "ICU_UNTAGGED"      ); ///< Untagged (from ICU)
DEFINE( "KNOWN_BY_MS",       "MS"                ); ///< Microsoft code page list
DEFINE( "KNOWN_BY_IBMCP",    "IBMCP"             ); ///< IBM code page list
DEFINE( "KNOWN_BY_IBMCCS",   "IBMCCS"            ); ///< IBM coded character set list


//============================================================================
//
//                  M e t a
//
/// @brief          Meta data for an encoding resp. character set
///
/// @details        The meta information comes from the file 'Meta.csv' which
///                 must be manually edited.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class Meta
{
   // General information
   var $Used;              ///< bool: Indicates that this meta information has been used or not
   var $Group;             ///< Group: Group to which the character set belongs to
   var $Number;            ///< int: Unique number of the encoding over all data sources
   var $State;             ///< string: State 'checked' or 'unchecked' of the encoding
   var $DataSource;        ///< string: Name of the data source of first appearance
   var $Name;              ///< string: Non unique name of the first occurence of the encoding in a data source
   var $Identifier;        ///< string: Unique identifier name of the encoding as a programming language symbol
   var $Year;              ///< int: Year of publication/ standardization of the encoding
   var $Description;       ///< string: Description of the encoding
   var $Standard;          ///< string: Related standard of the encoding
   var $Platform;          ///< string: Specific platform resp. operating system of the encoding
   var $Language;          ///< string: Specific language or language group of the encoding

   // Character information
   var $Domain;            ///< string: only 'ascii', 'ebcdic' and 'braille' allowed
   var $Width;             ///< string: only 'fixed8', 'fixed16', 'fixed32', 'varying8' and 'varying16' allowed
   var $MinWidth;          ///< int: Minimum bytes
   var $MaxWidth;          ///< int: Maximum bytes
   var $ReplacementChar;   ///< int: Replacement character to be used for unknown codepoints
   var $GeneratesNFC;      ///< tristate: Indicates that the encoding always generates Unicode normalisation form NFC
   var $ContainsBIDI;      ///< tristate: Indicates that the encoding contains bidirectional characters

   // Special information
   var $WikipediaURL;      ///< string: Link to the english Wikipedia page for the encoding
   var $JavaLibrary;       ///< string: Java library (rt.jar or charsets.jar)

   /// @brief  Constructor with essential encoding information
   ///
   /// @param  $group      Group to which the character set belongs to
   /// @param  $number     Unique number of the encoding
   /// @param  $state      State of the encoding
   /// @param  $datasource Name of the data source of first appearance
   /// @param  $name       Unique name of the encoding
   /// @param  $identifier Unique identifier name of the encoding
   /// @param  $year       Year of publication/ standardization of the encoding
   /// @param  $descr      Description of the encoding
   /// @param  $standard   Related standard of the encoding
   /// @param  $platform   Specific platform resp. operating system of the encoding
   /// @param  $language   Specific language or language group of the encoding
   /// @param  $wikipediaurl Link to the english Wikipedia page for the encoding
   /// @param  $domain     Only 'ascii', 'ebcdic' and 'braille' allowed
   /// @param  $width      Only 'sbcs', 'dbcs', 'qbcs', 'varying8' and 'varying16' allowed
   /// @param  $minwidth   Minimum bytes
   /// @param  $maxwidth   Maximum bytes
   /// @param  $replchar   Replacement character to be used for unknown codepoints
   /// @param  $nfc        Indicates that the encoding always generates Unicode normalisation form NFC
   /// @param  $bidi       Indicates that the encoding contains bidirectional characters
   /// @param  $javalib    Java library (rt.jar or charsets.jar)
   function __construct( $group, $number, $state, $datasource, $name, $identifier
                       , $year, $descr, $standard, $platform, $language
                       , $wikipediaurl, $domain, $width, $minwidth, $maxwidth
                       , $replchar, $nfc, $bidi, $javalib )
   {
      $this->Used            = false;
      $this->Group           = $group;
      $this->Number          = intval( $number );
      $this->State           = $state;
      $this->DataSource      = $datasource;
      $this->Name            = $name;
      $this->Identifier      = $identifier;
      $this->Year            = $year;
      $this->Description     = $descr;
      $this->Standard        = $standard;
      $this->Platform        = $platform;
      $this->Language        = $language;
      $this->Domain          = $domain;
      $this->Width           = $width;
      $this->MinWidth        = $minwidth;
      $this->MaxWidth        = $maxwidth;
      $this->ReplacementChar = $replchar;
      $this->GeneratesNFC    = $nfc;
      $this->ContainsBIDI    = $bidi;
      $this->WikipediaURL    = htmlspecialchars( $wikipediaurl );
      $this->JavaLibrary     = $javalib;
   }

   /// @brief  Compares this meta information to the other meta information
   ///         only by key.
   ///
   /// @param  $other      Other instance of meta information to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      return strcmp( $this->Identifier, $other->Identifier );
   }

   /// @brief  Sets this meta information into the used state.
   function SetUsed( )
   {
      $this->Used = true;
   }
}; // endclass Meta


//============================================================================
//
//                  E x c l u s i o n
//
/// @brief          Alias exclusions for an encoding resp. character set in a
///                 specific data source
///
/// @details        The alias exclusions come from the file 'Exclusion.csv'
///                 which must be manually edited.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class Exclusion
{
   var $Identifier;        ///< string: Unique identifier name of the encoding as a programming language symbol
   var $DataSource;        ///< string: Unique name of the data source
   var $Alias;             ///< string: Alias name to exclude
   var $AssignTo;          ///< string: Character set identifier to assign this exclusion to
   var $Reason;            ///< string: Reason for the exclusion

   /// @brief  Constructor with all exclusion information
   ///
   /// @param  $identifier Unique name of the encoding
   /// @param  $datasource Unique name of the data source
   /// @param  $alias      Alias name to exclude
   /// @param  $assignto   Character set identifier to assign this exclusion to
   /// @param  $reason     Reason for the exclusion
   function __construct( $identifier, $datasource, $alias, $assignto, $reason )
   {
      $this->Identifier = $identifier;
      $this->DataSource = $datasource;
      $this->Alias      = $alias;
      $this->AssignTo   = $assignto;
      $this->Reason     = $reason;
   }

   /// @brief  Compares this exclusion to the other exclusion only by key.
   ///
   /// @param  $other      Other instance of exclusion to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      if ( ($diff = strcmp( $this->DataSource, $other->DataSource )) != 0 )
         return $diff;

      if ( ($diff = strcmp( $this->Identifier, $other->Identifier )) != 0 )
         return $diff;

      return strcmp( $this->Alias, $other->Alias );
   }
}; // endclass Exclusion


//============================================================================
//
//                  R e f e r e n c e
//
/// @brief          Reference for an encoding resp. character set in a
///                 bibliographic sense
///
/// @details        The references come from the file 'Reference.csv'
///                 which must be manually edited.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class Reference
{
   var $Name;              ///< string: Non unique name of the reference
   var $Identifier;        ///< string: Unique identifier name of the reference as a programming language symbol
   var $Text;              ///< string: Description or some text of the reference
   var $URL;               ///< string: Link to the www (optional)
   var $Comment;           ///< string: A comment for this reference (optional)

   /// @brief  Constructor with some reference information
   ///
   /// @param  $name       Non unique name of the reference
   /// @param  $identifier Unique identifier name of the reference as a programming language symbol
   /// @param  $text       Description or some text of the reference
   /// @param  $url        Link to the www (optional)
   /// @param  $comment    A comment for this reference (optional)
   function __construct( $name, $identifier, $text, $url, $comment )
   {
      $this->Name        = $name;
      $this->Identifier  = $identifier;
      $this->Text        = $text;
      $this->URL         = $url;
      $this->Comment     = $comment;
   }

   /// @brief  Compares this reference to the other reference only by key.
   ///
   /// @param  $other      Other instance of reference to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      return strcmp( $this->Identifier, $other->Identifier );
   }

   /// @brief  Adds a hyperlink to the reference.
   ///
   /// @param  $url        The hyperlink to set
   function SetURL( $url )
   {
      if ( DEBUG_METHOD ) print "<p>Reference.SetURL(".$url.")</p>".PHP_EOL;

      $this->URL = $url;
   } // end SetURL

   /// @brief  Appends some text to the rerefence description.
   ///
   /// @param  $text       The text to append
   function AppendText( $text )
   {
      if ( DEBUG_METHOD ) print "<p>Reference.AppendText(".$text.")</p>".PHP_EOL;

      if ( isset( $this->Text ) )
      {
         if ( $text[0] == ',' && $this->Text[ strlen( $this->Text ) - 1 ] == ',' )
            $text = trim( substr( $text, 1 ) );
         if ( $text[0] != ',' )
            $this->Text .= ' ';
         $this->Text .= $text;
      }
      else
         $this->Text  = $text;
   } // end AppendText

   /// @brief  Writes all information of the reference to XML.
   ///
   /// @param  $file       XML file to write to
   function WriteXML( $file )
   {
      fputs( $file, "   <Reference xlink:label=\"".$this->Identifier );
      fputs( $file, "\" URL=\"".$this->URL."\">" );
      fputs( $file, $this->Text );
      fputs( $file, "</Reference>".PHP_EOL );
   } // end WriteXML
}; // endclass Reference


//============================================================================
//
//                  G r o u p
//
/// @brief          Group name of encodings
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2017-02-10 Wagner First release
///
//============================================================================

class Group
{
   // General information
   var $Identifier;        ///< string: Unique identifier name of the group as a programming language symbol
   var $Name;              ///< string: Name of the Group
   var $Description;       ///< string: Description of the Group
   var $CharacterSet;      ///< CharacterSet[]: Array of character sets

   /// @brief  Single constructor
   ///
   /// @param $identifier  Unique identifier name of the group as a programming language symbol
   /// @param $name        Name of the Group
   /// @param $description Description of the Group
   function __construct( $identifier, $name, $description )
   {
      if ( DEBUG_METHOD ) print "<p>Construct Group(".$name.")</p>".PHP_EOL;

      $this->Identifier  = $identifier;
      $this->Name        = $name;
      $this->Description = $description;

   } // end Constructor

   /// @brief  Compares this group to the other group only by key.
   ///
   /// @param  $other      Other instance of group to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      return strcmp( $this->Identifier, $other->Identifier );
   } // end CompareTo

   /// @brief  Writes all information of the group to XML.
   ///
   /// @param  $file       XML file to write to
   /// @param  $cs         Count of character sets
   /// @param  $checked    Count of checked character sets
   /// @param  $unsave     Count of unsave character sets
   /// @param  $unchecked  Count of unchecked character sets
   function WriteXML( $file, &$cs, &$checked, &$unsave, &$unchecked )
   {
      fputs( $file, "   <Group identifier=\"" .$this->Identifier     ."\"".PHP_EOL );
      fputs( $file, "          name=\""       .$this->Name           ."\"".PHP_EOL );
      fputs( $file, "          description=\"".$this->Description    ."\">".PHP_EOL );
      ksort( $this->CharacterSet );
      foreach ( $this->CharacterSet as $charset )
      {
         $cs++;
         $charset->WriteXML( $file );
         if ( $charset->State == "checked" )
            $checked++;
         else
         if ( $charset->State == "unsave" )
            $unsave++;
         else
         if ( $charset->State == "unchecked" )
            $unchecked++;
      }
      fputs( $file, "   </Group>".PHP_EOL );
   } // end WriteXML
}; // endclass Group


//============================================================================
//
//                  C h a r a c t e r S e t
//
/// @brief          Representation of an encoding resp. character set with
///                 all data
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class CharacterSet
{
   // General information
   var $Group;             ///< Group: Group to which the character set belongs to
   var $State;             ///< string: State 'checked' or 'unchecked' of the encoding
   var $Number;            ///< int: Unique number of the encoding over all data sources
   var $DataSource;        ///< string: Name of the data source of first appearance
   var $Name;              ///< Alias: Preferred alias name of the encoding
   var $Identifier;        ///< string: Unique identifier name of the encoding as a programming language symbol
   var $Year;              ///< int: Year of publication/ standardization of the encoding
   var $Description;       ///< string: Description of the encoding
   var $MSDescription;     ///< string: Microsoft description of the encoding
   var $IBMCPDescription;  ///< string: IBM code page description of the encoding
   var $IBMCCSDescription; ///< string: IBM coded character set description of the encoding
   var $Standard;          ///< string: Related standard of the encoding
   var $Platform;          ///< string: Specific platform resp. operating system of the encoding
   var $Language;          ///< string: Specific language or language group of the encoding
   var $Alias;             ///< Alias[]: Array of aliases

   // Data sources
   var $DataSourceIANA;    ///< tristate: Indicates whether the character set is known by the data source IANA or not
   var $DataSourceICONV;   ///< tristate: Indicates whether the character set is known by the data source GNU ICONV or not
   var $DataSourceICU;     ///< tristate: Indicates whether the character set is known by the data source IBM ICU or not
   var $DataSourceJAVA;    ///< tristate: Indicates whether the character set is known by the data source JAVA or not
   var $DataSourceMS;      ///< tristate: Indicates whether the character set is known by the data source Microsoft or not
   var $DataSourceIBMCP;   ///< tristate: Indicates whether the character set is known by the data source IBM code page or not
   var $DataSourceIBMCCS;  ///< tristate: Indicates whether the character set is known by the data source IBM coded character set or not

   // Character information
   var $Domain;            ///< string: 'ascii' or 'ebcdic' or 'braille'
   var $Width;             ///< enum: The character width
   var $MinWidth;          ///< int: Minimum width in bytes for this encoding
   var $MaxWidth;          ///< int: Maximum width in bytes for this encoding
   var $ReplacementChar;   ///< int: The replacement char for undefined code points
   var $GeneratesNFC;      ///< tristate: Indicates whether the encoding produces Unicode NFC or not
   var $ContainsBIDI;      ///< tristate: Indicates whether the encoding contains BIDI code points or not

   // Special information
   var $MIBenum;           ///< int: The MIB enum value
   var $PCL5SymbolSetId;   ///< string: PCL5 symbol set identifier
   var $ISOIR;             ///< string: ISO international register (ISO-IR)
   var $IANASource;        ///< string: Where IANA has the information from
   var $IANAURL;           ///< string: Link to the encoding on IANA web site
   var $ICUName;           ///< string: ICU's internal name
   var $ICUURL;            ///< string: Link to the individual ICU character set page
   var $WikipediaURL;      ///< string: Link to the english Wikipedia page for the encoding
   var $JavaLibrary;       ///< string: Java library (rt.jar or charsets.jar)

   // Transformation functions
   var $MbtowcFunc;        ///< string: GNU ICONV multi byte to wchar converter function
   var $FlushFunc;         ///< string: GNU ICONV flush function (optional)
   var $WctombFunc;        ///< string: GNU ICONV wchar to multi byte converter function
   var $ResetFunc;         ///< string: GNU ICONV reset function (optional)

   /// @brief  Constructor with optional meta information
   ///
   /// @param  $datasource Name of the data source of first appearance
   /// @param  $name       Name of the encoding
   /// @param  $meta       Meta information for this encoding (optional)
   function __construct( $datasource, $name, $meta )
   {
      if ( DEBUG_METHOD ) print "<p>Construct CharacterSet(".$datasource.", ".$name.")</p>".PHP_EOL;

      //----------------------------------------------------------------------
      // Take meta infromation.

      if ( isset( $meta ) )
      {
         $this->Group           = $meta->Group;
         $this->State           = $meta->State;
         $this->Number          = $meta->Number;
         $this->DataSource      = $meta->DataSource;
         $this->Identifier      = $meta->Identifier;
         $this->Year            = $meta->Year;
         $this->Description     = $meta->Description;
         $this->Standard        = $meta->Standard;
         $this->Platform        = $meta->Platform;
         $this->Language        = $meta->Language;
         $this->Domain          = $meta->Domain;
         $this->Width           = $meta->Width;
         $this->MinWidth        = $meta->MinWidth;
         $this->MaxWidth        = $meta->MaxWidth;
         $this->ReplacementChar = $meta->ReplacementChar;
         $this->GeneratesNFC    = $meta->GeneratesNFC;
         $this->ContainsBIDI    = $meta->ContainsBIDI;
         $this->WikipediaURL    = $meta->WikipediaURL;
         $this->JavaLibrary     = $meta->JavaLibrary;

         $this->Group->CharacterSet[ $this->Number ] = $this;
      }
      else
      {
         $this->Number          = 0;
         $this->State           = "new";
         $this->DataSource      = $datasource;
         $this->Identifier      = $name;
         $this->GeneratesNFC    = "no";
         $this->ContainsBIDI    = "no";
      }

      //----------------------------------------------------------------------
      // Initialize data sources.

      $this->DataSourceIANA     = "no";
      $this->DataSourceICONV    = "no";
      $this->DataSourceICU      = "no";
      $this->DataSourceJAVA     = "no";
      $this->DataSourceMS       = "no";
      $this->DataSourceIBMCP    = "no";
      $this->DataSourceIBMCCS   = "no";

      switch ( $datasource )
      {
         case DATASOURCE_IANA   : $this->DataSourceIANA   = "yes"; break;
         case DATASOURCE_ICONV  : $this->DataSourceICONV  = "yes"; break;
         case DATASOURCE_ICU    : $this->DataSourceICU    = "yes"; break;
         case DATASOURCE_JAVA   : $this->DataSourceJAVA   = "yes"; break;
         case DATASOURCE_MS     : $this->DataSourceMS     = "yes"; break;
         case DATASOURCE_IBMCP  : $this->DataSourceIBMCP  = "yes"; break;
         case DATASOURCE_IBMCCS : $this->DataSourceIBMCCS = "yes"; break;
      }
   } // end Constructor

   /// @brief  Takes the meta information over.
   ///
   /// @param  $meta       Meta information for this encoding (optional)
   function SetMeta( $meta )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetMeta(".$meta->Identifier.")</p>".PHP_EOL;

      //----------------------------------------------------------------------
      // Remove this character set from old group.

      unset( $this->Group->CharacterSet[ $this->Number ] );

      //----------------------------------------------------------------------
      // Take meta infromation.

      $this->Group           = $meta->Group;
      $this->State           = $meta->State;
      $this->Number          = $meta->Number;
      $this->DataSource      = $meta->DataSource;
      $this->Identifier      = $meta->Identifier;
      $this->Year            = $meta->Year;
      $this->Description     = $meta->Description;
      $this->Standard        = $meta->Standard;
      $this->Platform        = $meta->Platform;
      $this->Language        = $meta->Language;
      $this->Domain          = $meta->Domain;
      $this->Width           = $meta->Width;
      $this->MinWidth        = $meta->MinWidth;
      $this->MaxWidth        = $meta->MaxWidth;
      $this->ReplacementChar = $meta->ReplacementChar;
      $this->GeneratesNFC    = $meta->GeneratesNFC;
      $this->ContainsBIDI    = $meta->ContainsBIDI;
      $this->WikipediaURL    = $meta->WikipediaURL;
      $this->JavaLibrary     = $meta->JavaLibrary;

      //----------------------------------------------------------------------
      // Add this character set to new group.

      $this->Group->CharacterSet[ $this->Number ] = $this;

   } // end SetMeta

   /// @brief  Compares this character set to the other character set
   ///         only by key.
   ///
   /// @param  $other      Other instance of character set to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      return strcmp( $this->Identifier, $other->Identifier );
   } // end CompareTo

   /// @brief  Sets the text to the Microsoft description.
   ///
   /// @param  $text       The text to set
   function SetMSDescription( $text )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetMSDescription(".$text.")</p>".PHP_EOL;

      $this->MSDescription = $text;
   } // end SetMSDescription

   /// @brief  Sets the text to the IBM code page description.
   ///
   /// @param  $id         The numeric for the text
   /// @param  $text       The text to set
   function SetIBMCPDescription( $id, $text )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetIBMCPDescription(".$id.", ".$text.")</p>".PHP_EOL;

      if ( !isset( $this->IBMCPDescription ) || !isset( $this->IBMCPDescription[ $id ] ) )
         $this->IBMCPDescription[ $id ] = $text;
   } // end SetIBMCPDescription

   /// @brief  Sets the text to the IBM coded character set description.
   ///
   /// @param  $id         The numeric for the text
   /// @param  $text       The text to set
   function SetIBMCCSDescription( $id, $text )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetIBMCCSDescription(".$id.", ".$text.")</p>".PHP_EOL;

      if ( !isset( $this->IBMCCSDescription ) || !isset( $this->IBMCCSDescription[ $id ] ) )
         $this->IBMCCSDescription[ $id ] = $text;
   } // end SetIBMCCSDescription

   /// @brief  Set a data source to indicate that this character comes from
   ///         that data source.
   ///
   /// @param  $datasource Name of the data source
   function SetDataSource( $datasource )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetDataSource(".$datasource.")</p>".PHP_EOL;

      switch ( $datasource )
      {
         case DATASOURCE_IANA     : $this->DataSourceIANA   = "yes"; break;
         case DATASOURCE_ICONV    : $this->DataSourceICONV  = "yes"; break;
         case DATASOURCE_ICU      : $this->DataSourceICU    = "yes"; break;
         case DATASOURCE_JAVA     : $this->DataSourceJAVA   = "yes"; break;
         case DATASOURCE_MS       : $this->DataSourceMS     = "yes"; break;
         case DATASOURCE_IBMCP    : $this->DataSourceIBMCP  = "yes"; break;
         case DATASOURCE_IBMCCS   : $this->DataSourceIBMCCS = "yes"; break;
      }
   } // end SetDataSource

   /// @brief  Adds a new alias to the character set in a unique way.
   ///
   /// @param  $datasource Name of the data source of first appearance
   /// @param  $aliasname  Name of the new alias
   /// @param  $preferred  Indicates that the new alias is the preferred one
   ///                     for this character set
   /// @return Alias       The either newly created or found alias
   function AddAlias( $datasource, $aliasname, $preferred )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.AddAlias(".$datasource."/".$aliasname.", ".($preferred ? "preferred" : "not preferred").")</p>".PHP_EOL;

      //----------------------------------------------------------------------
      //  Find or insert the new alias.

      $alias = @$this->Alias[ $aliasname ];
      if ( !isset( $alias ) )
      {
         $alias = new Alias( $this, $datasource, $aliasname );
         $this->Alias[ $alias->Original ] = $alias;
      }

      //----------------------------------------------------------------------
      //  Set the character set name if not done already.

      if ( !isset( $this->Name ) || $preferred )
         $this->Name = $alias;

      return $alias;
   } // end AddAlias

   /// @brief  Adds new relations to all aliases of the character set
   ///         known so far.
   ///
   /// @param  $datasource The data source of the new relation
   /// @param  $reflist    List of references
   function SetRelationAll( $datasource, $reflist )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetRelationAll(".$datasource.")</p>".PHP_EOL;

      foreach ( $this->Alias as $alias )
         foreach ( $reflist as $reference )
            $alias->SetRelation( $datasource, $reference );
   } // end SetRelationAll

   /// @brief  Adds the MIB enumeration value to the character set.
   ///
   /// @param  $mibenum    The interger MIB enum value to set
   function SetMIBEnum( $mibenum )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetMIBEnum(".$mibenum.")</p>".PHP_EOL;

      $this->MIBenum = $mibenum;
   } // end SetMIBEnum

   /// @brief  Adds the HP PCL5 symbol set id value to the character set.
   ///
   /// @param  $pcl5symbolsetid The PCL5 symbol set id to set
   function SetPCL5SymbolSetId( $pcl5symbolsetid )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetPCL5SymbolSetId(".$pcl5symbolsetid.")</p>".PHP_EOL;

      $this->PCL5SymbolSetId = $pcl5symbolsetid;
   } // end SetPCL5SymbolSetId

   /// @brief  Adds the ISO international register value to the character set.
   ///
   /// @param  $isoir      The ISO international register value to set
   function SetISOIR( $isoir )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetISOIR(".$isoir.")</p>".PHP_EOL;

      $part = explode( '-', $isoir );
      $threedigits = sprintf( "%03u", intval( $part[0] ) );
      $this->ISOIR = $threedigits;
      if ( isset( $part[1] ) )
          $this->ISOIR .= '-'.$part[1];
   } // end SetISOIR

   /// @brief  Adds more text to the IANA source of the character set.
   ///
   /// @param  $text       The text portion to append
   function AppendIANASource( $text )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.AppendIANASource(".$text.")</p>".PHP_EOL;

      if ( isset( $this->IANASource ) )
      {
         $this->IANASource .= " ";
         $this->IANASource .= $text;
      }
      else
         $this->IANASource = $text;
   } // end AppendIANASource

   /// @brief  Adds the IANA hyperlink value to the character set.
   ///
   /// @param  $ianaurl    The IANA hyperlink to set
   function SetIANAURL( $ianaurl )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetIANAURL(".$ianaurl.")</p>".PHP_EOL;

      $this->IANAURL = $ianaurl;
   } // end SetIANAURL

   /// @brief  Adds the ICU hyperlink value to the character set.
   ///
   /// @param  $icuname    The ICU internal name
   /// @param  $icuurl     The ICU hyperlink to set
   function SetICU( $icuname, $icuurl )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetICU(".$icuname.", ".$icuurl.")</p>".PHP_EOL;

      $this->ICUName = $icuname;
      $this->ICUURL  = $icuurl;
   } // end SetICU

   /// @brief  Adds the Micrsoft id to all alias versions.
   ///
   /// @param  $aliasname  The alias name to process
   /// @param  $msid       The Microsoft id to set
   function SetMSIDAll( $aliasname, $msid )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetMSIDAll(".$aliasname.", ".$msid.")</p>".PHP_EOL;

      $alias = @$this->Alias[ $aliasname ];
      if ( isset( $alias ) )
      {
         $alias->SetMSID( $msid );
         $alias = @$this->Alias[ $alias->Name ];
         if ( isset( $alias ) )
            $alias->SetMSID( $msid );
         $alias = @$this->Alias[ $alias->Simplified ];
         if ( isset( $alias ) )
            $alias->SetMSID( $msid );
      }
   } // end SetMSIDAll

   /// @brief  Return true iff this character set has GNU libiconv functions available.
   ///
   /// @return bool         True iff functions are available
   function HasICONVFunctions( )
   {
      return strlen( $this->MbtowcFunc ) != 0
          || strlen( $this->FlushFunc  ) != 0
          || strlen( $this->WctombFunc ) != 0
          || strlen( $this->ResetFunc  ) != 0;
   }

   /// @brief  Adds the GNU libiconv functions to the character set.
   ///
   /// @param  $mbtowc_func The multibyte to wchar converter function
   /// @param  $flush_func  The flush function
   /// @param  $wctomb_func The wchar to multibyte converter function
   /// @param  $reset_func  The reset function
   function SetICONVFunctions( $mbtowc_func, $flush_func, $wctomb_func, $reset_func )
   {
      if ( DEBUG_METHOD ) print "<p>CharacterSet.SetICONVFunctions(".$mbtowc_func.", ".$wctomb_func.")</p>".PHP_EOL;

      $this->MbtowcFunc = $mbtowc_func;
      $this->FlushFunc  = $flush_func;
      $this->WctombFunc = $wctomb_func;
      $this->ResetFunc  = $reset_func;
   }

   /// @brief  Writes all information of the character set to XML.
   ///
   /// @param  $file       XML file to write to
   function WriteXML( $file )
   {
      fputs( $file, "      <CharacterSet state=\""             .$this->State           ."\"".PHP_EOL );
      fputs( $file, "                    number=\""            .$this->Number          ."\"".PHP_EOL );
      fputs( $file, "                    data-source=\""       .$this->DataSource      ."\"".PHP_EOL );
      fputs( $file, "                    name=\""              .$this->Name->Original  ."\"".PHP_EOL );
      fputs( $file, "                    identifier=\""        .$this->Identifier      ."\"".PHP_EOL );
      fputs( $file, "                    year=\""              .$this->Year            ."\"".PHP_EOL );
      fputs( $file, "                    standard=\""          .$this->Standard        ."\"".PHP_EOL );
      fputs( $file, "                    platform=\""          .$this->Platform        ."\"".PHP_EOL );
      fputs( $file, "                    language=\""          .$this->Language        ."\"".PHP_EOL );
      fputs( $file, "                    data-source-iana=\""  .$this->DataSourceIANA  ."\"".PHP_EOL );
      fputs( $file, "                    data-source-iconv=\"" .$this->DataSourceICONV ."\"".PHP_EOL );
      fputs( $file, "                    data-source-icu=\""   .$this->DataSourceICU   ."\"".PHP_EOL );
      fputs( $file, "                    data-source-java=\""  .$this->DataSourceJAVA  ."\"".PHP_EOL );
      fputs( $file, "                    data-source-ms=\""    .$this->DataSourceMS    ."\"".PHP_EOL );
      fputs( $file, "                    data-source-ibmcp=\"" .$this->DataSourceIBMCP ."\"".PHP_EOL );
      fputs( $file, "                    data-source-ibmccs=\"".$this->DataSourceIBMCCS."\"".PHP_EOL );
      fputs( $file, "                    domain=\""            .$this->Domain          ."\"".PHP_EOL );
      fputs( $file, "                    width=\""             .$this->Width           ."\"".PHP_EOL );
      fputs( $file, "                    min-width=\""         .$this->MinWidth        ."\"".PHP_EOL );
      fputs( $file, "                    max-width=\""         .$this->MaxWidth        ."\"".PHP_EOL );
      fputs( $file, "                    replacement-char=\""  .$this->ReplacementChar ."\"".PHP_EOL );
      fputs( $file, "                    generates-nfc=\""     .$this->GeneratesNFC    ."\"".PHP_EOL );
      fputs( $file, "                    contains-bidi=\""     .$this->ContainsBIDI    ."\"".PHP_EOL );
      fputs( $file, "                    mib-enum=\""          .$this->MIBenum         ."\"".PHP_EOL );
      fputs( $file, "                    pcl5-symbol-set-id=\"".$this->PCL5SymbolSetId ."\"".PHP_EOL );
      fputs( $file, "                    iso-ir=\""            .$this->ISOIR           ."\"".PHP_EOL );
      fputs( $file, "                    iana-url=\""          .htmlspecialchars( $this->IANAURL, ENT_COMPAT | ENT_XML1 )."\"".PHP_EOL );
      fputs( $file, "                    icu-name=\""          .$this->ICUName         ."\"".PHP_EOL );
      fputs( $file, "                    icu-url=\""           .htmlspecialchars( $this->ICUURL, ENT_COMPAT | ENT_XML1 )."\"".PHP_EOL );
      fputs( $file, "                    wikipedia-url=\""     .$this->WikipediaURL    ."\"".PHP_EOL );
      fputs( $file, "                    java-library=\""      .$this->JavaLibrary     ."\"".PHP_EOL );
      // fputs( $file, "                    char-map-file=\""   .(isset( $this->CharMap    ) $this->CharMap->GetFileName() : "")."\"".PHP_EOL );
      fputs( $file, "                    mb-to-wc-func=\""     .$this->MbtowcFunc      ."\"".PHP_EOL );
      fputs( $file, "                    flush-func=\""        .$this->FlushFunc       ."\"".PHP_EOL );
      fputs( $file, "                    wc-to-mb-func=\""     .$this->WctombFunc      ."\"".PHP_EOL );
      fputs( $file, "                    reset-func=\""        .$this->ResetFunc       ."\">".PHP_EOL );
      if ( strlen( $this->Description ) != 0 )
      fputs( $file, "         <Description>".$this->Description."</Description>".PHP_EOL );
      if ( strlen( $this->MSDescription ) != 0 )
      fputs( $file, "         <MSDescription>".$this->MSDescription."</MSDescription>".PHP_EOL );
      if ( isset( $this->IBMCPDescription ) )
         foreach ( $this->IBMCPDescription as $id => $text )
            fputs( $file, "         <IBMCPDescription cpid=\"".$id."\">".$text."</IBMCPDescription>".PHP_EOL );
      if ( isset( $this->IBMCCSDescription ) )
         foreach ( $this->IBMCCSDescription as $id => $text )
            fputs( $file, "         <IBMCCSDescription ccsid=\"".$id."\">".$text."</IBMCCSDescription>".PHP_EOL );
      if ( isset( $this->IANASource ) )
      fputs( $file, "         <IANASource>".$this->IANASource."</IANASource>".PHP_EOL );
      foreach ( $this->Alias as $alias )
         $alias->WriteXML( $file );
      fputs( $file, "      </CharacterSet>".PHP_EOL );
   } // end WriteXML
}; // endclass CharacterSet


//============================================================================
//
//                  A l i a s
//
/// @brief          Alias name of an encoding
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class Alias
{
   // General information
   var $CharacterSet;      ///< CharacterSet: Reference to the encoding
   var $DataSource;        ///< string: Name of the data source of first appearance
   var $Original;          ///< string: Original name of the alias, unique in the character set
   var $Name;              ///< string: Uppercased name of the alias
   var $Simplified;        ///< string: Simplified name of the alias for searching
   var $Relation;          ///< Relation[]: Relations from data sources to references

   // Known bies
   var $KnownByIANA;       ///< tristate: Indicates whether the alias is known by the data source IANA or not
   var $KnownByICONV;      ///< tristate: Indicates whether the alias is known by the data source GNU ICONV or not
   var $KnownByLIBC;       ///< tristate: Indicates whether the alias is known by GNU LIBC or not
   var $KnownByICU;        ///< tristate: Indicates whether the alias is known by the data source IBM ICU or not
   var $KnownByUTR22;      ///< tristate: Indicates whether the alias is known by UTR22 or not
   var $KnownByJAVA;       ///< tristate: Indicates whether the alias is known by the data source JAVA or not
   var $KnownByMIME;       ///< tristate: Indicates whether the alias is known by MIME or not
   var $KnownByIBM;        ///< tristate: Indicates whether the alias is known by IBM or not
   var $KnownByWINDOWS;    ///< tristate: Indicates whether the alias is known by WINDOWS or not
   var $KnownByUNTAGGED;   ///< tristate: Indicates whether the alias is UNTAGGED by ICU or not
   var $KnownByMS;         ///< tristate: Indicates whether the alias is known by Microsoft or not

   // Special information
   var $MSID;              ///< int: Microsoft's code page identifier
   var $IBMCPID;           ///< int: Number of the IBM code page
   var $IBMCPURL;          ///< string: Link to the IBM code page description
   var $IBMCCSID;          ///< int: Number of the IBM coded character set
   var $IBMCCSURL;         ///< string: Link to the IBM coded character set description

   /// @brief  Single constructor
   ///
   /// @param  $charset    Reference to the character set
   /// @param  $datasource Name of the data source of first appearance
   /// @param  $name       Name of the alias
   function __construct( $charset, $datasource, $name )
   {
      if ( DEBUG_METHOD ) print "<p>Construct Alias(".$name.")</p>".PHP_EOL;

      $this->CharacterSet = $charset;
      $this->DataSource   = $datasource;
      $this->Original     = $name;

      //-------------------------------------------------------------------------
      //  Convert the original name into upper case format.

      $this->Name = $name;
      if ( $this->Name[0] != 'c' || $this->Name[1] != 's' )
         $this->Name = strtoupper( $this->Name );
      else
      if ( strncasecmp( $this->Name, "CSA7", 4 ) == 0 ) // Speciality for canadian CSA_Z243.4-1985*
         $this->Name = strtoupper( $this->Name );
      else
         $this->Name = "cs".strtoupper( substr( $this->Name, 2 ) );

      //-------------------------------------------------------------------------
      //  Convert the uppercased name into a format that contains only digits and
      //  letters.

      $this->Simplified = str_replace( array( "-", "_", ":", "." ), "", $this->Name );

      $this->Relation = array();

      //-------------------------------------------------------------------------
      //  Set the known bies to a default value.

      $this->KnownByIANA     = "no";
      $this->KnownByICONV    = "no";
      $this->KnownByLIBC     = "no";
      $this->KnownByICU      = "no";
      $this->KnownByUTR22    = "no";
      $this->KnownByJAVA     = "no";
      $this->KnownByMIME     = "no";
      $this->KnownByIBM      = "no";
      $this->KnownByWINDOWS  = "no";
      $this->KnownByUNTAGGED = "no";
      $this->KnownByMS       = "no";

   } // end Constructor

   /// @brief  Compares this alias to the other alias only by key.
   ///
   /// @param  $other      Other instance of alias to compare to
   /// @return signed int  The difference
   function CompareTo( $other )
   {
      if ( ($diff = $this->CharacterSet->CompareTo( $other->CharacterSet )) != 0 )
         return $diff;
      return strcmp( $this->Original, $other->Original );
   } // end CompareTo

   /// @brief  Indicates that the alias is an uppercased alias.
   ///
   /// @return bool        True iff the alias is uppercased
   function IsUpper()
   {
      return strcmp( $this->Original, $this->Name ) == 0;
   } // end IsUpper

   /// @brief  Indicates that the alias is a simplified alias.
   ///
   /// @return bool        True iff the alias is simplified
   function IsSimple()
   {
      return strcmp( $this->Original, $this->Simplified ) == 0;
   } // end IsSimple

   /// @brief  Set a data source to indicate that this alias its being known by
   ///         that data source.
   ///
   /// @param  $knownby    Name of the known by
   function SetKnownBy( $knownby )
   {
      if ( DEBUG_METHOD ) print "<p>Alias.SetKnownBy(".$knownby.")</p>".PHP_EOL;

      switch ( $knownby )
      {
         case KNOWN_BY_IANA     : $this->KnownByIANA     = "yes"; break;
         case KNOWN_BY_ICONV    : $this->KnownByICONV    = "yes"; break;
         case KNOWN_BY_LIBC     : $this->KnownByLIBC     = "yes"; break;
         case KNOWN_BY_ICU      : $this->KnownByICU      = "yes"; break;
         case KNOWN_BY_UTR22    : $this->KnownByUTR22    = "yes"; break;
         case KNOWN_BY_JAVA     : $this->KnownByJAVA     = "yes"; break;
         case KNOWN_BY_MIME     : $this->KnownByMIME     = "yes"; break;
         case KNOWN_BY_IBM      : $this->KnownByIBM      = "yes"; break;
         case KNOWN_BY_WINDOWS  : $this->KnownByWINDOWS  = "yes"; break;
         case KNOWN_BY_UNTAGGED : $this->KnownByUNTAGGED = "yes"; break;
         case KNOWN_BY_MS       : $this->KnownByMS       = "yes"; break;
      }
   } // end SetKnownBy

   /// @brief  Adds a relation to the alias. Returns true if the relation already
   ///         existed.
   ///
   /// @param  $datasource Name of the data source
   /// @param  $reference  Reference to the reference
   /// @return bool        True iff the relation existed already
   function SetRelation( $datasource, $reference )
   {
      if ( DEBUG_METHOD ) print "<p>Alias.SetRelation(".$datasource.", ".$reference->Identifier.")</p>".PHP_EOL;

      $relation = @$this->Relation[ $datasource.$reference->Identifier ];
      if ( isset( $relation ) )
         return true;

      $this->Relation[ $datasource.$reference->Identifier ] = new Relation( $this, $datasource, $reference );

      return false;

   } // end SetRelation

   /// @brief  Adds the Microsoft identification number to the character set.
   ///
   /// @param  $msid       The Microsooft integer code page number
   function SetMSID( $msid )
   {
      if ( DEBUG_METHOD ) print "<p>Alias.SetMSID(".$msid.")</p>".PHP_EOL;

      $this->MSID = $msid;
   } // end SetMSID

   /// @brief  Sets the IBM code page id and the URL to the IBM code page description.
   ///
   /// @param  $id         Number of the IBM code page
   /// @param  $url        Link to the IBM code page description
   function SetIBMCP( $id, $url )
   {
      if ( DEBUG_METHOD ) print "<p>Alias.SetIBMCP(".$id.", ".$url.")</p>".PHP_EOL;

      $this->IBMCPID  = $id;
      $this->IBMCPURL = $url;

   } // end SetIBMCP

   /// @brief  Sets the IBM coded character set id and the URL to the IBM code page
   ///         description.
   ///
   /// @param  $id         Number of the IBM coded character set
   /// @param  $url        Link to the IBM coded character set description
   function SetIBMCCS( $id, $url )
   {
      if ( DEBUG_METHOD ) print "<p>Alias.SetIBMCCS(".$id.", ".$url.")</p>".PHP_EOL;

      $this->IBMCCSID  = $id;
      $this->IBMCCSURL = $url;

   } // end SetIBMCCS

   /// @brief  Writes all information of the alias to XML.
   ///
   /// @param  $file       XML file to write to
   function WriteXML( $file )
   {
      fputs( $file, "         <Alias data-source=\"".$this->DataSource     ."\"".PHP_EOL );
      fputs( $file, "                original=\""   .$this->Original       ."\"".PHP_EOL );
      fputs( $file, "                name=\""       .$this->Name           ."\"".PHP_EOL );
      fputs( $file, "                simplified=\"" .$this->Simplified     ."\"".PHP_EOL );
      fputs( $file, "                IANA=\""       .$this->KnownByIANA    ."\"".PHP_EOL );
      fputs( $file, "                ICONV=\""      .$this->KnownByICONV   ."\"".PHP_EOL );
      fputs( $file, "                LIBC=\""       .$this->KnownByLIBC    ."\"".PHP_EOL );
      fputs( $file, "                ICU=\""        .$this->KnownByICU     ."\"".PHP_EOL );
      fputs( $file, "                UTR22=\""      .$this->KnownByUTR22   ."\"".PHP_EOL );
      fputs( $file, "                JAVA=\""       .$this->KnownByJAVA    ."\"".PHP_EOL );
      fputs( $file, "                MIME=\""       .$this->KnownByMIME    ."\"".PHP_EOL );
      fputs( $file, "                IBM=\""        .$this->KnownByIBM     ."\"".PHP_EOL );
      fputs( $file, "                WINDOWS=\""    .$this->KnownByWINDOWS ."\"".PHP_EOL );
      fputs( $file, "                UNTAGGED=\""   .$this->KnownByUNTAGGED."\"".PHP_EOL );
      fputs( $file, "                MS=\""         .$this->KnownByMS      ."\"".PHP_EOL );
      fputs( $file, "                MSID=\""       .$this->MSID           ."\"".PHP_EOL );
      fputs( $file, "                IBMCPID=\""    .$this->IBMCPID        ."\"".PHP_EOL );
      fputs( $file, "                IBMCPURL=\""   .$this->IBMCPURL       ."\"".PHP_EOL );
      fputs( $file, "                IBMCCSID=\""   .$this->IBMCCSID       ."\"".PHP_EOL );
      fputs( $file, "                IBMCCSURL=\""  .$this->IBMCCSURL      ."\">".PHP_EOL );
      foreach ( $this->Relation as $relation )
         $relation->WriteXML( $file );
      fputs( $file, "         </Alias>".PHP_EOL );
   } // end WriteXML
}; // endclass Alias


//============================================================================
//
//                  R e l a t i o n
//
/// @brief          Relation of an alias from data source to reference
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

class Relation
{
   var $Alias;             ///< Alias: Reference to the alias
   var $DataSource;        ///< string: Data source for this alias
   var $Reference;         ///< Reference: Reference to the reference

   /// @brief  Single constructor
   ///
   /// @param  $alias      Reference to the alias
   /// @param  $datasource Name of the data source
   /// @param  $reference  Reference to the reference
   function __construct( $alias, $datasource, $reference )
   {
      $this->Alias        = $alias;
      $this->DataSource   = $datasource;
      $this->Reference    = $reference;
   }

   /// @brief  Writes all information of the relation to XML.
   ///
   /// @param  $file       XML file to write to
   function WriteXML( $file )
   {
      fputs( $file, "            <Relation data-source=\"".$this->DataSource );
      fputs( $file, "\" xlink:href=\"".$this->Reference->Identifier."\"/>".PHP_EOL );
   } // end WriteXML
}; // endclass Relation


//============================================================================
//
//                  M e t a S e t
//
/// @brief          Set of meta information indexed by the encoding identifier
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$MetaSet = [];


//============================================================================
//
//                  G r o u p S e t
//
/// @brief          Set of groups indexed by the identifier
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2017-02-10 Wagner First release
///
//============================================================================

$GroupSet = [];


//============================================================================
//
//                  E x c l u s i o n S e t
//
/// @brief          Set of alias exclusions indexed by the data source and the
///                 original case sensistive alias name
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$ExclusionSet = array();


//============================================================================
//
//                  R e f e r e n c e S e t
//
/// @brief          Set of references indexed by the search name
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$ReferenceSet = array();


//============================================================================
//
//                  R e l a t i o n S e t
//
/// @brief          Set of relations indexed by the data source and the
///                 original alias name
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$RelationSet = array();


//============================================================================
//
//                  C h a r a c t e r S e t S e t
//
/// @brief          Set of character sets indexed by the encoding identifier
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$CharacterSetSet = array();


//============================================================================
//
//                  A l i a s S e t
//
/// @brief          Set of aliaes indexed by the original name
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner First release
///
//============================================================================

$AliasSet = array();


//============================================================================
//
//                  R e a d P a r a m e t e r s
//
/// @brief          Reads the parameters required by the EncodingCollector.
///
/// @param          $argc               Number of command line parameters
/// @param          $argv               Array of the command line parameters
/// @return         int                 Return code
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2017-01-18 Wagner   First release
///
//============================================================================

function ReadParameters( $argc, $argv )
{
   global $iana_character_sets;
   global $libiconv_path;
   global $icu_url;
   global $icu_base_url;
   global $microsoft_url;
   global $ibmcp_url;
   global $ibmccs_url;

   //-------------------------------------------------------------------------
   //  Check command line parameters.

   if ( $argc < 2 )
   {
      print "      <p>Usage: ".$argv[0]." &lt;parameter file&gt;</p>".PHP_EOL;
//      print "      <p>Usage: ".$argv[0]." [&lt;IANA character-sets.txt&gt; [&lt;libiconv-Path&gt; [&lt;ICU base URL&gt; [&lt;Microsoft URL&gt; [&lt;IBM code page URL&gt; [&lt;IBM coded character set URL&gt;] ] ] ] ] ]</p>".PHP_EOL;
   }

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $argv[1], "r")) == NULL )
   {
      print "         <h2 class=\"error\">Parameter file '".$argv[1]."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and create the meta information instances.

   $count = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         $parameters[ $field[0] ] = $field[1];
      }
   }
   extract( $parameters );

   //-------------------------------------------------------------------------
   //  Print what we have as parameters.

   print "         <p>Using IANA character sets from '<a href=\"".$iana_character_sets."\">".$iana_character_sets."</a>'</p>".PHP_EOL;
   print "         <p>Using GNU libiconv path at '<a href=\"".$libiconv_path."\">".$libiconv_path."</a>'</p>".PHP_EOL;
   print "         <p>Using ICU from '<a href=\"".$icu_url."\">".$icu_url."</a>'</p>".PHP_EOL;
   print "         <p>Using ICU base is '<a href=\"".$icu_base_url."\">".$icu_base_url."</a>'</p>".PHP_EOL;
   print "         <p>Using Microsoft from '<a href=\"".$microsoft_url."\">".$microsoft_url."</a>'</p>".PHP_EOL;
   print "         <p>Using IBM code pages from '<a href=\"".$ibmcp_url."\">".$ibmcp_url."</a>'</p>".PHP_EOL;
   print "         <p>Using IBM coded character sets from '<a href=\"".$ibmccs_url."\">".$ibmccs_url."</a>'</p>".PHP_EOL;

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> parameters read in.</p>\n";

   return 0;

} // end ReadParameters


//============================================================================
//
//                  R e a d M e t a
//
/// @brief          Reads the meta information from a CSV file.
///
/// @param          $filename           Name of file to read from
/// @return         int                 Return code
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function ReadMeta( $filename )
{
   global $GroupSet;
   global $MetaSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">Meta file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and create the meta information instances.

   $count       = 0;
   $count_unset = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( empty( $field[0] ) )
         $field[0] = "0";

      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         if ( strcmp( $field[2], "Group" ) == 0 )
         {
            $group = new Group( $field[4], $field[3], $field[6] );
            $GroupSet[ $field[4] ] = $group;
         }
         else
         {
            $meta = new Meta( $group,     $field[ 0], $field[ 1], $field[ 2]
                            , $field[ 3], $field[ 4], $field[ 5], $field[ 6]
                            , $field[ 7], $field[ 8], $field[ 9], $field[10]
                            , $field[11], $field[12], $field[13], $field[14]
                            , $field[15], $field[16], $field[17], $field[18] );
            $MetaSet[ $field[3] ] = $meta;
            if ( strlen( $field[6] ) == 0   // Description
              && strlen( $field[7] ) == 0   // Domain
              && strlen( $field[8] ) == 0 ) // Width
            {
               $count_unset++;
               print "         <p class=\"error\">Meta information for '".$field[3]."' is not set.</p>\n";
            }
         }
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> meta informations read in, <b>".$count_unset."</b> unset.</p>\n";

   return 0;

} // end ReadMeta


//============================================================================
//
//                  R e a d E x c l u s i o n
//
/// @brief          Reads the exclusion information from a CSV file.
///
/// @param          $filename           Name of file to read from
/// @return         int                 Return code
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function ReadExclusion( $filename )
{
   global $ExclusionSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">Exclusion file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and create the exclusion instances.

   $count = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         $exclusion = new Exclusion( $field[0], $field[1], $field[2], $field[3], $field[4] );
         $ExclusionSet[ $field[0].$field[1].$field[2] ] = $exclusion;
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> exclusions read in.</p>\n";

   return 0;

} // end ReadExclusion


//============================================================================
//
//                  R e a d R e f e r e n c e
//
/// @brief          Reads the reference information from a CSV file.
///
/// @param          $filename           Name of file to read from
/// @return         int                 Return code
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function ReadReference( $filename )
{
   global $ReferenceSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">Reference file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and create the reference instances.

   $count = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         $reference = new Reference( $field[0], $field[1], $field[2], $field[3], $field[4] );
         $ReferenceSet[ $field[0] ] = $reference;
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> references read in.</p>\n";

   return 0;

} // end ReadReference


//============================================================================
//
//                  R e a d R e l a t i o n
//
/// @brief          Reads the relation information from a CSV file.
///
/// @note           This depends on the references being read in.
///
/// @param          $filename           Name of file to read from
/// @return         int                 Return code
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner First release
///
//============================================================================

function ReadRelation( $filename )
{
   global $ReferenceSet;
   global $RelationSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">Relation file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and create the relation instances.

   $count = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         $RelationSet[ $field[0].$field[1] ][] = $ReferenceSet[ $field[2] ];
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> relations read in.</p>\n";

   return 0;

} // end ReadRelation


//============================================================================
//
//                  S e l e c t O r I n s e r t C h a r s e t
//
/// @brief          Tries to find a new character set. If not found than
///                 it will be created.
///
/// @param          $datasource         Name of the originating data source
/// @param          $aliasname          Name of character set
/// @param          $count              Counter for new character sets
/// @return         CharacterSet        The found or created character set
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-18 Wagner First release
///
//============================================================================

function SelectOrInsertCharset( $datasource, $aliasname, &$count )
{
   global $MetaSet;
   global $CharacterSetSet;

   //-------------------------------------------------------------------
   //  Look for the meta information.

   $meta = @$MetaSet[ $aliasname ];
   if ( !isset( $meta ) )
      print "         <p class=\"error\">".$datasource." encoding '".$aliasname."' is unknown.</p>\n";
   else
   if ( $datasource != $meta->DataSource )
   {
      if ( $datasource == DATASOURCE_IANA )
         print "         <p class=\"error\">".$datasource." differs from meta '".$meta->DataSource."' for character set '.".$aliasname."</p>\n";
      else
      if ( $datasource == DATASOURCE_ICONV && $meta->DataSource != DATASOURCE_IANA )
         print "         <p class=\"error\">".$datasource." differs from meta '".$meta->DataSource."' for character set '.".$aliasname."</p>\n";
      else
      if ( $datasource == DATASOURCE_ICU   && $meta->DataSource != DATASOURCE_IANA
                                           && $meta->DataSource != DATASOURCE_ICONV )
         print "         <p class=\"error\">".$datasource." differs from meta '".$meta->DataSource."' for character set '.".$aliasname."</p>\n";
      else
      if ( $datasource == DATASOURCE_JAVA  && $meta->DataSource != DATASOURCE_IANA
                                           && $meta->DataSource != DATASOURCE_ICONV
                                           && $meta->DataSource != DATASOURCE_ICU   )
         print "         <p class=\"error\">".$datasource." differs from meta '".$meta->DataSource."' for character set '.".$aliasname."</p>\n";
      else
      if ( $datasource == DATASOURCE_MS    && $meta->DataSource != DATASOURCE_IANA
                                           && $meta->DataSource != DATASOURCE_ICONV
                                           && $meta->DataSource != DATASOURCE_ICU
                                           && $meta->DataSource != DATASOURCE_JAVA  )
         print "         <p class=\"error\">".$datasource." differs from meta '".$meta->DataSource."' for character set '.".$aliasname."</p>\n";
   }

   //-------------------------------------------------------------------
   //  Find or create the new character set.

   if ( isset( $meta ) )
   {
      $meta->SetUsed();
      $charset = @$CharacterSetSet[ $meta->Identifier ];
   }
   else
      $charset = @$CharacterSetSet[ $aliasname ];

   if ( !isset( $charset ) )
   {
      $charset = new CharacterSet( $datasource, $aliasname, $meta );
      $CharacterSetSet[ isset( $meta ) ? $meta->Identifier : $aliasname ] = $charset;
      print "         <p>".$datasource." character set <b>".$aliasname."</b> is new.</p>\n";
      $count++;
   }
   $charset->SetDataSource( $datasource );

   //-------------------------------------------------------------------------
   //  Good bye ...

   return $charset;

} // end SelectOrInsertCharset


//============================================================================
//
//                  S e l e c t O r I n s e r t A l i a s
//
/// @brief          Tries to find a new alias in a character set. If not found
///                 than it will be created. Creates a simplified version of
///                 the alias, too if not done already.
///
/// @details        This also adds the simplified version of the alias.
///                 Relations will be added, too.
///
/// @param          $datasource         Name of the data source
/// @param          $aliasname          Original name of the new alias
/// @param          $charset            The character set to which the new
///                                     alias will be added
/// @param          $reflist            List of references
/// @param          $preferred          Indicates that the new alias is the
///                                     preferred one for this character set
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-18 Wagner First release
///
//============================================================================

function SelectOrInsertAlias( $datasource, $aliasname, $charset, $reflist, $preferred )
{
   global $ExclusionSet;
   global $RelationSet;

   //----------------------------------------------------------------------
   //  Ignore exclusions.

   if ( isset( $ExclusionSet[ $charset->Identifier.$datasource.$aliasname ] ) )
      return;

   //-------------------------------------------------------------------
   //  Create the international register.

   if ( strncasecmp( $aliasname, "ISO-IR-", 7 ) == 0 )
      $charset->SetISOIR( substr( $aliasname, 7 ) );

   //----------------------------------------------------------------------
   //  Map data source to known by.

   switch ( $datasource )
   {
      case DATASOURCE_SELF   : $knownby = KNOWN_BY_SELF;   break;
      case DATASOURCE_IANA   : $knownby = KNOWN_BY_IANA;   break;
      case DATASOURCE_ICONV  : $knownby = KNOWN_BY_ICONV;  break;
      case DATASOURCE_ICU    : $knownby = KNOWN_BY_ICU;    break;
      case DATASOURCE_JAVA   : $knownby = KNOWN_BY_JAVA;   break;
      case DATASOURCE_MS     : $knownby = KNOWN_BY_MS;     break;
      case DATASOURCE_IBMCP  : $knownby = KNOWN_BY_IBMCP;  break;
      case DATASOURCE_IBMCCS : $knownby = KNOWN_BY_IBMCCS; break;
   }

   //----------------------------------------------------------------------
   //  Find or insert the new alias.

   $alias = $charset->AddAlias( $datasource, $aliasname, $preferred );
   $alias->SetKnownBy( $knownby );
   foreach ( $reflist as $reference )
      $alias->SetRelation( $datasource, $reference );
   $references = @$RelationSet[ $datasource.$aliasname ];
   if ( isset( $references ) )
      foreach ( $references as $reference )
         $alias->SetRelation( $datasource, $reference );
   $references = @$RelationSet[ DATASOURCE_SELF.$aliasname ];
   if ( isset( $references ) )
      foreach ( $references as $reference )
         $alias->SetRelation( DATASOURCE_SELF, $reference );

   //----------------------------------------------------------------------
   //  Find or insert the uppercased alias.

   if ( !$alias->IsUpper()
     && !isset( $ExclusionSet[ $charset->Identifier.$datasource.$alias->Name ] ) )
   {
      $upper = $charset->AddAlias( $datasource, $alias->Name, false );
      $upper->SetKnownBy( $knownby );
      foreach ( $reflist as $reference )
         $upper->SetRelation( $datasource, $reference );
      $references = @$RelationSet[ $datasource.$aliasname ];
      if ( isset( $references ) )
         foreach ( $references as $reference )
            $upper->SetRelation( $datasource, $reference );
      $references = @$RelationSet[ DATASOURCE_SELF.$aliasname ];
      if ( isset( $references ) )
         foreach ( $references as $reference )
            $upper->SetRelation( DATASOURCE_SELF, $reference );
   } // endif Not uppercased and not excluded

   //----------------------------------------------------------------------
   //  Find or insert the simplified alias.

   if ( !$alias->IsSimple()
     && !isset( $ExclusionSet[ $charset->Identifier.$datasource.$alias->Simplified ] ) )
   {
      $simple = $charset->AddAlias( $datasource, $alias->Simplified, false );
      $simple->SetKnownBy( $knownby );
      foreach ( $reflist as $reference )
         $simple->SetRelation( $datasource, $reference );
      $references = @$RelationSet[ $datasource.$aliasname ];
      if ( isset( $references ) )
         foreach ( $references as $reference )
            $simple->SetRelation( $datasource, $reference );
      $references = @$RelationSet[ DATASOURCE_SELF.$aliasname ];
      if ( isset( $references ) )
         foreach ( $references as $reference )
            $simple->SetRelation( DATASOURCE_SELF, $reference );
   } // endif Not simple and not excluded

   //-------------------------------------------------------------------------
   //  Good bye ...

   return $alias;

} // end SelectOrInsertAlias


//============================================================================
//
//                  R e f n a m e s T o A r r a y
//
/// @brief          Transforms an comma separated list of reference names to
///                 an array of references.
///
/// @param          $datasource         Name of the originating data source
/// @param          $refnamelist        Array of reference names
/// @return         Reference[]         The array of references
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-18 Wagner First release
///
//============================================================================

function RefnamesToArray( $datasource, $refnamelist )
{
   global $ReferenceSet;

   //-------------------------------------------------------------------------
   //  Find the references from comma separated string list.

   $result = array();

   if ( $refnamelist != "" )
   {
      $refnames = explode( ",", strtoupper( str_replace( array( "[", "]", ".", "/", "-", " " ), "", $refnamelist ) ) );
      foreach ( $refnames as $refname )
      {
         $ref = @$ReferenceSet[ $refname ];
         if ( isset( $ref ) )
            $result[] = $ref;
         else
            print "         <p class=\"error\">".$datasource." reference ".$refname." is unknown.</p>\n";
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   return $result;

} // end RefnamesToArray


//============================================================================
//
//                  R e a d I A N A
//
/// @brief          Reads the IANA character-sets.txt file.
///
/// @param          $filename           Name of file to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-18 Wagner Redesigned to use @c preg_match
/// @date           2016-12-11 Wagner First release
///
//============================================================================

DEFINE( "IANA_STATE_UNDEFINED",     0 ); ///< We're in the undefined state
DEFINE( "IANA_STATE_CHARACTERSETS", 1 ); ///< We're in the state of reading character sets
DEFINE( "IANA_STATE_REFERENCES",    2 ); ///< We're in the state of reading references

function ReadIANA( $filename )
{
   global $ReferenceSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r" )) == NULL )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IANA." file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create empty reference list.

   $emptyreflist = RefnamesToArray( DATASOURCE_IANA, "" );

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set and reference instances.

   $state = IANA_STATE_UNDEFINED;
   $count = 0;
   $count_new = 0;
   while ( ($line = fgets( $file )) != NULL )   // Does strip HTML tags!!!
   {
      $line = trim( $line );
      if ( DEBUG_IANA ) print "<p>Line: ".$line."</p>".PHP_EOL;

      //----------------------------------------------------------------------
      //  Determine what to do.

      if ( strlen( $line ) == 0 )
      {
         // Skip empty line.
      }
      else
      if ( strncmp( $line, "------", 6 ) == 0 )
      {
         //  Skip underline.
      }
      else
      if ( strcmp( $line, "[]" ) == 0 )
      {
         //  Skip empty reference.
      }
      else
      if ( strcmp( $line, "REFERENCES" ) == 0 )
      {
         $state = IANA_STATE_REFERENCES;
      }
      else
      if ( strcmp( $line, "PEOPLE" ) == 0 )
      {
         $state = IANA_STATE_REFERENCES;
      }
      else
      if ( preg_match( "#^Character Set\h+Reference$#", $line, $match ) == 1 )
      {
         $state = IANA_STATE_CHARACTERSETS;
      }
      else
      if ( $state == IANA_STATE_CHARACTERSETS
        && preg_match( "#^Name\:\h+([\w\_\-\.\:\(\)]+)\h*(\(preferred MIME name\))?\h*(\[[\w\-]+(,[\w\-]+)*\])?$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>Name:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         //-------------------------------------------------------------------
         //  Ignore the text "(preferred MIME name)" in match[2].
         //  The first alias is the preferred one by default.

         //-------------------------------------------------------------------
         //  Create the reference list.

         if ( isset( $match[3] ) )
            $reflist = RefnamesToArray( DATASOURCE_IANA, $match[3] );
         else
            $reflist = $emptyreflist;

         //-------------------------------------------------------------------
         //  Create the new character set.

         $charset = SelectOrInsertCharset( DATASOURCE_IANA, $match[1], $count_new );
         $count++;
         SelectOrInsertAlias( DATASOURCE_IANA, $match[1], $charset, $reflist, false );
      }
      else
      if ( $state == IANA_STATE_CHARACTERSETS
        && preg_match( "#^(Alias|Aliases)\:\h+([\w\_\-\:\.\+]+)\h*(\(preferred MIME name\))?$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>Alias:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         $aliasname = $match[2];
         if ( strcmp( $aliasname, "None" ) == 0 )
            continue;

         //-------------------------------------------------------------------
         //  Create the alias.

         if ( isset( $match[3] ) && strcmp( $match[3], "(preferred MIME name)" ) == 0 )
            SelectOrInsertAlias( DATASOURCE_IANA, $aliasname, $charset, $reflist, true  );
         else
            SelectOrInsertAlias( DATASOURCE_IANA, $aliasname, $charset, $reflist, false );
      }
      else
      if ( $state == IANA_STATE_CHARACTERSETS
        && preg_match( "#^MIBenum\:\h+(\d+)$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>MIBenum:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         $charset->SetMIBEnum( intval( $match[1] ) );
      }
      else
      if ( $state == IANA_STATE_CHARACTERSETS
        && preg_match( "#^\h*\[Malyshev\]$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>[Malyshev]:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         // Speciality for Amiga 1251
         $reflist = array_merge( $reflist, RefnamesToArray( DATASOURCE_IANA, "Malyshev" ) );
         $charset->SetRelationAll( DATASOURCE_IANA, $reflist );
      }
      else
      if ( $state == IANA_STATE_CHARACTERSETS
        && preg_match( "#^(Source\:)?\h*([^\[]+)(\[[\w\-]+(,[\w\-]+)*\])?$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>Source:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         $line = trim( $match[2] );

         if ( isset( $match[3] ) )
         {
            $reflist = array_merge( $reflist, RefnamesToArray( DATASOURCE_IANA, $match[3] ) );
            $charset->SetRelationAll( DATASOURCE_IANA, $reflist );
         }

         if ( strlen( $line ) == 0 )
            continue;
         else
         if ( strncmp( $line, "ECMA registry", 13 ) == 0 )
         {
            $charset->AppendIANASource( $line );
            $reflist = array_merge( $reflist, RefnamesToArray( DATASOURCE_IANA, "ECMA" ) );
            $charset->SetRelationAll( DATASOURCE_IANA, $reflist );
            continue;
         }
         else
         if ( preg_match( "#^ISO(\hregistry\h+\(formerly ECMA registry\))?$#", $line, $match ) == 1 )
         {
            $charset->AppendIANASource( $line );
            $reflist = array_merge( $reflist, RefnamesToArray( DATASOURCE_IANA, "ISO" ) );
            $charset->SetRelationAll( DATASOURCE_IANA, $reflist );
            continue;
         }
         else
         if ( preg_match( "#^(.*)?\h*PCL\hSymbol\hSet\h[iI]d\:\h+(\w+)$#", $line, $match ) == 1 )
         {
            if ( DEBUG_IANA ) print "<p>PCL:".PHP_EOL;
            if ( DEBUG_IANA ) var_dump( $match );
            if ( DEBUG_IANA ) print "</p>".PHP_EOL;

            if ( strlen( $match[1] ) != 0 )
               $charset->AppendIANASource( trim( $match[1] ) );
            $charset->SetPCL5SymbolSetId( $match[2] );
            continue;
         }
         else
         if ( strncmp( $line, "ISO", 3 ) == 0 )
         {
            $charset->AppendIANASource( "ISO" );
            $line = trim( substr( $line, 3 ) );
            if ( DEBUG_IANA ) print "<p>ISO: ".$line.PHP_EOL;
         }
         else
         if ( strncmp( $line, "Microsoft", 9 ) == 0 )
         {
            $charset->AppendIANASource( "Microsoft" );
            $line = trim( substr( $line, 9 ) );
            if ( DEBUG_IANA ) print "<p>Microsoft: ".$line.PHP_EOL;
         }
         else
         if ( strncmp( $line, "IBM", 3 ) == 0 )
         {
            $charset->AppendIANASource( "IBM" );
            $line = trim( substr( $line, 3 ) );
            if ( DEBUG_IANA ) print "<p>IBM: ".$line.PHP_EOL;
         }
         else
         if ( strncmp( $line, "SCSU", 4 ) == 0 )
         {
            $charset->AppendIANASource( "SCSU" );
            $line = trim( substr( $line, 4 ) );
            if ( DEBUG_IANA ) print "<p>SCSU: ".$line.PHP_EOL;
         }

         if ( preg_match( "#^(see|Please see\:|See)?\h*[\(\<]?(http\:\/\/[\w\-\.\/\_]+)[\)\>]?$#", $line, $match ) == 1 )
         {
            if ( DEBUG_IANA ) print "<p>URL:".PHP_EOL;
            if ( DEBUG_IANA ) var_dump( $match );
            if ( DEBUG_IANA ) print "</p>".PHP_EOL;

            $charset->SetIANAURL( $match[2] );
         }
         else
         if ( preg_match( "#^(RFC[-\ ]?\d+)\h*(, )?(.*)$#", $line, $match ) == 1 )
         {
            if ( DEBUG_IANA ) print "<p>RFC:".PHP_EOL;
            if ( DEBUG_IANA ) var_dump( $match );
            if ( DEBUG_IANA ) print "</p>".PHP_EOL;

            $rfc = strtolower( str_replace( array( "-", " " ), "", $match[1] ) );
            $charset->SetIANAURL( "http://www.ietf.org/rfc/".$rfc.".txt" );

            if ( strlen( $match[3] ) != 0 )
               $charset->AppendIANASource( $match[3] );
         }
         else
         if ( strlen( $line ) != 0 )
            $charset->AppendIANASource( $line );
      }
      else
      if ( $state == IANA_STATE_REFERENCES
        && preg_match( "#^\[([\w\-\ ]+)\]\h+(.*)$#", $line, $match ) == 1 )
      {
         if ( DEBUG_IANA ) print "<p>Reference:".PHP_EOL;
         if ( DEBUG_IANA ) var_dump( $match );
         if ( DEBUG_IANA ) print "</p>".PHP_EOL;

         if ( strcmp( $match[1], "ECMA Registry" ) == 0 )
            $match[1] = "ECMA";
         $refname = strtoupper( str_replace( "-", "", $match[1] ) );
         $reference = $ReferenceSet[ $refname ];
         if ( !isset( $reference ) )
         {
            print "         <p class=\"error\">IANA reference ".$refname." is unknown.</p>\n";
            $reference = new Reference( $refname );
            $ReferenceSet[ $refname ] = $reference;
         }
         if ( strncmp( $match[1], "RFC", 3 ) == 0 )
            $reference->SetURL( "http://www.ietf.org/rfc/".strtolower( $match[1] ).".txt" );
         $line = $match[2];

         //-------------------------------------------------------------------
         //  Add the text.

         if ( preg_match( "#^([^\<]+)\,?\h+\<([\w\.\&\@\-]+)\>(.*)$#", $line, $match ) == 1 )
         {
            if ( DEBUG_IANA ) print "<p>People:".PHP_EOL;
            if ( DEBUG_IANA ) var_dump( $match );
            if ( DEBUG_IANA ) print "</p>".PHP_EOL;

            $reference->AppendText( str_replace( "&", "&amp;", $match[1] ) );
            //  Handle damaged email address.
            $reference->SetURL( "mailto:".str_replace( '&', '@', $match[2] ) );
            if ( strlen( $match[3] ) != 0 )
               $reference->AppendText( str_replace( "&", "&amp;", $match[3] ) );
         }
         else
            $reference->AppendText( str_replace( "&", "&amp;", $line ) );
      }
      else
      if ( $state == IANA_STATE_REFERENCES )
      {
         if ( preg_match( "#^(http:\/\/[^\h]+)\h(.*)$#", $line, $match ) == 1 )
         {
            if ( DEBUG_IANA ) print "<p>Reference URL2:".PHP_EOL;
            if ( DEBUG_IANA ) var_dump( $match );
            if ( DEBUG_IANA ) print "</p>".PHP_EOL;

            $reference->SetURL( $match[1] );
            $reference->AppendText( str_replace( "&", "&amp;", $match[2] ) );
         }
         else
            $reference->AppendText( $line );
      } // endif Patterns
      else
      {
         // Anything else will be ignored.
         if ( DEBUG_IANA )
            print "         <p class=\"error\">".DATASOURCE_IANA." line '".$line."' not processed.</p>".PHP_EOL;
      }
   } // endwhile Lines

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> ".DATASOURCE_IANA." character sets read in, <b>".$count_new."</b> new.</p>\n";

   return 0;

} // end ReadIANA


//============================================================================
//
//                  R e a d I C O N V
//
/// @brief          Reads an ICONV encoding definitions file and creates
///                 even more character set instances.
///
/// @param          $filename           Name of file to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

DEFINE( "ICONV_STATE_UNDEFINED", 0 ); ///< We're in the undefined state
DEFINE( "ICONV_STATE_COMMENT",   1 ); ///< We're in the state of reading a block comment

function ReadICONV( $filename )
{
   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">".DATASOURCE_ICONV." file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create empty reference list.

   $emptyreflist = RefnamesToArray( DATASOURCE_ICONV, "" );

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set instances.

   $state = ICONV_STATE_UNDEFINED;
   $count = 0;
   $count_new = 0;
   while ( ($line = fgets( $file )) != NULL )
   {
      $line = trim( $line );
      $reflist = $emptyreflist;

      //----------------------------------------------------------------------
      //  Determine what to do.

      if ( strlen( $line ) == 0 )
         // Skip empty line.
         continue;
      else
      if ( strcmp( $line, ")," ) == 0 )
         // Skip closing entry.
         continue;
      else
      if ( $state == ICONV_STATE_COMMENT && preg_match( "#^.*\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>CommentEnd:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         // Skip ending comment line.
         $state = ICONV_STATE_UNDEFINED;
         continue;
      }
      else
      if ( $state == ICONV_STATE_COMMENT )
         // Skip inner comment line.
         continue;
      else
      if ( preg_match( "#^\/\*\"([\w\-\:\.]+)\"\,\h*([\w\h\/\-\.]+)(\,\h*([\w\h\/\-\.]+)*)?\h\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>ComAlias:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = RefnamesToArray( DATASOURCE_ICONV, $match[2] );
      }
      else
      if ( preg_match( "#^\/\*.*\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>CommentLine:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         // Skip single comment line.
         continue;
      }
      else
      if ( preg_match( "#^\/\*.*$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>CommentBegin:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $state = ICONV_STATE_COMMENT;
         continue;
      }
      else
      if ( preg_match( "#^DEFENCODING\(\(\h*\"([\w\-\:\.]*)\"\,\h*\/\*\h([\w\h\/\-\.]+(\,\h[\w\h\/\-\.]+)*)\h\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>DefEncoding:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = RefnamesToArray( DATASOURCE_ICONV, $match[2] );

         //-------------------------------------------------------------------
         //  Create the new character set.

         $charset = SelectOrInsertCharset( DATASOURCE_ICONV, $aliasname, $count_new );
         $count++;
      }
      else
      if ( preg_match( "#^DEFENCODING\(\(\h*\"([\w\-\:\.]*)\"\,$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>DefEncoding:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = $emptyreflist;

         //-------------------------------------------------------------------
         //  Create the new character set.

         $charset = SelectOrInsertCharset( DATASOURCE_ICONV, $aliasname, $count_new );
         $count++;
      }
      else
      if ( preg_match( "#^DEFALIAS\(\h*\"([\w\-\:\.]*)\"\,\h*\/\*\h([\w\h\/\-\.]+)\h\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>DefAlias:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = RefnamesToArray( DATASOURCE_ICONV, $match[2] );
      }
      else
      if ( preg_match( "#^\"([\w\-\:\.]*)\"\,\h*\/\*\h([\w\h\/\-\.]+(,\h[\w\h\/\-\.]+)*)+\h\*\/$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>AliasRefs:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = RefnamesToArray( DATASOURCE_IANA, $match[2] );
      }
      else
      if ( preg_match( "#^\"([\w\-\:\.]*)\"\,$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>Alias:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         $aliasname = $match[1];
         $reflist   = $emptyreflist;
      }
      else
      if ( preg_match( "#^\{\h(\w+)\,\h(\w+)\h\}\,\h*\{\h(\w+)\,\h(\w+)\h\}\)$#", $line, $match ) == 1 )
      {
         if ( DEBUG_ICONV ) print "<p>Functions:".PHP_EOL;
         if ( DEBUG_ICONV ) var_dump( $match );
         if ( DEBUG_ICONV ) print "</p>".PHP_EOL;

         if ( strcmp( $charset->Identifier, "UCS_2" ) != 0
           && strcmp( $charset->Identifier, "UCS_4" ) != 0 )
         {
            // Ignore the message for ICONV UCS_2 and UCS_4.
            // We want to use the internal version of the converters
            // aka UCS_2_INTERNAL and UCS_4_INTERNAL.
            if ( $charset->HasICONVFunctions() )
               print "         <p class=\"error\">".DATASOURCE_ICONV." functions for '".$charset->Identifier."' already set (".$charset->MbtowcFunc." vs. ".$match[1].").</p>".PHP_EOL;
         }
         $charset->SetICONVFunctions( str_replace( "NULL", "", $match[1] )
                                    , str_replace( "NULL", "", $match[2] )
                                    , str_replace( "NULL", "", $match[3] )
                                    , str_replace( "NULL", "", $match[4] )  );
         continue;
      }
      else
      {
         // Leave the state as is.
         if ( DEBUG_ICONV )
            print "         <p class=\"error\">".DATASOURCE_ICONV." line '".$line."' not processed.</p>".PHP_EOL;
      }

      //-------------------------------------------------------------------------
      //  Add the alias with its references.

      SelectOrInsertAlias( DATASOURCE_ICONV, $aliasname, $charset, $reflist, false );
   } // endwhile Lines

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> ".DATASOURCE_ICONV." character sets read in, <b>".$count_new."</b> new.</p>\n";

   return 0;

} // end ReadICONV


//============================================================================
//
//                  e l e m e n t 2 a r r a y
//
/// @brief          Helps to parse the ICU converter list.
///
/// @param          $element            HTML td or th element with the text
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function element2array( $element )
{
   $result = array();
   foreach ( $element->childNodes as $node )
   {
      if ( $node->nodeType == XML_TEXT_NODE
        && strlen( $value = trim( $node->nodeValue ) ) != 0
        && $value != "\xC2\xA0" ) // non-breaking space in UTF-8
         $result[] = $value;
   }
   return $result;
}

//============================================================================
//
//                  R e a d I C U
//
/// @brief          Reads the ICU main web page and creates even more
///                 character set instances.
///
/// @param          $filename           URL to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function ReadICU( $filename )
{
   global $icu_base_url;

   DEFINE( "ICU_COLUMN_ICUNAME",  0 );
   DEFINE( "ICU_COLUMN_UTR22",    1 );
   DEFINE( "ICU_COLUMN_IBM",      2 );
   DEFINE( "ICU_COLUMN_WINDOWS",  3 );
   DEFINE( "ICU_COLUMN_JAVA",     4 );
   DEFINE( "ICU_COLUMN_IANA",     5 );
   DEFINE( "ICU_COLUMN_MIME",     6 );
   DEFINE( "ICU_COLUMN_UNTAGGED", 7 );
   DEFINE( "ICU_COLUMN_ALL",      8 );

   //-------------------------------------------------------------------------
   //  Open the input file.

   $document = new DOMDocument();
   $document->preserveWhiteSpace = false;
   if ( $document->loadHTMLFile( $filename ) == false )
   {
      print "         <h2 class=\"error\">".DATASOURCE_ICU." file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create reference lists.

   $emptyreflist    = RefnamesToArray( DATASOURCE_ICU, ""         );
   $utr22reflist    = RefnamesToArray( DATASOURCE_ICU, "UTR22"    );
   $ibmreflist      = RefnamesToArray( DATASOURCE_ICU, "IBM"      );
   $windowsreflist  = RefnamesToArray( DATASOURCE_ICU, "WINDOWS"  );
   $javareflist     = RefnamesToArray( DATASOURCE_ICU, "JAVA"     );
   $ianareflist     = RefnamesToArray( DATASOURCE_ICU, "IANA"     );
   $mimereflist     = RefnamesToArray( DATASOURCE_ICU, "MIME"     );
   $untaggedreflist = RefnamesToArray( DATASOURCE_ICU, "UNTAGGED" );

   //-------------------------------------------------------------------------
   //  Get some table header information.

   $div = $document->getElementById( "main" );
   $tables = $div->getElementsByTagName( "table" );
   foreach ( $tables as $table )
     if ( $table->getAttribute( "class" ) != NULL && strcmp( $table->getAttribute( "class" ), "data-table-2" ) == 0 )
        break;
   if ( $table == NULL )
   {
      print "         <h2 class=\"error\">".DATASOURCE_ICU." file '".$filename."' doesn't contain a table with class 'data-table-2'.</h2>".PHP_EOL;
      return 1;
   }

   $column2index[ "InternalConverter Name" ] = ICU_COLUMN_ICUNAME;
   $column2index[ "UTR22"                  ] = ICU_COLUMN_UTR22;
   $column2index[ "IBM"                    ] = ICU_COLUMN_IBM;
   $column2index[ "WINDOWS"                ] = ICU_COLUMN_WINDOWS;
   $column2index[ "JAVA"                   ] = ICU_COLUMN_JAVA;
   $column2index[ "IANA"                   ] = ICU_COLUMN_IANA;
   $column2index[ "MIME"                   ] = ICU_COLUMN_MIME;
   $column2index[ "Untagged Aliases"       ] = ICU_COLUMN_UNTAGGED;
   $column2index[ "All Aliases"            ] = ICU_COLUMN_ALL;

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set instances.

   $headers_seen = false;
   $count = 0;
   $count_new = 0;
   foreach ( $table->getElementsByTagName( "tr" ) as $row )
   {
      //----------------------------------------------------------------------
      //  Parse the table row.

      $i = 0;
      foreach ( $row->childNodes as $column )
      {
         if ( $column->nodeType == XML_ELEMENT_NODE )
         {
            if ( $headers_seen == false && strcmp( $column->tagName, "th" ) == 0 )
            {
               $headmap[ $i ] = $column2index[ $column->textContent ];
               $i++;
            }
            else
            if ( strcmp( $column->tagName, "td" ) == 0 || strcmp( $column->tagName, "th" ) == 0 )
            {
               switch ( $headmap[ $i ] )
               {
                  case ICU_COLUMN_ICUNAME  : $icunode       = $column->firstChild;  break;
                  case ICU_COLUMN_UTR22    : $utr22names    = element2array( $column ); break;
                  case ICU_COLUMN_IBM      : $ibmnames      = element2array( $column ); break;
                  case ICU_COLUMN_WINDOWS  : $windowsnames  = element2array( $column ); break;
                  case ICU_COLUMN_JAVA     : $javanames     = element2array( $column ); break;
                  case ICU_COLUMN_IANA     : $iananames     = element2array( $column ); break;
                  case ICU_COLUMN_MIME     : $mimenames     = element2array( $column ); break;
                  case ICU_COLUMN_UNTAGGED : $untaggednames = element2array( $column ); break;
               }
               $i++;
            }
            else
            {
               print "         <p class=\"error\">".DATASOURCE_ICU." content '".$column->tagName."' cannot be processed.</p>".PHP_EOL;
               return 2;
            }
         }
      } // endfor Columns

      if ( $headers_seen == false )
      {
         if ( DEBUG_ICU ) print "<p>Headermap:".PHP_EOL;
         if ( DEBUG_ICU ) var_dump( $headmap );
         if ( DEBUG_ICU ) print "</p>".PHP_EOL;

         $headers_seen = true;
         continue;
      }
      else
      {
         if ( DEBUG_ICU ) print "<p>Data:".PHP_EOL;
         if ( DEBUG_ICU ) var_dump( $icunode, $utr22names, $ibmnames, $windowsnames, $javanames, $iananames, $mimenames, $untaggednames );
         if ( DEBUG_ICU ) print "</p>".PHP_EOL;
      }

      //-------------------------------------------------------------------
      //  Create the new character set.

      $charset = SelectOrInsertCharset( DATASOURCE_ICU, $icunode->textContent, $count_new );
      $count++;
      if ( $icunode->tagName == "a" )
         $charset->SetICU( $icunode->textContent, $icu_base_url.$icunode->getAttribute( "href" )."&ShowLocales&ShowUnavailable=" );
      else
         print "         <p class=\"error\">".DATASOURCE_ICU." anchor '".$icunode->textContent."' is not linkable.</p>\n";

      //-------------------------------------------------------------------------
      //  Add the aliases and their references.

      SelectOrInsertAlias( DATASOURCE_ICU, $icunode->textContent, $charset, $emptyreflist, false );

      foreach ( $utr22names as $utr22name )
         SelectOrInsertAlias( DATASOURCE_ICU, $utr22name, $charset, $utr22reflist, false );

      foreach ( $ibmnames as $ibmname )
         SelectOrInsertAlias( DATASOURCE_ICU, $ibmname, $charset, $ibmreflist, false );

      foreach ( $windowsnames as $windowsname )
         SelectOrInsertAlias( DATASOURCE_ICU, $windowsname, $charset, $windowsreflist, false );

      foreach ( $javanames as $javaname )
         SelectOrInsertAlias( DATASOURCE_ICU, $javaname, $charset, $javareflist, false );

      foreach ( $iananames as $iananame )
         SelectOrInsertAlias( DATASOURCE_ICU, $iananame, $charset, $ianareflist, false );

      foreach ( $mimenames as $mimename )
         SelectOrInsertAlias( DATASOURCE_ICU, $mimename, $charset, $mimereflist, false );

      foreach ( $untaggednames as $untaggedname )
         SelectOrInsertAlias( DATASOURCE_ICU, $untaggedname, $charset, $untaggedreflist, false );

   } // endfor Rows

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> ".DATASOURCE_ICU." character sets read in, <b>".$count_new."</b> new.</p>\n";

   return 0;

} // end ReadICU


//============================================================================
//
//                  C r e a t e J A V A
//
/// @brief          Creates the JAVA character set list into
///                 @c CharsetLister.java.
///
/// @param          $filename           XML character set list to write to
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function CreateJAVA( $filename )
{
   //-------------------------------------------------------------------------
   //  Run Java.

   exec( "java JavaCharsetLister ".$filename, $output, $rc );
   if ( DEBUG_JAVA ) var_dump( $output );

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".(count( $output ) - 1)."</b> ".DATASOURCE_JAVA." character sets written.</p>\n";

   return $rc;

} // end CreateJAVA


//============================================================================
//
//                  R e a d J A V A
//
/// @brief          Reads the JAVA character set list produced by
///                 @c CharsetListe.java and creates even more character set
///                 instances.
///
/// @param          $filename           XML character set list to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function ReadJAVA( $filename )
{
   //-------------------------------------------------------------------------
   //  Open the input file.

   $document = new DOMDocument();
   $document->preserveWhiteSpace = false;
   if ( $document->load( $filename ) == false )
   {
      print "         <h2 class=\"error\">".DATASOURCE_JAVA." file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create reference lists.

   $emptyreflist = RefnamesToArray( DATASOURCE_JAVA, ""     );
   $ianareflist  = RefnamesToArray( DATASOURCE_JAVA, "IANA" );

   //-------------------------------------------------------------------------
   //  Read the elements and create the character set instances.

   $count = 0;
   $count_new = 0;
   foreach ( $document->getElementsByTagName( "Charset" ) as $entry )
   {
      //-------------------------------------------------------------------
      //  Create the new character set.

      if ( DEBUG_JAVA ) print "<p>Charset:".PHP_EOL;
      if ( DEBUG_JAVA ) var_dump( $entry );
      if ( DEBUG_JAVA ) print "</p>".PHP_EOL;

      $charsetname = $entry->getAttribute( "name" );
      $charset = SelectOrInsertCharset( DATASOURCE_JAVA, $charsetname, $count_new );
      $count++;

      //-------------------------------------------------------------------------
      //  Add the aliases and their references.

      if ( $entry->getAttribute( "iana-registered" ) == "yes" )
         $reflist = $ianareflist;
      else
         $reflist = $emptyreflist;
      SelectOrInsertAlias( DATASOURCE_JAVA, $charsetname, $charset, $reflist, false );

      foreach ( $entry->getElementsByTagName( "Alias" ) as $alias )
      {
         if ( DEBUG_JAVA ) print "<p>Alias:".PHP_EOL;
         if ( DEBUG_JAVA ) var_dump( $alias );
         if ( DEBUG_JAVA ) print "</p>".PHP_EOL;

         SelectOrInsertAlias( DATASOURCE_JAVA, $alias->getAttribute( "name" ), $charset, $emptyreflist, false );
      }
   } // endfor Rows

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> ".DATASOURCE_JAVA." character sets read in, <b>".$count_new."</b> new.</p>\n";

   return 0;

} // end ReadJAVA


//============================================================================
//
//                  R e a d M i c r o s o f t
//
/// @brief          Reads the Microsoft code page list web page and creates
///                 even more character set instances.
///
/// @param          $filename           URL to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner   First release
///
//============================================================================

function ReadMicrosoft( $filename )
{
   DEFINE( "MS_COLUMN_IDENTIFIER",  0 );
   DEFINE( "MS_COLUMN_DOTNETNAME",  1 );
   DEFINE( "MS_COLUMN_DESCRIPTION", 2 );

   //-------------------------------------------------------------------------
   //  Open the input file.
   //  Unfortunately, the builtin XML parser isn't able to handle HTML5 tags
   //  such as header, nav, main, or footer. So, suppress warnings.

   $document = new DOMDocument();
   $document->preserveWhiteSpace = false;
   if ( @$document->loadHTMLFile( $filename ) == false )
   {
      print "         <h2 class=\"error\">".DATASOURCE_MS." file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create the reference list.

   $reflist = RefnamesToArray( DATASOURCE_MS, "MICROSOFT" );

   //-------------------------------------------------------------------------
   //  Get some table header information.

   $tables = $document->getElementsByTagName( "table" );
   foreach ( $tables as $table )
     if ( $table->getAttribute( "summary" ) != NULL && strcmp( $table->getAttribute( "summary" ), "table" ) == 0 )
        break;
   if ( !isset( $table ) )
   {
      print "         <h2 class=\"error\">".DATASOURCE_MS." file '".$filename."' doesn't contain a table with summary 'table'.</h2>".PHP_EOL;
      return 1;
   }

   $column2index[ "Identifier"             ] = MS_COLUMN_IDENTIFIER;
   $column2index[ ".NET Name"              ] = MS_COLUMN_DOTNETNAME;
   $column2index[ "Additional information" ] = MS_COLUMN_DESCRIPTION;

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set instances.

   $headers_seen = false;
   $count        = 0;
   $count_new    = 0;
   foreach ( $table->getElementsByTagName( "tr" ) as $row )
   {
      //----------------------------------------------------------------------
      //  Parse the table row.

      foreach ( $row->childNodes as $column )
      {
         if ( $column->nodeType == XML_ELEMENT_NODE )
         {
            if ( $headers_seen == false && strcmp( $column->tagName, "th" ) == 0 )
            {
               // Nothing to do here.
            }
            else
            if ( strcmp( $column->tagName, "td" ) == 0 )
            {
               switch ( $column2index[ $column->getAttribute( "data-th" ) ] )
               {
                  case MS_COLUMN_IDENTIFIER  : $microsoft_id  = $column->textContent; break;
                  case MS_COLUMN_DOTNETNAME  : $dotnet_name   = $column->textContent; break;
                  case MS_COLUMN_DESCRIPTION : $description   = $column->textContent; break;
               }
            }
            else
            {
               print "         <p class=\"error\">".DATASOURCE_MS." content '".$column->tagName."' cannot be processed.</p>".PHP_EOL;
               return 2;
            }
         }
      } // endfor Columns

      if ( $headers_seen == false )
      {
         if ( DEBUG_MS ) print "<p>Headerrow:".PHP_EOL;
         if ( DEBUG_MS ) var_dump( $row );
         if ( DEBUG_MS ) print "</p>".PHP_EOL;

         $headers_seen = true;
         continue;
      }
      else
      {
         if ( DEBUG_MS ) print "<p>Data:".PHP_EOL;
         if ( DEBUG_MS ) var_dump( $microsoft_id, $dotnet_name, $description );
         if ( DEBUG_MS ) print "</p>".PHP_EOL;
      }

      //-------------------------------------------------------------------
      //  Create the new character set.

      $msid = intval( $microsoft_id );
      if ( $msid == 50222 )
      {
         // The .NET name isn't unique to ID 50220. Both have iso-2022-jp as character set name.
         $dotnet_name = "ISO-2022-JP-2"; // TODO Find the right replacement. Actually, don't know better.
      }
      if ( strlen( $dotnet_name ) == 0 )
         // The microsoft_id contains a leading zero for id 37. Use this here, not the integer value.
         $charset = SelectOrInsertCharset( DATASOURCE_MS, "CP".$microsoft_id, $count_new );
      else
         $charset = SelectOrInsertCharset( DATASOURCE_MS, $dotnet_name, $count_new );
      $count++;
      $charset->SetMSDescription( str_replace( ';', ',', $description ) ); // Remove semicolon for CSV

      //-------------------------------------------------------------------------
      //  Add the aliases and their references.

      if ( strlen( $dotnet_name ) != 0 )
         SelectOrInsertAlias( DATASOURCE_MS, $dotnet_name, $charset, $reflist, false );
      $charset->SetMSIDAll( $dotnet_name, $msid );
      // The microsoft_id contains a leading zero for id 37. Use this here, not the integer value.
      SelectOrInsertAlias( DATASOURCE_MS, "cp".$microsoft_id, $charset, $reflist, false );
      $charset->SetMSIDAll( "CP".$microsoft_id, $msid );
      $charset->SetMSIDAll( "windows-".$microsoft_id, $msid );

   } // endfor Rows

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> ".DATASOURCE_MS." character sets read in, <b>".$count_new."</b> new.</p>\n";

   return 0;

} // end ReadMicrosoft


//============================================================================
//
//                  C r e a t e O r R e n a m e C h a r a c t e r S e t s
//
/// @brief          Rename some character sets to something preferred. This
///                 is done have a name in the data source of meta information.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2017-01-03 Wagner First release
///
//============================================================================

function CreateOrRenameCharacterSets( )
{
   global $MetaSet;
   global $CharacterSetSet;

   //-------------------------------------------------------------------------
   //  Create empty reference list.

   $reflist = RefnamesToArray( DATASOURCE_SELF, "" );

   //-------------------------------------------------------------------------
   //  Walk through the meta informations and rename the character sets to a
   //  preferred name. Create our own character sets.

   $count_new     = 0;
   $count_renamed = 0;
   foreach ( $MetaSet as $meta )
   {
      if ( $meta->DataSource == DATASOURCE_SELF )
      {
         $charset = SelectOrInsertCharset( DATASOURCE_SELF, $meta->Name, $count_new );
         SelectOrInsertAlias( DATASOURCE_SELF, $meta->Name, $charset, $reflist, true );
      }
      else
      if ( $meta->DataSource == "Name" )
      {
         $charset = $CharacterSetSet[ $meta->Identifier ];
         $charset->SetMeta( $meta );
         $meta->SetUsed();
         SelectOrInsertAlias( DATASOURCE_SELF, $meta->Name, $charset, $reflist, true );
         $count_renamed++;
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count_new."</b> character sets created, <b>".$count_renamed."</b> renamed.</p>\n";

   return 0;

} // end CreateOrRenameCharacterSets


//============================================================================
//
//                  R e a d A l i a s e s
//
/// @brief          Reads the Alias.csv to manually add aliases to existent
///                 character sets.
///
/// @param          $filename           URL to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2017-01-09 Wagner   First release
///
//============================================================================

function ReadAliases( $filename )
{
   global $CharacterSetSet;

   //-------------------------------------------------------------------------
   //  Open the input file.

   if ( ($file = fopen( $filename, "r")) == NULL )
   {
      print "         <h2 class=\"error\">Alias file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Read the lines and add the aliases.

   $count = 0;
   while ( ($field = fgetcsv( $file, 0, ";" )) != NULL )
   {
      if ( $field[0][0] != '#' ) // Skip comment.
      {
         $count++;
         $charset = @$CharacterSetSet[ $field[0] ];
         if ( !isset( $charset ) )
            print "         <p class=\"error\">Character set ".DATASOURCE_SELF."/".$field[0]." not found.</p>".PHP_EOL;
         else
         {
            $reflist = RefnamesToArray( DATASOURCE_SELF, $field[2] );
            SelectOrInsertAlias( DATASOURCE_SELF, $field[1], $charset, $reflist, false );
         }
      }
   }

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count."</b> ".DATASOURCE_SELF." aliases read in.</p>\n";

   return 0;

} // end ReadAliases


//============================================================================
//
//                  C r e a t e A l i a s S e t
//
/// @brief          Creates the set of aliases over all character sets and
///                 checks for uniqueness of them at the same time.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner First release
///
//============================================================================

function CreateAliasSet( )
{
   global $CharacterSetSet;
   global $AliasSet;

   //-------------------------------------------------------------------------
   //  Walk through the aliases of all character sets and build the alias set.

   $count = 0;
   $count_dup = 0;
   foreach ( $CharacterSetSet as $charset )
      foreach ( $charset->Alias as $alias )
      {
         $count++;
         $entry = @$AliasSet[ $alias->Original ];
         if ( isset( $entry ) )
         {
            if ( array_search( $charset, $entry, true ) === false )
               $entry[] = $charset;
            if ( count( $entry ) > 1 )
            {
               print "         <p class=\"error\">Alias '".$alias->DataSource."/".$alias->Original.
                     "' exists in character sets '".$entry[0]->DataSource."/".$entry[0]->Name->Original.
                     "' and '".$charset->DataSource."/".$charset->Name->Original."'.</p>\n";
               $count_dup++;
            }
         }
         else
            $AliasSet[ $alias->Original ] = array( $alias->CharacterSet );
      }

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> aliases added to set, <b>".$count_dup."</b> duplicate.</p>\n";

   return 0;

} // end CreateAliasSet


//============================================================================
//
//                  R e a d I B M C P
//
/// @brief          Reads the IBM code page list web page and assigns URLs to
///                 the IBM alias.
///
/// @param          $filename           URL to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner   First release
///
//============================================================================

function ReadIBMCP( $filename )
{
   global $AliasSet;

   //-------------------------------------------------------------------------
   //  Open the input file.
   //  Unfortunately, the builtin XML parser isn't able to handle HTML5 tags
   //  such as header, nav, main, or footer. So, suppress warnings.

   $document = new DOMDocument();
   $document->preserveWhiteSpace = false;
   if ( @$document->loadHTMLFile( $filename ) == false )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCP." code page file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create the reference list.

   $reflist = RefnamesToArray( DATASOURCE_IBMCP, "IBMCP" );

   //-------------------------------------------------------------------------
   //  Get some link information.

   $links = $document->getElementsByTagName( "link" );
   foreach ( $links as $link )
     if ( $link->getAttribute( "rel" ) != NULL && strcmp( $link->getAttribute( "rel" ), "canonical" ) == 0 )
        break;
   if ( !isset( $link ) )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCP." code page file '".$filename."' doesn't contain a link with relation 'canonical'.</h2>".PHP_EOL;
      return 1;
   }
   $base_url = substr( $link->getAttribute( "href" ), 0, strrpos( $link->getAttribute( "href" ), '/' ) + 1 );

   //-------------------------------------------------------------------------
   //  Get some table header information.

   $tbodies = $document->getElementsByTagName( "tbody" );
   foreach ( $tbodies as $tbody )
     if ( $tbody->parentNode->getAttribute( "class" ) != NULL && strcmp( $tbody->parentNode->getAttribute( "class" ), "ibm-data-table" ) == 0 )
        break;
   if ( !isset( $tbody ) )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCP." code page file '".$filename."' doesn't contain a table with class 'ibm-data-table'.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Prepare the alias set in a new set indexed by number.

   foreach( $AliasSet as $alias => $charsetlist )
      foreach ( $charsetlist as $charset )
         foreach ( $charset->Alias as $alias )
         {
            $numericpart = "";
            if ( strncmp( $alias->Name, "CP", 2 ) == 0 )
               $numericpart = substr( $alias->Name, 2 );
            else
            if ( strncmp( $alias->Name, "IBM-", 4 ) == 0 )
               $numericpart = substr( $alias->Name, 4 );
            else
            if ( strncmp( $alias->Name, "IBM", 3 ) == 0 )
               $numericpart = substr( $alias->Name, 3 );
            else
            if ( strncmp( $alias->Name, "X-IBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
            if ( strncmp( $alias->Name, "XIBM", 4 ) == 0 )
               $numericpart = substr( $alias->Name, 4 );
            else
            if ( strncmp( $alias->Name, "cpIBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
            if ( strncmp( $alias->Name, "csIBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
               $numericpart = $alias->Name;

            $numeric = intval( $numericpart );
            if ( $numeric != 0 )
               $idaliasset[ $numeric ][] = $alias;
         }

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set instances.

   $count        = 0;
   $count_unused = 0;
   foreach ( $tbody->getElementsByTagName( "tr" ) as $row )
   {
      //----------------------------------------------------------------------
      //  Parse the table row.

      foreach ( $row->childNodes as $column )
      {
         if ( $column->nodeType == XML_ELEMENT_NODE )
         {
            if ( strcmp( $column->tagName, "th" ) == 0 )
            {
               $count++;
               // Column 1
               $anchor = $column->getElementsByTagName( "a" )[0];
               $url = $anchor->getAttribute( "href" );
               $id  = intval( $anchor->textContent );
            }
            else
            if ( strcmp( $column->tagName, "td" ) == 0 )
            {
               // Column 2
               $description = $column->textContent;
            }
            else
            {
               print "         <p class=\"error\">".DATASOURCE_IBMCP." code page content '".$column->tagName."' cannot be processed.</p>".PHP_EOL;
               return 2;
            }
         }
      } // endfor Columns

      if ( DEBUG_IBMCP ) print "<p>IBM CP data:".PHP_EOL;
      if ( DEBUG_IBMCP ) var_dump( $id, $url, $description );
      if ( DEBUG_IBMCP ) print "</p>".PHP_EOL;

      //-------------------------------------------------------------------------
      // Add the IBM code page information. The identifiers always have 5 digits.
      // Note that the CP<identifier> aliases will be updated only if the exists
      // a corresponding IBM<identifier> alias.

      $ibmurl      = $base_url.$url;
      $description = htmlspecialchars( $description );
      $aliases     = @$idaliasset[ $id ];
      if ( isset( $aliases ) )
         foreach ( $aliases as $alias )
         {
            $alias->SetIBMCP( $id, $ibmurl );
            $alias->SetRelation( DATASOURCE_IBMCP, $reflist[0] );
            $alias->CharacterSet->SetIBMCPDescription( $id, $description );
         }
      else
      {
         print "         <p class=\"warning\">".DATASOURCE_IBMCP."/".$id." is unused.</p>".PHP_EOL;
         $count_unused++;
      }

   } // endfor Rows

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> ".DATASOURCE_IBMCP." code pages read in, <b>".$count_unused."</b> unused.</p>\n";

   return 0;

} // end ReadIBMCP


//============================================================================
//
//                  R e a d I B M C C S
//
/// @brief          Reads the IBM coded character set list web page and
///                 assigns URLs to the IBM alias.
///
/// @param          $filename           URL to read from
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner   First release
///
//============================================================================

function ReadIBMCCS( $filename )
{
   global $AliasSet;

   //-------------------------------------------------------------------------
   //  Open the input file.
   //  Unfortunately, the builtin XML parser isn't able to handle HTML5 tags
   //  such as header, nav, main, or footer. So, suppress warnings.

   $document = new DOMDocument();
   $document->preserveWhiteSpace = false;
   if ( @$document->loadHTMLFile( $filename ) == false )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCCS." coded character set file '".$filename."' could not be opened for reading.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Create the reference list.

   $reflist = RefnamesToArray( DATASOURCE_IBMCCS, "IBMCCS" );

   //-------------------------------------------------------------------------
   //  Get some link information.

   $links = $document->getElementsByTagName( "link" );
   foreach ( $links as $link )
     if ( $link->getAttribute( "rel" ) != NULL && strcmp( $link->getAttribute( "rel" ), "canonical" ) == 0 )
        break;
   if ( !isset( $link ) )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCCS." coded character set file '".$filename."' doesn't contain a link with relation 'canonical'.</h2>".PHP_EOL;
      return 1;
   }
   $base_url = substr( $link->getAttribute( "href" ), 0, strrpos( $link->getAttribute( "href" ), '/' ) + 1 );

   //-------------------------------------------------------------------------
   //  Get some table header information.

   $tables = $document->getElementsByTagName( "table" );
   foreach ( $tables as $table )
     if ( $table->getAttribute( "class" ) != NULL && strcmp( $table->getAttribute( "class" ), "ibm-data-table" ) == 0 )
        break;
   if ( !isset( $table ) )
   {
      print "         <h2 class=\"error\">".DATASOURCE_IBMCCS." coded character set file '".$filename."' doesn't contain a table with class 'ibm-data-table'.</h2>".PHP_EOL;
      return 1;
   }

   //-------------------------------------------------------------------------
   //  Prepare the alias set in a new set indexed by number.

   foreach( $AliasSet as $alias => $charsetlist )
      foreach ( $charsetlist as $charset )
         foreach ( $charset->Alias as $alias )
         {
            $numericpart = "";
            if ( strncmp( $alias->Name, "CP", 2 ) == 0 )
               $numericpart = substr( $alias->Name, 2 );
            else
            if ( strncmp( $alias->Name, "IBM-", 4 ) == 0 )
               $numericpart = substr( $alias->Name, 4 );
            else
            if ( strncmp( $alias->Name, "IBM", 3 ) == 0 )
               $numericpart = substr( $alias->Name, 3 );
            else
            if ( strncmp( $alias->Name, "X-IBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
            if ( strncmp( $alias->Name, "XIBM", 4 ) == 0 )
               $numericpart = substr( $alias->Name, 4 );
            else
            if ( strncmp( $alias->Name, "cpIBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
            if ( strncmp( $alias->Name, "csIBM", 5 ) == 0 )
               $numericpart = substr( $alias->Name, 5 );
            else
               $numericpart = $alias->Name;

            $numeric = intval( $numericpart );
            if ( $numeric != 0 )
               $idaliasset[ $numeric ][] = $alias;
         }

   //-------------------------------------------------------------------------
   //  Read the lines and create the character set instances.

   $headers_seen = false;
   $count        = 0;
   $count_unused = 0;
   foreach ( $table->getElementsByTagName( "tr" ) as $row )
   {
      //----------------------------------------------------------------------
      //  Parse the table row.

      foreach ( $row->childNodes as $column )
      {
         if ( $headers_seen && $column->nodeType == XML_ELEMENT_NODE )
         {
            if ( strcmp( $column->tagName, "th" ) == 0 )
            {
               $count++;
               // Column 1
               $anchor = $column->getElementsByTagName( "a" )[0];
               $url = $anchor->getAttribute( "href" );
               $id  = intval( $anchor->textContent );
            }
            else
            if ( strcmp( $column->tagName, "td" ) == 0 )
            {
               // Column 2 + 3, ignoring column 2 with the hexadecimal value
               $description = $column->textContent;
            }
            else
            {
               print "         <p class=\"error\">".DATASOURCE_IBMCCS." coded character set content '".$column->tagName."' cannot be processed.</p>".PHP_EOL;
               return 2;
            }
         }
      } // endfor Columns

      if ( $headers_seen == false )
      {
         if ( DEBUG_IBMCCS ) print "<p>Headerrow:".PHP_EOL;
         if ( DEBUG_IBMCCS ) var_dump( $row );
         if ( DEBUG_IBMCCS ) print "</p>".PHP_EOL;

         $headers_seen = true;
         continue;
      }
      else
      {
         if ( DEBUG_IBMCCS ) print "<p>IBM CCS data:".PHP_EOL;
         if ( DEBUG_IBMCCS ) var_dump( $id, $url, $description );
         if ( DEBUG_IBMCCS ) print "</p>".PHP_EOL;
      }

      //-------------------------------------------------------------------------
      //  Add the IBM code page information. The identifiers always have 5 digits.
      // Note that the CP<identifier> aliases will be updated only if the exists
      // a corresponding IBM<identifier> alias.

      $ibmurl      = $base_url.$url;
      $description = htmlspecialchars( $description );
      $aliases     = @$idaliasset[ $id ];
      if ( isset( $aliases ) )
         foreach ( $aliases as $alias )
         {
            $alias->SetIBMCCS( $id, $ibmurl );
            $alias->SetRelation( DATASOURCE_IBMCCS, $reflist[0] );
            $alias->CharacterSet->SetIBMCCSDescription( $id, $description );
         }
      else
      {
         print "         <p class=\"warning\">".DATASOURCE_IBMCCS."/".$id." is unused.</p>".PHP_EOL;
         $count_unused++;
      }

   } // endfor Rows

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> ".DATASOURCE_IBMCCS." coded character sets read in, <b>".$count_unused."</b> unused.</p>\n";

   return 0;

} // end ReadIBMCCS


//============================================================================
//
//                  I n s e r t A n d C h e c k R e l a t i o n s
//
/// @brief          Adds some relation manually and check whether each alias
///                 has a relation.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner First release
///
//============================================================================

function InsertAndCheckRelations( )
{
   global $CharacterSetSet;

   //-------------------------------------------------------------------------
   //  First, add relations manually.
   //  This is done in the opposite order as the data source have been read in.

   $javaunspecref  = RefnamesToArray( DATASOURCE_JAVA,  "JAVAUNSPEC"  );
   $icuunspecref   = RefnamesToArray( DATASOURCE_ICU,   "ICUUNSPEC"   );
   $iconvunspecref = RefnamesToArray( DATASOURCE_ICONV, "ICONVUNSPEC" );
   $ianaunspecref  = RefnamesToArray( DATASOURCE_IANA,  "IANAUNSPEC"  );
   $count_add = 0;
   foreach ( $CharacterSetSet as $charset )
      foreach ( $charset->Alias as $alias )
      {
         // Be sure that there is at least one relation for this alias.
         if ( $charset->DataSourceJAVA == "yes" && count( $alias->Relation ) == 0 )
         {
            $count_add++;
            $alias->SetRelation( DATASOURCE_JAVA, $javaunspecref[0] );
            if ( ($simple = @$charset->Alias[ $alias->Simplified ]) != NULL
              && count( $simple->Relation ) == 0 )
               $simple->SetRelation( DATASOURCE_JAVA, $javaunspecref[0] );
         }
         if ( $charset->DataSourceICU == "yes" && count( $alias->Relation ) == 0 )
         {
            $count_add++;
            $alias->SetRelation( DATASOURCE_ICU, $icuunspecref[0] );
            if ( ($simple = @$charset->Alias[ $alias->Simplified ]) != NULL
              && count( $simple->Relation ) == 0 )
               $simple->SetRelation( DATASOURCE_ICU, $icuunspecref[0] );
         }
         if ( $charset->DataSourceICONV == "yes" && count( $alias->Relation ) == 0 )
         {
            $count_add++;
            $alias->SetRelation( DATASOURCE_ICONV, $iconvunspecref[0] );
            if ( ($simple = @$charset->Alias[ $alias->Simplified ]) != NULL
              && count( $simple->Relation ) == 0 )
               $simple->SetRelation( DATASOURCE_ICONV, $iconvunspecref[0] );
         }
         if ( $charset->DataSourceIANA == "yes" && count( $alias->Relation ) == 0 )
         {
            $count_add++;
            $alias->SetRelation( DATASOURCE_IANA, $ianaunspecref[0] );
            if ( ($simple = @$charset->Alias[ $alias->Simplified ]) != NULL
              && count( $simple->Relation ) == 0 )
               $simple->SetRelation( DATASOURCE_IANA, $ianaunspecref[0] );
         }
         // Microsoft has itself as a relation.
      }

   //-------------------------------------------------------------------------
   //  Second, check that each alias has relations.

   $count = 0;
   foreach ( $CharacterSetSet as $charset )
      foreach ( $charset->Alias as $alias )
         if ( count( $alias->Relation ) == 0 )
         {
            print "         <p class=\"error\">References for alias '".$charset->Name->Original."/".$alias->Original."' are unknown.</p>\n";
            $count++;
         }

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> aliases do not have references, <b>".$count_add."</b> added.</p>\n";

   return 0;

} // end InsertAndCheckRelations


//============================================================================
//
//                  C h e c k U n u s e d M e t a
//
/// @brief          Checks for unused meta information.
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-21 Wagner First release
///
//============================================================================

function CheckUnusedMeta( )
{
   global $MetaSet;

   //-------------------------------------------------------------------------
   //  Scan all meta informations and check that it is used.

   $count = 0;
   foreach ( $MetaSet as $meta )
      if ( $meta->Used == false && strcmp( $meta->DataSource, "Deprecated" ) !=  0 )
      {
         print "         <p class=\"error\">Meta information for '".$meta->DataSource."/".$meta->Name."' is unused.</p>\n";
         $count++;
      }

   //-------------------------------------------------------------------------
   //  Good bye ...

   print "         <p><b>".$count."</b> meta informations are unused.</p>\n";

   return 0;

} // end CheckUnusedMeta


//============================================================================
//
//                  W r i t e X M L
//
/// @brief          Writes all collected information on character sets into
///                 a single XML file.
///
/// @param          $filename           Name of file to write to
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

function WriteXML( $filename )
{
   global $GroupSet;
   global $ReferenceSet;

   if ( ($file = fopen( $filename, "w" )) == NULL )
   {
      print "         <h2 class=\"error\">XML file '".$filename."' could not be opened for writing.</h2>".PHP_EOL;
      return 1;
   }

   //--------------------------------------------------------------------------
   //  Emit the XML elements of character set data.

   fputs( $file, "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>".PHP_EOL );
   fputs( $file, "<CharacterSetDatabase version=\"1.0\"".PHP_EOL );
   fputs( $file, "                      xmlns:xlink=\"http://www.w3.org/1999/xlink\"".PHP_EOL );
   fputs( $file, "                      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"".PHP_EOL );
   fputs( $file, "                      xsi:schemaLocation=\"CharacterSetDatabase.xsd\">".PHP_EOL );

   $count_group     = 0;
   $count_cs        = 0;
   $count_checked   = 0;
   $count_unsave    = 0;
   $count_unchecked = 0;
   foreach ( $GroupSet as $group )
   {
      $count_group++;
      $group->WriteXML( $file, $count_cs, $count_checked, $count_unsave, $count_unchecked );
   }

   $count_ref = 0;
   foreach ( $ReferenceSet as $reference )
   {
      $count_ref++;
      $reference->WriteXML( $file );
   }

   fputs( $file, "</CharacterSetDatabase>".PHP_EOL );

   //-------------------------------------------------------------------------
   //  Good bye ...

   fclose( $file );

   print "         <p><b>".$count_cs."</b> character sets in <b>".$count_group."</b> groups and <b>".$count_ref."</b> references written.</p>\n";
   print "         <p><b>".$count_checked."</b> checked, <b>".$count_unsave."</b> unsave and <b>".$count_unchecked."</b> unchecked character sets written.</p>\n";

   return 0;
} // end WriteXML


//============================================================================
//
//                  m a i n
//
/// @brief          Main function of the encoding collector
///
/// @author         Matthias Wagner
/// @version        1.0
/// @since          1.0
/// @date           2016-12-11 Wagner First release
///
//============================================================================

$starttime = microtime( true ); // Get as float.

$rc = 0; ///< Return code

print "      <h1>Report of ".$argv[0]."</h1>".PHP_EOL;

if ( $rc == 0 )
{
   $rc = ReadParameters( $argc, $argv );
}

if ( $rc == 0 )
{
   $filename = "Meta.csv";
   print "      <h1>Reading meta information from ".$filename."</h1>".PHP_EOL;
   $rc = ReadMeta( $filename );
}

if ( $rc == 0 )
{
   $filename = "Exclusion.csv";
   print "      <h1>Reading exclusions from ".$filename."</h1>".PHP_EOL;
   $rc = ReadExclusion( $filename );
}

if ( $rc == 0 )
{
   $filename = "Reference.csv";
   print "      <h1>Reading references from ".$filename."</h1>".PHP_EOL;
   $rc = ReadReference( $filename );
}

if ( $rc == 0 )
{
   $filename = "Relation.csv";
   print "      <h1>Reading relations from ".$filename."</h1>".PHP_EOL;
   $rc = ReadRelation( $filename );
}

if ( $rc == 0 )
{
   $filename = $iana_character_sets;
   print "      <h1>Reading IANA character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadIANA( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings.def";
   print "      <h1>Reading ICONV standard character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings_aix.def";
   print "      <h1>Reading ICONV AIX character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings_dos.def";
   print "      <h1>Reading ICONV DOS character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings_extra.def";
   print "      <h1>Reading ICONV extra character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings_local.def";
   print "      <h1>Reading ICONV local character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $libiconv_path."encodings_osf1.def";
   print "      <h1>Reading ICONV OSF/1 character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICONV( $filename );
}

if ( $rc == 0 )
{
   $filename = $icu_url;
   print "      <h1>Reading ICU character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadICU( $filename );
}

if ( $rc == 0 )
{
   $filename = "JavaCharsetList.xml";
   print "      <h1>Creating JAVA character sets into ".$filename."</h1>".PHP_EOL;
   $rc = CreateJAVA( $filename );
}

if ( $rc == 0 )
{
   print "      <h1>Reading JAVA character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadJAVA( $filename );
}

if ( $rc == 0 )
{
   $filename = $microsoft_url;
   print "      <h1>Reading MICROSOFT code pages from ".$filename."</h1>".PHP_EOL;
   $rc = ReadMicrosoft( $filename );
}

if ( $rc == 0 )
{
   print "      <h1>Creating or renaming character sets</h1>".PHP_EOL;
   $rc = CreateOrRenameCharacterSets();
}

if ( $rc == 0 )
{
   $filename = "Alias.csv";
   print "      <h1>Reading SELF aliases from ".$filename."</h1>".PHP_EOL;
   $rc = ReadAliases( $filename );
}

if ( $rc == 0 )
{
   print "      <h1>Checking for uniqueness of aliases</h1>".PHP_EOL;
   $rc = CreateAliasSet();
}

if ( $rc == 0 )
{
   $filename = $ibmcp_url;
   print "      <h1>Reading IBM code pages from ".$filename."</h1>".PHP_EOL;
   $rc = ReadIBMCP( $filename );
}

if ( $rc == 0 )
{
   $filename = $ibmccs_url;
   print "      <h1>Reading IBM coded character sets from ".$filename."</h1>".PHP_EOL;
   $rc = ReadIBMCCS( $filename );
}

if ( $rc == 0 )
{
   print "      <h1>Checking for references</h1>".PHP_EOL;
   $rc = InsertAndCheckRelations();
}

if ( $rc == 0 )
{
   print "      <h1>Checking for unused meta information</h1>".PHP_EOL;
   $rc = CheckUnusedMeta();
}

if ( $rc == 0 )
{
   $filename = "CharacterSetDatabase.xml";
   print "      <h1>Creating XML database in ".$filename."</h1>".PHP_EOL;
   $rc = WriteXML( $filename );
}

print "      <h1>Statistics</h1>".PHP_EOL;
print "         <p>Maximum memory used is <b>".round( (memory_get_peak_usage( true ) + 1024*1024 - 1)/(1024*1024), 2 )."</b> MB.</p>".PHP_EOL;
print "         <p>Time consumed is <b>".round( microtime( true ) - $starttime, 2 )."</b> seconds.</p>".PHP_EOL;

return $rc;
?>
   </body>
</html>
