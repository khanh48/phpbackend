<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
$host = "localhost";
$user = "root";
$pass = "";
$db = "forum";
$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die("Lỗi kết nối với cơ sở dữ liệu.");
}
$my_id = isset($_SESSION["userID"]) ? $_SESSION["userID"] : null;