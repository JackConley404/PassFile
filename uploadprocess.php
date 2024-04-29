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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_FILES['fileToUpload']['tmp_name'];
    $password = $_POST['password'];
    
    // Check if file and password are provided
    if (is_uploaded_file($file) && !empty($password)) {
        // Read file contents
        $data = file_get_contents($file);

        // Generate a random IV for AES-256-CBC encryption
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivLength);

        // Encrypt the data using OpenSSL
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $password, 0, '$iv');

        // SQL to insert encrypted file
        $sql = "INSERT INTO globalfiles (file_name, file_contents) VALUES (?, ?)";
        $statement = $db_connection->prepare($sql);
        $file_name = $_FILES['fileToUpload']['name'];
        $statement->bind_param("ss", $file_name, $encryptedData);

        if ($statement->execute()) {
            echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded and encrypted.";
        } else {
            echo "Error: " . $sql . "<br>" . $db_connection->error;
        }

        $statement->close();
        $db_connection->close();
    } else {
        echo "Please select a file and password.";
    }
}
?>