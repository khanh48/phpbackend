<?php
require("./connect.php");
if (isset($_POST['fullName']) && isset($_POST['userName']) && isset($_POST['pwd']) && isset($_POST['repwd'])) {
    $fullName = $_POST['fullName'];
    $userName = $_POST['userName'];
    $password = $_POST['pwd'];
    $rePassword = $_POST['repwd'];
    $lenName = strlen($fullName);
    $patternUser = "/^(?=.*[A-Za-z])[A-Za-z\d]{6,13}$/";
    $patternPass = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,15}$/";

    $checkUser = $con->query("SELECT * FROM nguoidung WHERE taikhoan = '$userName'");

    if ($lenName < 5 || $lenName > 50) {
        echo json_encode(array("type" => "name_size", "message" => "Họ tên phải từ 5 đến 50 ký tự."), JSON_UNESCAPED_UNICODE);
    } elseif (!preg_match($patternUser, $userName)) {
        echo json_encode(array("type" => "user_format", "message" => "Tên đăng nhập không hợp lệ."), JSON_UNESCAPED_UNICODE);
    } elseif (!preg_match($patternPass, $password)) {
        echo json_encode(array("type" => "pass_format", "message" => "Mật khẩu phải từ 6 đến 15 ký tự, bao gồm cả chữ và số."), JSON_UNESCAPED_UNICODE);
    } elseif ($password !== $rePassword) {
        echo json_encode(array("type" => "repass_not_same", "message" => "Mật khẩu phải giống nhau."), JSON_UNESCAPED_UNICODE);
    } elseif ($checkUser->num_rows > 0) {
        echo json_encode(array("type" => "user_already_exists", "message" => "Tài khoản đã tồn tại."), JSON_UNESCAPED_UNICODE);
    } else {
        $con->query("INSERT INTO nguoidung (taikhoan, matkhau, hoten) VALUES('$userName', '" . md5($password) . "', '$fullName')");
        echo json_encode(array("type" => "success", "message" => "Tạo tài khoản thành công."), JSON_UNESCAPED_UNICODE);
    }
}