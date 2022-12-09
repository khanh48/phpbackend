<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
include_once(dirname(__DIR__) . "/object/user.php");
$host = "localhost";
$user = "root";
$pass = "";
$db = "forum";
$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Lỗi kết nối với cơ sở dữ liệu.");
}
$logged = isset($_SESSION["userID"]);
$my_id = $logged ? $_SESSION["userID"] : null;
$userObj = new User($con);
$myRank = $logged ? $userObj->getUser($my_id)['chucvu'] : "undefine";
function getTime($datetime, $full = false)
{
    $tz = new DateTimeZone("Asia/Ho_Chi_Minh");
    $now = new DateTime("now", $tz);
    $ago = new DateTime($datetime, $tz);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'ngay bây giờ';
}