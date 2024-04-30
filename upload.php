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
        <title>PassFile - Upload</title>
    </head>
    <body>
            <center>
            <h1>PassFile - Secure File Transfer</h1>
            <h3>Upload files</h3>
            <form action="uploadprocess.php" method="post" enctype="multipart/form-data">
                Select File to Upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <br><br>
                Enter Password for File: 
                <input type="password" name="password">
                <br><br>
                <input type="submit" name="submit" value="Upload!">
            </form>
            </center>

    </body>
</html>