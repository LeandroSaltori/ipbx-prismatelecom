<?php
$addon=$_REQUEST['addon'];
if(!isset($_REQUEST['addon'])) {
    header("HTTP/1.1 404 NOT FOUND");
    die();
}
exec('/usr/bin/issabel-helper license check', $respuesta, $retorno);
foreach($respuesta as $linea) {
    if(preg_match("/$addon/",$linea)) {
        list($rawname,$serial,$expires) = preg_split("/\s+/",$linea);
        if($expires<>'') {
            $date       = new DateTime();
            $now_ts     = $date->format("U");
            $date       = new DateTime($expires);
            $expires_ts = $date->format("U");

            if($now_ts>$expires_ts) {
                // license expired 
                header("HTTP/1.1 410 GONE");
                die();
            } else {
                //license not expired, ok
                die();
            }
        } else {
            // No expire date, ok
            die();
        } 
    }
}
header("HTTP/1.1 404 NOT FOUND");
die();
