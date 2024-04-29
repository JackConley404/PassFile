<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>PassFile - Global Files</title>
    </head>
    <body>
            <center>
            <h1>PassFile - Secure File Transfer</h1>
            <h3>Global files</h3>
            <form action="downloadprocess.php" method="post" enctype="multipart/form-data">
                Select File to Download:
                <br>
                <input type="submit" name="submit" value="Download!">
            </form>
            </center>

    </body>
</html>