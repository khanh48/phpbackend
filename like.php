<?php
require "./includes/connect.php";
if (isset($_POST["id"]) && isset($_POST["isPost"]) && isset($_POST["userName"])) {
    $id = $_POST["id"];
    $isPost = $_POST["isPost"];
    $userName = $_POST["userName"];
    $type = $isPost == 'true' ? "post_id" : "cmt_id";
    $is_liked = false;
    if ($userName == "") {
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id'")->fetch_assoc();
        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked));
    } else {
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id' AND user_name = '$userName'")->fetch_assoc();
        if ($liked["liked"] > 0) {
            $con->query("DELETE FROM likes WHERE likes.user_name = '$userName' AND " . $type . " = '$id'");
            $is_liked = false;
        } else {
            $con->query("INSERT INTO likes(is_post, user_name, " . $type . ") VALUES($isPost, '$userName', '$id')");
            $is_liked = true;
        }
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id'")->fetch_assoc();

        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked));
    }
}