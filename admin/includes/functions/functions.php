<?php

/*
 * getTitle function v1.0
 *prints the page title if the page has a $pageTitle variable and prints the Default title otherwise.
*/

function getTitle(){
    
    global $pageTitle;
    
    if(isset($pageTitle)){    //chek if it exists in the current page.
        
        echo $pageTitle;
    }
    else{
        
        echo 'Default';
    }
}


 /*
  * Redirect function v2.0
  * this function accept:
  * $msg: that will be printed.
  * $url: link to redirect to.
  * $seconds: time in seconds before redirecting [default = 3s].
 */

function redirect($msg, $url = null, $seconds = 5){

    if($url === null){

        $url = 'index.php';
        $link = 'Homepage';

    }elseif($url == 'back'){

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        }else{
            $url = 'index.php';
            $link = 'Homepage';
        }
    }else{
        $link = str_replace('.php', '', $url);
        echo $url;
    }

    echo $msg;
    echo '<div class="alert alert-info">You will be redirected to the ' . $link . 'Page after ' . $seconds . ' seconds</div>';

    header("refresh:$seconds;url=$url");
    exit();
}


/*
 * Check item function v1.0
 * Function to check item in database.
 * Parameters:
 * $select => the item to be selected
 * $from => the table to query
 * $value => the value of select
 * */

function checkItem($select, $from, $value){

    global $con;

    $statment = $con->prepare('SELECT ' . $select . ' FROM ' . $from . ' WHERE ' . $select . ' = ?');
    $statment->execute(array($value));
    $count = $statment->rowCount();
    return $count;
}

