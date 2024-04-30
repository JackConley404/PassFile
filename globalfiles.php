<?php
session_start();
if (!isset($_SESSION['loggedin'])) 
{
    header('Location: index.html');
    exit;
}

$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'passfile_db';

$db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (mysqli_connect_errno()) 
{
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$result = $db_connection->query("SELECT file_name FROM globalfiles");
if (!$result) 
{
    die('MySQL query failed with error: ' . mysqli_error($db_connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Files</title>
</head>
<body>
    <h1>Download a File</h1>
    <ul>
    <?php
    while ($row = $result->fetch_assoc()) 
    {
        echo "<li>" . htmlspecialchars($row['file_name']) . 
             " - <form action='downloadprocess.php' method='post' style='display:inline;'>
                 <input type='hidden' name='file' value='" . htmlspecialchars($row['file_name']) . "'>
                 <input type='password' name='password' placeholder='Enter password'>
                 <input type='submit' value='Download'>
                 </form>
             </li>";
    }
    
    ?>
    </ul>
</body>
</html>