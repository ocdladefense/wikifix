

# Wikifix
A PHP program to touch up wikitext files.


## Overview
This program takes in wikitext from a file and outputs a "fixed" version of the wikitext for use in other downstream applications.  For example, it replaces the <code>&</code> character (which denotes an XML entity reference) to <code>&amp;amp;</code>.  Wikifix makes use of text replacement tools and document tools (i.e., PHP's <code>preg_replace</code> and <code>DomDocument</code> function and class) to perform these manipulations.


## Installation
* This repository includes the <code>wikifix.phar</code> binary.  You can clone this repository, which includes the most recent version of the Wikifix binary in <code>phar</code> archive format.

### Or you can download the Wikifix binary using <code>curl</code>
```bash
curl -O https://www.ocdla.org/wp-content/uploads/applications/wikifix.phar
```

### Then make the binary executable
```bash
chmod u+x wikifix.phar
```

### Then <code>alias</code> the command in your <code>.bashrc</code> or <code>.zshrc</code> files.
```bash
alias wikifix="~/wikifix"
```


## Usage
<code>wikifix _infile_ [_alternate-label_]</code>
* _infile_ - The path to a file to read wikitext from.
* _alternate-label_ - A "string in double quotes" to be used to label all visible updated sections.

### Downstream applications (i.e., pandoc) can then do:
```bash
pandoc -f mediawiki -t docx fsm-chapter-1-FIXED.wiki -o fsm-chapter-1.docx
```

## Details
_Details on transformations and internal workings can be summarized here._

## Distribution
The included <code>compress.php</code> file can be used to create the phar archive.
<code>php -dphar.readonly=0 compress.php</code>

## Additional documentation
* [PHP libxml constants](https://www.php.net/manual/en/libxml.constants.php)
* [PHP DomDocument](https://www.php.net/manual/en/class.domdocument.php)