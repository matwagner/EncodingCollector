php EncodingProcessor.php CharacterSetDatabase2Db.xsl CharacterSetDatabase.xml >CharacterSetDatabase.import.xml
#java -jar D:\Saxon\saxon9he.jar -s:CharacterSetDatabase.xml -xsl:CharacterSetDatabase2Db.xsl >CharacterSetDatabase.import.xml