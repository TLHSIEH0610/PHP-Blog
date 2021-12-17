<?php 

// autoloader, resume session.


spl_autoload_register(function($class){
    // print_r("../classes/{$class}.php");
    require "classes/{$class}.php";
});

session_start();