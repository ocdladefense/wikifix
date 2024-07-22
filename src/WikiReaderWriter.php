<?php

class WikiReaderWriter {


    private $data = null;


    public function __construct($data = null) {
        $this->data = $data;

    }





    public function transform() {

        if(!isset($this->data)){
           throw new Exception("No data to transform.");
        }

        Console::log("BEGIN TRANSFORM", $this->data);
        
	    $this->data = preg_replace("/&/mis","&amp;",$this->data);
        $this->data = $this->convertWikiText($this->data);
    }



    public function getContent() {
        return $this->data;
    }

    public function setContent($data) {
        $this->data = $data;
    }


    
    // Open a file of wikitext and read its contents.
    public function read($filename) {
        $filepath = FILE_PATH . DIRECTORY_SEPARATOR . $filename;
        Console::log("Opening source file: $filepath");

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
    public function write($filename) {
        $text = $this->data;

        $filepath = FILE_PATH . DIRECTORY_SEPARATOR . $filename;

        Console::log("Writing to target file: $filepath");

        $handle = fopen($filepath, "w");
        
        $result = fwrite($handle, $text);
        fclose($handle);

        $msg = false !== $result ? "Success!" : "ERROR writing file.";
        Console::log($msg);
    }


    
    public function convertWikiText($wikitext){
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