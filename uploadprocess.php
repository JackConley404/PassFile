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

// Function to validate the password
function isValidPassword($password) 
{
    if (strlen($password) < 7) 
    {
        return false;
    }

    if (!preg_match('/[0-9]/', $password)) 
    {
        return false;
    }

    if (!preg_match('/[^\da-zA-Z]/', $password)) 
    {
        return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (!isset($_FILES['fileToUpload']['name']) || $_FILES['fileToUpload']['error'] == UPLOAD_ERR_NO_FILE) 
    {
        exit("No file was uploaded.");
    }
    
    if (!isset($_POST['password']) || !isValidPassword($_POST['password'])) 
    {
        exit("Password must be at least 7 characters long, include at least one number, and one special character.");
    }
    
    $target_dir = "C:/xampp/htdocs/passfile/globalfiles/";
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;

    // Encrypt file
    $data = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $_POST['password'], 0, $iv);

    if (file_put_contents($target_file, $encryptedData)) 
    {
        echo "The file ". htmlspecialchars($file_name). " has been uploaded.";
        $statement = $db_connection->prepare("INSERT INTO globalfiles (file_name, file_path, iv) VALUES (?, ?, ?)");
        $iv_base64 = base64_encode($iv);
        $statement->bind_param("sss", $file_name, $target_file, $iv_base64);
        $statement->execute();
        $statement->close();
    } 

    else 
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>