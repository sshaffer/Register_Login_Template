<?php
include('title.php');
?>
<head>
    <title>The Lab<?php if (isset($title)) echo "&#8212;{$title}"; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" href="css/screen.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
//credit http://asif18.com/17/jquery/live-username-availability-checking-using-ajax-jquery-php-and-password-strength-indicator/ 
$(document).ready(function(){
	$('#username').keyup(function(){ // Keyup function for check the user action in input
		var Username = $(this).val(); // Get the username textbox using $(this) or you can use directly $('#username')
		var UsernameAvailResult = $('#username_avail_result'); // Get the ID of the result where we gonna display the results
		if(Username.length > 2) { // check if greater than 2 (minimum 3)
			UsernameAvailResult.html('Loading..'); // Preloader, use can use loading animation here
			var UrlToPass = 'action=username_availability&username='+Username;
			$.ajax({ // Send the username val to another checker.php using Ajax in POST menthod
			type : 'POST',
			data : UrlToPass,
			url  : 'checker.php',
			success: function(responseText){ // Get the result and asign to each cases
				if(responseText == 0){
					UsernameAvailResult.html('<span class="success">Username name available</span>');
				}
				else if(responseText > 0){
					UsernameAvailResult.html('<span class="error">Username already taken</span>');
				}
				else{
					alert('Problem with query');
				}
			}
			});
		}else{
			UsernameAvailResult.html('Enter atleast 3 characters');
		}
		if(Username.length == 0) {
			UsernameAvailResult.html('');
		}
	});
	
	$('#password, #username').keydown(function(e) { // Dont allow users to enter spaces for their username and passwords
		if (e.which == 32) {
			return false;
  		}
	});
	$('#password').keyup(function() { // As same using keyup function for get user action in input
		var PasswordLength = $(this).val().length; // Get the password input using $(this)
		var PasswordStrength = $('#password_strength'); // Get the id of the password indicator display area
		
		if(PasswordLength <= 0) { // Check is less than 0
			PasswordStrength.html(''); // Empty the HTML
			PasswordStrength.removeClass('normal weak strong verystrong'); //Remove all the indicator classes
		}
		if(PasswordLength > 0 && PasswordLength < 4) { // If string length less than 4 add 'weak' class
			PasswordStrength.html('weak');
			PasswordStrength.removeClass('normal strong verystrong').addClass('weak');
		}
		if(PasswordLength > 4 && PasswordLength < 8) { // If string length greater than 4 and less than 8 add 'normal' class
			PasswordStrength.html('Normal');
			PasswordStrength.removeClass('weak strong verystrong').addClass('normal');			
		}	
		if(PasswordLength >= 8 && PasswordLength < 12) { // If string length greater than 8 and less than 12 add 'strong' class
			PasswordStrength.html('Strong');
			PasswordStrength.removeClass('weak normal verystrong').addClass('strong');
		}
		if(PasswordLength >= 12) { // If string length greater than 12 add 'verystrong' class
			PasswordStrength.html('Very Strong');
			PasswordStrength.removeClass('weak normal strong').addClass('verystrong');
		}
	});
});
</script>

</head>
