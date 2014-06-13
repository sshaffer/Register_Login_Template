<?php
include 'core/init.php';
protect_page();
include 'includes/overall/overallheader.php'; 


if (empty($_POST) === false) {
		//checks if field is empty. If in array and is empty error. 
	$required_fields = array('first_name', 'last_name', 'email');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true)  {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1; //if one error appears then break.
		}
	}

	if (empty($errors) === true) {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid e-mail address is required.';
		} else if (email_exists($_POST['email']) === true && $user_data['email'] !== $_POST['email']) {
			$errors[] = 'Sorry, the email \'' . htmlentities($_POST['email']) . '\' is already in use.';
		}
	}
}


?>
<h1>Settings</h1>

<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
	echo 'Your details have been updated.';
} else {

	if (empty($_POST) === false && empty($errors) === true) {
		//update user details 
		$update_data = array(
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name'],
			'email' 		=> $_POST['email'],
		);	

		update_user($session_user_id, $update_data);
		header('Location: settings.php?success');
		exit();

	} else if (empty($errors) === false) {
		echo output_errors($errors);
	}

	?>

	<form action="" method="post">
			<ul>
				<li>
					First Name:*<br>
					<input type="text" name="first_name" value="<?php echo $user_data['first_name']; ?>">
				</li>
				<li>
					Last Name:*<br>
					<input type="text" name="last_name" value="<?php echo $user_data['last_name']; ?>">
				</li>
				<li>
					E-mail:*<br>
					<input type="email" name="email" value="<?php echo $user_data['email']; ?>">
				</li>
				<li>
					<input type="submit" value="Update">
				</li>
			</ul>
		</form>

	<?php
	}
include 'includes/overall/overallfooter.php';
?>

