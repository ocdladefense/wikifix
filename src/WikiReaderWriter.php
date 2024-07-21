<?php

class WikiReaderWriter {


    private $data = null;


    public function __construct($data = null) {
        $this->data = $data;

    }


    static function console($msg, $data = null) {
        echo "wikifix: $msg\n";
        if(isset($data)) echo substr($data,0,255)."\n";
    }


    function transform() {

        if(!isset($this->data)){
           throw new Exception("No data to transform.");
        }

        self::console("BEGIN TRANSFORM", $this->data);
        
	    $this->data = preg_replace("/&/mis","&amp;",$this->data);
        $this->data = $this->convertToDomDoc($this->data);
        $this->data = $this->convertWikiText($this->data);
    }




    
    // Open a file of wikitext and read its contents.
    function open($filename) {
        $filepath = FILE_PATH . DIRECTORY_SEPARATOR . $filename;
        self::console("Opening source file: $filepath");

        $size = @filesize($filepath);

        if(false === $size){
            throw new Exception("File not found: $filepath");
        }

        $handle = fopen($filepath, "r");
        $contents = fread($handle, $size);
        fclose($handle);

        $this->data = $contents;
        return $contents;
    }


    // Write the value of $data to a file.
    function save($filename) {
        $text = $this->data;

        $filepath = FILE_PATH . DIRECTORY_SEPARATOR . $filename;

        self::console("Writing to target file: $filepath");

        $handle = fopen($filepath, "w");
        
        $result = fwrite($handle, $text);
        fclose($handle);

        if(false !== $result){
            self::console("Success!");
        } else {
            self::console("ERROR writing file.");
        }
    }






    // Add a filter to remove <ref> tags from the content of any deprecated section.
    // This is a temporary fix to remove the deprecated sections from the content.
    // Add update labels back into the MS Word document.
    function convertToDomDoc($content) {
        // libxml_use_internal_errors(true);
        $doc = new DomDocument("1.0");
        $parsed = new DomDocument("1.0");
        
        $xml = "<content>" . $content . "</content>";
        // return $xml;

        $doc->loadXML($xml);//, LIBXML_PARSEHUGE | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);// | LIBXML_NOENT);
        // libxml_clear_errors();
        // $doc->normalize();

        // return $doc->saveXML();
        $deprecated = $doc->getElementsByTagName('bon-update');

        // var_dump($deprecated);

        for($i = $deprecated->length - 1; $i >= 0; $i--){
            $attr = $deprecated->item($i)->getAttribute('deprecated');
            if($attr != "true") continue;
            $node = $deprecated->item($i);
            $node->parentNode->removeChild($node);
        }

        $deprecated = $doc->getElementsByTagName('bon-update');
        // var_dump($deprecated);



        $doc->saveHTML();
        $body = $doc->getElementsByTagName('content')[0];

        foreach ($body->childNodes as $child){
            $parsed->appendChild($parsed->importNode($child, true));
        }

        return $parsed->saveHTML();
    }


    
    function convertWikiText($wikitext){
        // Remove all </sup> tags
        $out = preg_replace("/<\/*sup>/mis","",$wikitext);

        // Remove line breaks inside of <ref> tags.
        function foobar($matches) {
            // print "<pre>".print_r($matches,true)."</pre>";
            $parts = [
                $matches[1],
                preg_replace("/\n+/mis","",$matches[2]).
                $matches[3]
            ];

            return implode("",$parts);
        }

        if(!DO_CONVERSION){
            echo "\n\nWARNING: LINE BREAKS HAVE NOT BEEN CONVERTED:\n\n<br /><br />'sup' tags have been removed.<br /><br />\n\n";
        }
        if(DO_CONVERSION){
            $out = preg_replace_callback("/(<ref.*?>)(.*?)(<\/ref>)/mis","foobar",$out);
        }



        return $out;
    }


}