<?php
include("connect.php");
if (isset($_POST['username']) && isset($_POST['pass'])) {
    $uname = $_POST['username'];
    $pwd = $_POST['pass'];
    $sql = "SELECT * FROM users WHERE user_name = '$uname'";
    $row = $con->query($sql)->fetch_assoc();

    if (isset($row['user_name']) && $row['user_name'] === $uname && $row['pass'] === md5($pwd)) {
        $_SESSION['userID'] = $uname;
        echo json_encode(array("message" => "success", "username" => $uname));
    } else {
        echo json_encode(array("message" => "failed"));
    }
} else {
    echo json_encode(array("message" => "empty"));
}