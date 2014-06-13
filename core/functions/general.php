<?php
function email($to, $subject, $body) {
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
		// Additional headers
		$headers .= 'From: mail@domain.com' ."\r\n";
		$headers .= 'Reply-To: mail@domain.com' ."\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $body, $headers);
}


function logged_in_redirect() {
	//prevent user for accessing page if logged - i.e. register page if already registered. 
	if(logged_in() === true) {
		header('Location: index.php');
		exit();
	}
}

function protect_page() {
	//protect pages - redirect visitor from protected areas if not logged in. 
	if (logged_in() === false) {
		header('Location: redirect.php');
		exit();
	}
}

function array_sanitize(&$item) {
	$item = mysql_real_escape_string($item);
}

function sanitize($data) {
	//sanitize form data
	return mysql_real_escape_string($data);
}

function output_errors($errors) {
	//place each error in it's own li tag within the ul.
	$output = array();
	foreach($errors as $error) {
		$output[] = '<li>'. $error . '</li>';
	}
	return '<ul>' . implode('', $output) .  '</ul>';
}

?>
