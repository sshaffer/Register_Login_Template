<?php
$connect_error = 'Sorry we\'re having technical issues with our tubes.';
mysql_connect('localhost','uid','password') or die($connect_error);
mysql_select_db('lr') or die($connect_error);

?>
