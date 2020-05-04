<?php

// This page structure will be the same for almost all pages.

session_start();
$pageTitle = 'Dashboard';

if(isset($_SESSION['Username'])){

    include 'init.php';
    

    include $tpl . 'footer.php';
}else{

    header('Location: index.php');
    exit();
}