<?php
session_start();
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'passfile_db';

$db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if ( mysqli_connect_errno() ) 
{
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if ( !isset($_POST['username'], $_POST['password']) ) 
{
	exit('Please fill both the username and password fields!');
}

if ($statement = $db_connection->prepare('SELECT id, password FROM users WHERE username = ?')) 
{
	$statement->bind_param('s', $_POST['username']);
	$statement->execute();
	$statement->store_result();

	if ($statement->num_rows > 0) 
	{
		$statement->bind_result($id, $password);
		$statement->fetch();
	
		if (password_verify($_POST['password'], $password)) 
		{
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['username'];
			$_SESSION['id'] = $id;
			header('Location: homepage.php');
		} 

		else 
		{
			echo 'Incorrect username and password combination.';
		}

	} 

	else 
	{
		echo 'Incorrect username and password combination.';
	}


	$statement->close();
}
?>