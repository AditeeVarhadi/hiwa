<?php
require 'config.phplib';
?>
<html>
<head>
<title>HIWA Recover Password</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php';

if (array_key_exists("username", $_POST)) {

	if (array_key_exists("confirmed", $_POST)) {
		print($_POST['confirmed']);
		if ($_POST['confirmed'] == "on") {
			print("<p>A password reset link has been emailed to $_POST[username].</p>");
			print("<p>If you did not receive an email in a few minutes, please check your Spam folder.</p>");
			exit();
		}
	}
	// query_params will protect against SQL injection
	$result = pg_query_params($conn, "SELECT role users
			WHERE login=$1 ",
 			array(mysql_real_escape_string($_POST['username'])));

	$conn = pg_connect('user='.$CONFIG['username'].
		' dbname='.$CONFIG['database']);
	$res = pg_prepare($conn, "result_query", $query); // using prepare to protect against sql injection
	$executed_result = pg_execute($conn, 'result_query');
	
	if (pg_num_rows($executed_result) == 1) {
		print('<P>By continuing this process, you will reset the password of <span style="font-weight:bold">'.
			$_POST['username'].'</span>.</p>');
		print("<p>To continue, check the box and hit the Submit button.</p>");
		print('<FORM method="post">');
		print('<input type="hidden" name="username" value="'.$_POST['username'].'">');
		print('<input type="checkbox" name="confirmed">');
		print('<input type="submit">');
		print('</form>');
		exit();
	} else {
		print('Invalid login!');
	}
}
?>
<p>To begin the password reset process, please enter your username in the secure field below.</p>
<form method="POST">
<input type="password" name="username" width="30" placeholder="Enter your login">
<p><input type="submit"></p>
</form>
</body>
</html>


