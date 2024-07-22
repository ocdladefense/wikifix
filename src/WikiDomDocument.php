<?php

class WikiDomDocument extends DomDocument {

    private $doc = null;


    public function __construct($version = "1.0", $encoding = "UTF-8") {
        parent::__construct($version, $encoding);
    }
    // Add a filter to remove <ref> tags from the content of any deprecated section.
    // This is a temporary fix to remove the deprecated sections from the content.
    // Add update labels back into the MS Word document.
    public static function fromXML($content) {
        // libxml_use_internal_errors(true);
        $doc = new WikiDomDocument("1.0");
        $parsed = new WikiDomDocument("1.0");
        
        // Make sure we have a root element.
        $xml = "<content>" . $content . "</content>";
        // return $xml;

        $doc->loadXML($xml);//, LIBXML_PARSEHUGE | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);// | LIBXML_NOENT);
        // libxml_clear_errors();
        // $doc->normalize();

        


        $doc->saveXML();
        $body = $doc->getElementsByTagName('content')[0];

        foreach ($body->childNodes as $child) {
            $parsed->appendChild($parsed->importNode($child, true));
        }

        $parsed->normalizeDocument();

        return $parsed;
    }


    public function getHTML() {
        return $this->saveHTML();
    }

    
    function transform($altText = null) {
        $doc = $this;
        $updates = $doc->getElementsByTagName('bon-update');

        $DOM_SPACE = " ";

        // var_dump($deprecated);

        for($i = $updates->length - 1; $i >= 0; $i--){
            $node = $updates->item($i);
            $deprecated = $node->getAttribute('deprecated');
            $parent = $node->parentNode;

            $label = $doc->createElement("h2");
            $year = $node->getAttribute('year');
            $month = $node->getAttribute('month');
            $text = $altText ?? implode($DOM_SPACE, [$month, $year, "Update"]);
            $textNode = $doc->createTextNode($text);
            $label->appendChild($textNode);
            

            if($deprecated != "true") {
                $label = $parent->insertBefore($label, $node);
                continue;
            }
            
            // Operations on parent node:
            $parent->removeChild($node);
        }
    }
}