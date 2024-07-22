<?php

class Console {


    public static function log($msg, $data = null) {
        echo "wikifix: $msg\n";
        if(isset($data)) echo substr($data,0,255)."\n";
    }
}