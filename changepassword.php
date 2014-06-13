<?php 
include 'core/init.php';
protect_page();

if (empty($_POST) === false) {
	$salt = "InsertRandomSaltHashHere";
	//checks if field is empty. If in array and is empty error. 
	$required_fields = array('current_password', 'password', "password_again");
		foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true)  {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1; //if one error appears then break.
		}
	}

		if (md5($_POST['current_password'].$salt) === $user_data['password']) {
			if (trim($_POST['password']) !== trim($_POST['password_again'])) {
				$errors[] = "Your new passwords do not match.";
 			} else if (strlen($_POST['password']) < 8) {
 			$errors[] = 'Your new password must be at least 8 characters.';	
 			}
		} else {
			$errors[] = 'Your current password is incorrect';
		}
}

include 'includes/overall/overallheader.php'; 


?>

<h1>Change Password</h1>
<?php
if (isset($_GET['success']) === true && empty($_GET['sucess']) === true) {
	echo 'Your password has been updated successfully!';
	} else {

		if (isset($_GET['force']) === true && empty($_GET['force'])=== true) {
        ?>
        	<p>You must change your password to continue.</p>
        <?php
		}

		if (empty($_POST) === false && empty($errors) === true) {
			//posted the form and no errors
			change_password($session_user_id, $_POST['password']);
			header('Location: changepassword.php?success');
		} else if (empty($errors) === false) {
			//output errors
			echo output_errors($errors);
		}
	?>

	<form action="" method="post">
			<ul>
				<li>
					Current Password*<br>	
					<input type="password" name="current_password">
				</li>
					
				<li>
					New Password*<br>	
					<input type="password" name="password">
				</li>
				<li>
					 New Password Again*<br>	
					<input type="password" name="password_again">
				</li>
				<li>
					<input	type="submit" value="Change password">
				</li>
			</ul>
	</form>


<?php
}
 include 'includes/overall/overallfooter.php';?>
