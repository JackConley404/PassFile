<?php
session_start();
if (!isset($_SESSION['loggedin'])) 
{
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>'s Homepage</title>
	</head>
	<body>
			<center>
			<h1><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>'s Homepage</h1>
			<br>
			<h3>Welcome back, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</h3>
			<br>
			<form action="logout.php"><button type="submit">Logout</button></form>
			<br>
			<form action="upload.php"><button type="submit">Upload</button></form>
			<br>
			<form action="globalfiles.php"><button type="submit">Download</button></form>
			</center>

	</body>
</html>