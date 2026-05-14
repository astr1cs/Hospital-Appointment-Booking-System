<?php
// logout.php
require_once 'includes/init.php';

Auth::logout();
redirect('login.php');
?>