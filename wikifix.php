<?php

define('DO_CONVERSION',true);
define('FILE_PATH', getcwd());
define('VERSION','1.1');



require 'src/WikiDomDocument.php';
require 'src/WikiReaderWriter.php';
require 'src/Console.php';
#
#
# WIKIFIX - A SCRIPT TO REMOVE CERTAIN TAGS AND LINE BREAKS FROM A GIVEN FILE OF WIKITEXT
# VERSION 1.1 - July, 2024
# AUTHOR: Jose Bernal for OCDLA
#

// ./wikifix.php test.wiki result.wiki
$infile = $argv[1];
$outfile = $argv[1];//.'-RESULT;
$label = $argv[2];
$outfile = explode('.',$outfile)[0].'-FIXED.wiki';


Console::log("WIKIFIX VERSION " . VERSION);

// Read and transfrom the wikitext.
$parser = new WikiReaderWriter();
$parser->read($infile);
$parser->transform();
$content = $parser->getContent();

// Load the transformed content into a DOMDocument for further processing.
$doc = WikiDomDocument::fromXML($content);
$doc->transform($label);

// Save the transformed content back to a file.
$parser->setContent($doc->getHTML());
$parser->write($outfile);


__HALT_COMPILER();
exit;





