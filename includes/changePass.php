<?php
require('./connect.php');

if (isset($_POST['user']) && isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['reNewPass'])) {
    $user_name = $_POST['user'];
    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];
    $reNewPass = $_POST['reNewPass'];
    $patternPass = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,15}$/";

    $user = $userObj->getUser($user_name);
    $array = array();
    if (md5($oldPass) !== $user['matkhau'])
        $array = array("type" => "failed", "message" => "Mật khẩu không chính xác.");
    elseif (!preg_match($patternPass, $newPass))
        $array = array("type" => "failed", "message" => "Mật khẩu mới không hợp lệ.");
    elseif ($newPass !== $reNewPass)
        $array = array("type" => "failed", "message" => "Mật khẩu mới phải trùng nhau.");
    elseif ($newPass === $oldPass)
        $array = array("type" => "failed", "message" => "Hãy thử một mật khẩu khác.");
    else {
        $array = array("type" => "success", "message" => "Đổi mật khẩu thành công.");
        $userObj->changePassword($user_name, md5($newPass));
    }

    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}