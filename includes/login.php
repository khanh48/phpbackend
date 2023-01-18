<?php
include("connect.php");
if (isset($_POST['username']) && isset($_POST['pass'])) {
    $uname = $_POST['username'];
    $pwd = $_POST['pass'];
    $sql = "SELECT * FROM nguoidung WHERE taikhoan = '$uname'";
    $row = $con->query($sql)->fetch_assoc();

    if (isset($row['taikhoan']) && $row['taikhoan'] === $uname && $row['matkhau'] === md5($pwd)) {
        $_SESSION['userID'] = $uname;
        echo json_encode(array("message" => "success", "username" => $uname));
    } else {
        echo json_encode(array("message" => "failed"));
    }
} else {
    echo json_encode(array("message" => "empty"));
}