<?php
error_reporting(E_ALL ^ E_NOTICE);
$host = "localhost";
$user = "root";
$pass = "";
$db = "forum";
$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Lỗi kết nối với cơ sở dữ liệu.");
}