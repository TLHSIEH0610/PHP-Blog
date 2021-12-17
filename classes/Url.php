<?php

class Url {

    public static function redirect($path){

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocal = 'https';
        }else {
            $protocal = 'http';
        }

        header("Location: $protocal://". $_SERVER['HTTP_HOST'] . $path);

    }

}