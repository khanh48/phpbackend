<?php
require "./includes/connect.php";
if (isset($_POST["id"]) && isset($_POST["isPost"]) && isset($_POST["userName"]) && isset($_POST["to"])) {

    $milliseconds = intval(microtime(true) * 1000);
    $id = $_POST["id"];
    $isPost = $_POST["isPost"];
    $userName = $_POST["userName"];
    $type = $isPost == 'true' ? "post_id" : "cmt_id";
    $to = $_POST["to"];
    $is_liked = false;
    if ($userName == "") {
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id'")->fetch_assoc();
        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked));
    } else {
        $mid = "";
        $msg = "";
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id' AND user_name = '$userName'")->fetch_assoc();
        if ($liked["liked"] > 0) {
            $con->query("DELETE FROM likes WHERE likes.user_name = '$userName' AND " . $type . " = '$id'");
            $is_liked = false;
        } else {
            if ($userName !== $to) {
                $getUser = $con->query("SELECT * FROM users WHERE user_name = '$userName'")->fetch_assoc();
                $fullName = isset($getUser["hoten"]) ? $getUser["hoten"] : "";
                $msg = $fullName . " đã thích " . ($isPost == "true" ? "bài viết" : "bình luận") . " của bạn.";
                $resultID = $con->query("SELECT * FROM comments WHERE comment_id = $id")->fetch_assoc();
                $mid = isset($resultID["post_id"]) ? $resultID["post_id"] : "";
                if ($isPost == "true")
                    $mid = $id;
                $con->query("INSERT INTO notify(notify_id, from_user, msg, to_user, url) VALUES($milliseconds,'$userName', '$msg', '$to', './post.php?id=$mid')");
            }

            $con->query("INSERT INTO likes(is_post, user_name, " . $type . ") VALUES($isPost, '$userName', '$id')");
            $is_liked = true;
        }
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE " . $type . " = '$id'")->fetch_assoc();

        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked, "notify" => array("msg" => $msg, "id" => $milliseconds, "url" => "./post.php?id=" . $mid . "&r=" . $milliseconds)), JSON_UNESCAPED_UNICODE);
    }
}