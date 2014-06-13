<?php
//Credit http://asif18.com/17/jquery/live-username-availability-checking-using-ajax-jquery-php-and-password-strength-indicator/ for this!
include 'core/init.php'; // include the library for database connection
if(isset($_POST['action']) && $_POST['action'] == 'username_availability'){ // Check for the username posted
	$username 		= htmlentities($_POST['username']); // Get the username values
	$check_query	= mysql_query('SELECT username FROM users WHERE username = "'.$username.'" '); // Check the database
	echo mysql_num_rows($check_query); // echo the num or rows 0 or greater than 0
}
?>
