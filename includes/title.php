<?php
$title = basename($_SERVER['SCRIPT_FILENAME'], '.php'); //Grab filename and remove .php
$title = str_replace('_', ' ', $title); //Looks for underscore and replaces with space
if ($title == 'index') {
	$title = 'home';
}

if ($title == 'redirect') {
	$title = 'please login';
}
$title = ucwords($title);
