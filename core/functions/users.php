<?php

function recover($mode, $email) {
	//recover username
	$mode = sanitize($mode);
	$email = sanitize($email);

	$user_data = user_data(user_id_from_email($email), 'user_id', 'first_name','username');

	if ($mode == 'username') {
		//recover username
		email($email, 'Your username', "Hello ". $user_data['first_name'] .", \n\n Your username is: ". $user_data['username']. " \n\n -Thanks");

	} else if ($mode == 'password') {
		//recover password
		$generated_password = substr(md5(rand(999, 999999)),0, 10);
		change_password($user_data['user_id'], $generated_password);

		update_user($user_data['user_id'], array('password_recover' => '1'));

		email($email, 'Your password recovery', "Hello ". $user_data['first_name'] .", \n\n Your new password is: ". $generated_password . " \n\n After login you will be prompted to change your password \n\n -The Lab @ stephenshaffer.net");
	}

}

function update_user($user_id, $update_data) {
	//user settings update function
	$update = array();
	array_walk($update_data, 'array_sanitize');

	foreach ($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}

	mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = " . $user_id) or die(mysql_error());
}

function activate($email, $email_code) {
	$email 		= mysql_real_escape_string($email);
	$email_code = mysql_real_escape_string($email_code);

	if (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0 "), 0) == 1) {
		//query to update user active status
		mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
		return true;
	} else {
		return false;
	}

}


function change_password($user_id, $password) {
	//sanitize data and change password
	$user_id = (int)$user_id;
	$salt = "InputRandomSaltHashHere";
	$password = md5($password.$salt);

	mysql_query("UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id"); 
}


function register_user($register_data) {
	//sanitize the data - md5 hash the password
	array_walk($register_data, 'array_sanitize');
	$salt = "InputRandomSaltHashHere";
	$register_data['password'] = md5($register_data['password'].$salt);
	//break up the array 
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';    //surrounded by back ticks
	$data = '\'' . implode('\', \'', $register_data) . '\''; //surrounded by single quotes
	
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
	//print_r($register_data) for testing
	//email user their activation code
	email($register_data['email'], 'Activate your account', "Hello " . $register_data['first_name'] .", \n\nYou need to activate your account, so use the link below:\n\nhttp://www.domain.com/activate.php?email=". $register_data['email']. "&email_code=" . $register_data['email_code'] ." \n\n-Thanks");
}

function user_count() {
	//return value of total active registered users
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);
}

function user_data($user_id) {
	//return user data information
	$data = array();
	$user_id = (int)$user_id; //sanitize to prevent sql injections

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if ($func_num_args > 1) {
		unset($func_get_args[0]);
		
		$fields = '`' . implode('` , `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id"));

		//print_r($data) //see the data array
		return $data;
	}
}

function logged_in() {
	//is the user logged in?
	return (isset($_SESSION['user_id'])) ? true : false; 
}

function user_exists($username) {
	//Checking if user exists in the database after sanitizing data
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function email_exists($email) {
	//Checking if user exists in the database after sanitizing data
	$email = sanitize($email);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'");
	return (mysql_result($query, 0) == 1) ? true : false;
}


function user_active($username) {
	//checking if user is active after sanitizing data
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` = 1");
	return (mysql_result($query, 0) == 1) ? true : false;
}


function user_id_from_username($username) {
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'"), 0, 'user_id');
}

function user_id_from_email($email) {
	$email = sanitize($email);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `email` = '$email'"), 0, 'user_id');
}

function login($username, $password) {
	$user_id = user_id_from_username($username);

	$username = sanitize($username);
	$salt = "InputRandomSaltHashHere";
	$password = md5($password.$salt);

	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'"), 0) == 1) ? $user_id : false;
}	

?>
