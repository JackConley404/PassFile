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

if (isset($_POST['file']) && isset($_POST['password'])) 
{
    $file = basename($_POST['file']); // Protect against directory traversal

    if ($statement = $db_connection->prepare("SELECT file_path, iv FROM globalfiles WHERE file_name = ?")) 
    {
        $statement->bind_param("s", $file);
        $statement->execute();
        $statement->store_result();

        if ($statement->num_rows > 0) {
            $statement->bind_result($file_path, $iv_base64);
            $statement->fetch();
            $statement->close();

            // Check if the file exists
            if (!file_exists($file_path)) 
            {
                echo "File does not exist. It may have been deleted.";
                exit;
            }

            // Check if the file is readable
            if (!is_readable($file_path)) 
            {
                echo "File is not readable. Check file permissions.";
                exit;
            }

            $iv = base64_decode($iv_base64);
            $encryptedData = file_get_contents($file_path);
            if ($encryptedData === false) 
            {
                exit("Failed to read the file.");
            }

            $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $_POST['password'], 0, $iv);
            if ($decryptedData === false) 
            {
                exit("Failed to decrypt the file. Check your password and ensure it is correct.");
            }

            // Prepare the file for download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . strlen($decryptedData));
            echo $decryptedData;
            exit;
        } 

        else 
        {
            echo "File not found in database.";
        }
    } 

    else 
    {
        echo "Failed to prepare the database query.";
    }
} 

else 
{
    echo "No file specified or password missing.";
}
?>