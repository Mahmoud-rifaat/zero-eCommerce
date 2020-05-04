<?php 
    session_start();
    $noNavbar = ''; //to prevent including the navbar on this page.
    $pageTitle = 'Login'; //to be passed to getTitle() function.
    
    if(isset($_SESSION['Username'])){
        
        header('Location: dashboard.php');   //Redirect to dashboard page.
    }
    
    
    
    include 'init.php';
    
    //Check if user coming from HTTP post request.
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);
        
        //Check if the user exists in database:
        
        $stmt = $con->prepare("SELECT 
                                    UserID, Username, Password 
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? 
                                AND 
                                    GroupID = 1 
                                LIMIT 1");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //If count > 0, this means the database contains that record.
        
        if($count > 0){
            
            $_SESSION['Username'] = $username;   //Register session name.
            $_SESSION['ID'] = $row['UserID'];    //Register session ID.
            header('Location: dashboard.php');   //Redirect to dashboard page.
            exit();
        }
        
    }
?>


<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off"/>
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="off"/>
    <input class="btn btn-primary btn-block" type="submit" value="login"/>
    
    
</form>

    


<?php
    include $tpl . "footer.php"; 
?>
