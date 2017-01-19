import java.io.FileNotFoundException;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.util.Map;
import java.util.Set;
import java.util.SortedMap;
import java.nio.charset.Charset;

/**
   Simple class to extract the builtin character sets and aliases of Java(tm)
 */
public class JavaCharsetLister
{
   /**
    *  Main function which is the only function/method
    *
    *  @param args Command line arguments with the output filename
    */
   public static void main(String[] args)
   {
      String outencoding = "UTF-8";

      if ( args.length < 1 )
      {
         System.err.println( "Usage: JavaCharsetLister <outfilename>" );
         return;
      }

      try
      {
         PrintWriter outfile = new PrintWriter( args[0], outencoding );
         outfile.println( "<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>" );
         outfile.println( "<JavaCharsetLister>" );

         SortedMap<String,Charset> available = Charset.availableCharsets();
         for ( Map.Entry<String, Charset> entry : available.entrySet() )
         {
            System.out.println( "Processing " + entry.getValue().displayName() );
            String registered = entry.getValue().isRegistered() ? "yes" : "no";
            outfile.println( "   <Charset name=\"" + entry.getKey()
                           + "\" iana-registered=\"" + registered + "\">" );

            Set<String> aliases = entry.getValue().aliases();
            for ( String alias : aliases )
            {
               outfile.println( "      <Alias name=\"" + alias + "\"/>" );
            }

            outfile.println( "   </Charset>" );
         }

         outfile.println( "</JavaCharsetLister>" );
         outfile.close();
         System.out.println( "Processed " + available.entrySet().size() + " encodings." );
      }
      catch ( FileNotFoundException e )
      {
         System.err.println( "File \'" + args[0] + "\' not found." );
      }
      catch ( UnsupportedEncodingException e )
      {
         System.err.println( "Encoding " + outencoding + " not applicable." );
      }
   }
}
