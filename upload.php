<?php
session_start();
include('src/autoloader.php');
$controller = new model();
$conn = $controller->returnConn();
$username = $controller->secureInput($_SESSION['slapyvardis']);
$targetDir = "img/profile pictures/";
$temp = explode(".", $_FILES["profPicLoc"]["name"]);
$fileName = $username . "_picture" . '.' . end($temp);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (!empty($_FILES["profPicLoc"]["name"]))
{
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes))
    {
        if (move_uploaded_file($_FILES["profPicLoc"]["tmp_name"], $targetFilePath))
        {
            $sql = "UPDATE naudotojai SET avataro_kelias=? WHERE slapyvardis=?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql))
            {
                mysqli_stmt_bind_param($stmt, "ss", $targetFilePath, $username);
                mysqli_stmt_execute($stmt);
                echo("<script>location.href = 'settings.php?picchange=success';</script>");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
} else {
    return false;
}