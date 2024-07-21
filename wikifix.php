<?php

define('DO_CONVERSION',true);
define('FILE_PATH', getcwd());
define('VERSION','1.1');




require 'src/WikiReaderWriter.php';
#
#
# WIKIFIX - A SCRIPT TO REMOVE CERTAIN TAGS AND LINE BREAKS FROM A GIVEN FILE OF WIKITEXT
# VERSION 1.1 - July, 2024
# AUTHOR: Jose Bernal for OCDLA
#

// ./wikifix.php test.wiki result.wiki
$infile = $argv[1];
$outfile = $argv[1];//.'-RESULT;
$outfile = explode('.',$outfile)[0].'-FIXED.wiki';

WikiReaderWriter::console("WIKIFIX VERSION " . VERSION);


$parser = new WikiReaderWriter();

$parser->open($infile);

$parser->transform();

$parser->save($outfile);


__HALT_COMPILER();
exit;





