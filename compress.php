<?php

$phar = new Phar('wikifix.phar');

$phar->startBuffering();
$defaultStub = $phar->createDefaultStub('wikifix.php', null);
$phar->addFile('wikifix.php');
$phar->buildFromDirectory('src');
// $phar->addFile('WikiReaderWriter.php');
$phar->setStub("#!/opt/local/bin/php74 \n" . $defaultStub);
$phar->stopBuffering();

