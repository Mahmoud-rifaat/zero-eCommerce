<?php

/*
========================================================
= Manage members page
= You can Add | Edit | Delete members from here.
========================================================
*/

session_start();
$pageTitle = 'Members';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


    //Start manage page.
    if($do == 'Manage'){ //Manage members page.

        //Select all users except admins.
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1");
        $stmt->execute();

        //assign the returned values to an array.
        $rows = $stmt->fetchAll();
    ?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                        foreach ($rows as $row){

                            echo '<tr>';
                                echo '<td>' . $row['UserID'] . '</td>';
                                echo '<td>' . $row['Username'] . '</td>';
                                echo '<td>' . $row['Email'] . '</td>';
                                echo '<td>' . $row['FullName'] . '</td>';
                                echo '<td>' . '</td>';
                                echo '<td>
                                            <a href="members.php?do=Edit&userid=' . $row['UserID'] .  '" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a>
                                            <a href="members.php?do=Delete&userid=' . $row['UserID'] .  '" class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>
                                        </td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a>

        </div>

    <?php
    }elseif($do == 'Add'){ //Add members page. ?>

        <h1 class="text-center">Add new member</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start username field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" autocomplete="off" required ="required" placeholder="Username to login"/>
                    </div>
                </div>
                <!-- End username field -->
                <!-- Start Password field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class=" password form-control" autocomplete="new-password" required="required" placeholder="Password shouldn't be too simple"/>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- End Password field -->
                <!-- Start Email field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" required ="required" placeholder="Email must be valid and active"/>
                    </div>
                </div>
                <!-- End Email field -->
                <!-- Start Full Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" class="form-control" required ="required" placeholder="Will not be used to login"/>
                    </div>
                </div>
                <!-- End Full Name field -->
                <!-- Start Submit field -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add member" class="btn btn-primary btn-lg"/>
                    </div>
                </div>
                <!-- End Submit field -->
            </form>
        </div>


    <?php

    }elseif($do == 'Insert'){ //Insert member page.


        //Check if the user came from a post request method(form Edit submission).
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo "<h1 class='text-center'>Update Member </h1>";
            //a containter to make things look neat.
            echo '<div class = "container">';

            //Get variables from the form
            $user   = $_POST['username'];
            $pass   = $_POST['password'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            $hashPass = sha1($_POST['password']);

            //Validate the form.
            $formErrors = array(); //An array to hold the form errors.
            if(strlen($user) < 4){

                $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>!';
            }
            if(strlen($user) > 20){

                $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>!';
            }
            if(empty($user)){

                $formErrors[] = 'Username field can\'t be <strong>empty</strong>!';
            }
            if(empty($pass)){

                $formErrors[] = 'Password field can\'t be <strong>empty</strong>!';
            }
            if(empty($name)){

                $formErrors[] = 'Full name field can\'t be <strong>empty</strong>!';
            }
            if(empty($email)){

                $formErrors[] = 'Email field can\'t be <strong>empty</strong>!';
            }
            //print form errors
            foreach($formErrors as $error){

                echo '<div class = "alert alert-danger">' . $error . '</div>';
            }

            //Check if theres any errors in the input before updating the database.
            if(empty($formErrors)){

                //Check if user already exists in database
                $check = checkItem('Username', 'users', $user);

                if($check === 1){

                    //echo 'Sorry, this user already exists';
                    //I added this:
                    $msg = '<div class="alert alert-danger">Sorry, this user already exists</div>';
                    redirect($msg, 'back');
                }else{

                    //Insert user info into the database.
                    $stmt = $con->prepare("INSERT INTO
                                        users(Username, Password, Email, FullName)
                                        VALUES(:zuser, :zpass, :zmail, :zname)");
                    $stmt->execute(array(

                        'zuser'     => $user,
                        'zpass'     => $hashPass,
                        'zmail'     => $email,
                        'zname'     => $name

                    ));

                    //Show a success message.
                    //echo '<div class = "alert alert-success">' . $stmt->rowCount() . ' Record(s) inserted.</div>';
                    $msg = '<div class = "alert alert-success">' . $stmt->rowCount() . ' Record(s) inserted.</div>';
                    redirect($msg, 'members.php');
                }

            }

        }else{

            $msg = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly</div>';

            redirect($msg, 'back', 6);
        }

        echo '</div>';

    }elseif($do == 'Edit'){  //Edit page

        //Check if Get request userid is Numeric & get the integer value of it

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        //Select all data depending on this ID
        $stmt = $con->prepare("SELECT
                                    *
                                FROM
                                    users
                                WHERE
                                    UserID = ?
                                LIMIT 1");
        //Execute the query
        $stmt->execute(array($userid));
        //Fetch the data
        $row = $stmt->fetch();
        //the rowCount to check if there is such ID
        $count = $stmt->rowCount();
        //If there is such ID, show the data in the form
        if($count > 0){ ?>

            <h1 class="text-center">Edit member</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <!-- A hidden input field to hold the id to be sent over the post request-->
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>"/>
                    <!-- Start username field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required ="required"/>
                        </div>
                    </div>
                    <!-- End username field -->
                    <!-- Start Password field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave it blank if you don't want to change"/>
                        </div>
                    </div>
                    <!-- End Password field -->
                    <!-- Start Email field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required ="required"/>
                        </div>
                    </div>
                    <!-- End Email field -->
                    <!-- Start Full Name field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required ="required"/>
                        </div>
                    </div>
                    <!-- End Full Name field -->
                    <!-- Start Submit field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End Submit field -->
                </form>
            </div>

    <?php
        //If there is no such ID show error message.
        }else{

            echo 'There is no such id';
        }

    }elseif($do == 'Update'){ //Update page

        echo "<h1 class='text-center'>Update Member </h1>";
        //a containter to make things look neat.
        echo '<div class = "container">';

            //Check if the user came from a post request method(form Edit submission).
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                //Get variables from the form
                $id     = $_POST['userid'];
                $user   = $_POST['username'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                //Password trick.
                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                //Validate the form.
                $formErrors = array(); //An array to hold the form errors.
                if(strlen($user) < 4){

                    $formErrors[] = 'Username can\'t be less than <strong>4 characters</strong>!';
                }
                if(strlen($user) > 20){

                    $formErrors[] = 'Username can\'t be more than <strong>20 characters</strong>!';
                }
                if(empty($user)){

                    $formErrors[] = 'Username field can\'t be <strong>empty</strong>!';
                }
                if(empty($name)){

                    $formErrors[] = 'Full name field can\'t be <strong>empty</strong>!';
                }
                if(empty($email)){

                    $formErrors[] = 'Email field can\'t be <strong>empty</strong>!';
                }
                //print form errors
                foreach($formErrors as $error){

                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }

                //Check if theres any errors in the input before updating the database.
                if(empty($formErrors)){

                    //Update database with this input.
                    $stmt = $con->prepare("UPDATE users SET username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    //Show a success message.
                    echo '<div class = "alert alert-success">' . $stmt->rowCount() . ' Record(s) Updated.</div>';
                }

            }else{

                echo 'Sorry, you can\'t browse this page directly';
            }

        echo '</div>';

    }elseif($do == 'Delete'){ //Delete Member Page

        echo "<h1 class='text-center'>Update Member </h1>";
        //a containter to make things look neat.
        echo '<div class = "container">';

            //Check if Get request userid is Numeric & get the integer value of it.
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            //Select all data depending on this ID
            $stmt = $con->prepare("SELECT
                                        *
                                    FROM
                                        users
                                    WHERE
                                        UserID = ?
                                    LIMIT 1");
            //Execute the query
            $stmt->execute(array($userid));
            //the rowCount to check if there is such ID
            $count = $stmt->rowCount();
            //If there is such ID show the form.
            if($stmt->rowCount() > 0){

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                $stmt->bindParam(":zuser", $userid);
                $stmt->execute();

                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
            }

        echo '</div>';

    }

    include $tpl . 'footer.php';
}else{

    header('Location: index.php');
    exit();
}
