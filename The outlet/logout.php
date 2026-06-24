<?php
include 'config.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to home
redirect('/index.php');
?>
