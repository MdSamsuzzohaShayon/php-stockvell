<?php 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require get_language();

function get_language(){
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    $_SESSION["lang"] = $_SESSION["lang"] ?? "en"; // Set english as default
    $_SESSION["lang"] = $_GET["lang"] ?? $_SESSION["lang"]; // set language according to query params
    // if(empty($__SESSION["lang"])) $__SESSION["lang"] = "en";
    // return "/languages/" . $_SESSION['lang'] . ".php";
    // We can set it on database for more permanent version of it
    return $ROOT . "/languages/" . $_SESSION['lang'] . ".php";
}


function __($str){
    global $lang;
    if(!empty($lang[$str])){
        return $lang[$str];
    }
    return $str;
}
?>