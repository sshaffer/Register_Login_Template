

<?php 
//Start error reporting ON

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

//End error reporting ON

include 'core/init.php';
logged_in_redirect();
include 'includes/overall/overallheader.php'; 

if (empty($_POST) === false) {
	//checks if field is empty. If in array and is empty error. 
	$required_fields = array('username', 'password', 'password_again', 'first_name', 'last_name', 'email');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true)  {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1; //if one error appears then break.
		}
	}

	if (empty($errors) === true) {
		if (user_exists($_POST['username']) === true) {
			$errors[] = 'Sorry, the username \'' . htmlentities($_POST['username']) . '\' is already taken.';
		}

		if (preg_match("/\\s/",$_POST['username']) == true) {
			$errors[] = 'Your username must not contain any spaces.';
		}
		
		if (strlen($_POST['password']) < 8) {
			$errors[] = 'Your password must be at least 8 characters.';
		}

		if ($_POST['password'] !== $_POST['password_again'] ) {
			$errors[] = 'Your passwords don\'t match.';
 		}

 		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
 			$errors[] = 'A valid e-mail address is required.';
 		}

 		if (email_exists($_POST['email']) === true) {
 		$errors[] = 'Sorry, the email \'' . htmlentities($_POST['email']) . '\' is already in use.';	
 		}

	}
}
//print_r($errors);
?>

<h1>Register</h1>

<?php
if (isset($_GET['success']) && empty($_GET['sucess'])) {
	echo 'You\'ve been registered successfully! Please check your e-mail to activate your account.';
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		//if errors array empty and form submitted register user
		$register_data = array(
	       'username' 	=> $_POST['username'],
	       'password' 	=> $_POST['password'],
	       'first_name' => $_POST['first_name'],
	       'last_name' 	=> $_POST['last_name'],
	       'email' 		=> $_POST['email'],
	       'email_code' => md5($_POST['username'] + microtime())
			);
		//print_r($register_data); for testing purposes
		register_user($register_data);
		//redirect
		header('Location: register.php?success');
		exit();
	} else if (empty($errors) === false) {
		//output errors
		echo output_errors($errors);
	}

?>

	<form action="" method="post">
		<ul>
			<li>
				Username*:<br>
				<input type="text" name="username" id="username">
			</li>
			<li><div class="username_avail_result" id="username_avail_result"></div>
			</li>
			<li>
				Password*:</br>
				<input type="password" name="password" id="password">
			</li>
			<li>
			<div class="password_strength" id="password_strength"></div>
			</li>
			<li>
				Password again*:</br>
				<input type="password" name="password_again">
			</li>
			<li>
				First Name*:<br>
				<input type="text" name="first_name">
			</li>
			<li>
				Last Name*:<br>
				<input type="text" name="last_name">
			</li>
			<li>
				E-mail*:<br>
				<input type="email" name="email">
			</li>
			<li>
				<input type="submit" value="Register">
			</li>
		</ul>
	</form>


<?php 
}
include 'includes/overall/overallfooter.php';?>
