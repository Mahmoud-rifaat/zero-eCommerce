<?php

session_start(); // Start session.
session_unset(); //Unset data.
session_destroy(); //Destroys the session.

header('Location: index.php');
exit();
