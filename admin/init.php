<?php

include 'connect.php';

//Routes:

$tpl  = 'includes/templates/';  //Templates directory
$lang = 'includes/languages/';  //Languages directory
$func = 'includes/functions/';  //Functinos directory
$css  = 'layout/css/';          //Css directory
$js   = 'layout/js/';           //JS directory



//Include the important files:

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';

//Include the navbar on all pages except the one with $noNavbar variable:
if(!isset($noNavbar)){ include $tpl . 'navbar.php'; }

