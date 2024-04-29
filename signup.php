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
		echo 'Username is taken';
	} 

	else 
	{
		if ($statement = $db_connection->prepare('INSERT INTO users (username, password) VALUES (?, ?)')) 
		{
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$statement->bind_param('ss', $_POST['username'], $password);
			$statement->execute();
			echo 'You have successfully registered! You can now login!';
		}

		else 
		{
			echo 'Could not prepare statement!';
		}
	}

	$statement->close();
}

else 
{
	echo 'Could not prepare statement!';
}

$db_connection->close();
?>

