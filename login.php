<?php
include 'core/init.php';
logged_in_redirect();
if (empty($_POST) === false) {
	//error checking login credentials
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) === true || empty($password) === true ) {
		$errors[] = 'You need to enter a username and password.';
	} else if (user_exists($username) === false ) {
		$errors[] = 'User doesn\'t exist. Have you registered?';
	} else if (user_active($username) === false ) {
		$errors[] = 'You haven\'t activeated your account.';
	}  else {

		if (strlen($password) > 32) {
			$errors[] = 'Password too long';
		}

		$login = login($username, $password);
		if ($login === false) {
			$errors[] = 'That username and password combo is incorrect';
		} else {
			//If login okay set session
			$_SESSION['user_id'] = $login;
			header('Location: index.php');
			exit();
		}
	}

} else {
	//if someone lands on login.php with no data
	$errors[] = 'No data received.'; 
}
include 'includes/overall/overallheader.php';

if (empty($errors) === false) {
?>
	<h2>Login Failed...</h2>	
<?php 
	echo output_errors($errors);

}


include 'includes/overall/overallfooter.php';

?>