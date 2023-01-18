<?php
require "./includes/connect.php";
if (isset($_POST["id"]) && isset($_POST["isPost"]) && isset($_POST["userName"]) && isset($_POST["to"])) {

    $milliseconds = intval(microtime(true) * 1000);
    $id = $_POST["id"];
    $isPost = $_POST["isPost"];
    $userName = $_POST["userName"];
    $type = $isPost == 'true' ? "mabaiviet" : "mabinhluan";
    $to = $_POST["to"];
    $is_liked = false;
    if ($userName == "") {
        $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE " . $type . " = '$id'")->fetch_assoc();
        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked));
    } else {
        $mid = "";
        $msg = "";
        $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE " . $type . " = '$id' AND taikhoan = '$userName'")->fetch_assoc();
        if ($liked["liked"] > 0) {
            $con->query("DELETE FROM luotthich WHERE taikhoan = '$userName' AND " . $type . " = '$id'");
            $is_liked = false;
        } else {
            if ($userName !== $to) {
                $getUser = $con->query("SELECT * FROM nguoidung WHERE taikhoan = '$userName'")->fetch_assoc();
                $fullName = isset($getUser["hoten"]) ? $getUser["hoten"] : "";
                $msg = $fullName . " đã thích " . ($isPost == "true" ? "bài viết" : "bình luận") . " của bạn.";
                $resultID = $con->query("SELECT * FROM binhluan WHERE mabinhluan = $id")->fetch_assoc();
                $mid = isset($resultID["mabaiviet"]) ? $resultID["mabaiviet"] : "";
                if ($isPost == "true")
                    $mid = $id;
                $con->query("INSERT INTO thongbao(mathongbao, nguoigui, noidung, nguoinhan, url) VALUES($milliseconds,'$userName', '$msg', '$to', './post.php?id=$mid')");
            }

            $con->query("INSERT INTO luotthich(loai, taikhoan, " . $type . ") VALUES($isPost, '$userName', '$id')");
            $is_liked = true;
        }
        $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE " . $type . " = '$id'")->fetch_assoc();

        echo json_encode(array("count" => $liked["liked"] == 0 ? '' : $liked["liked"], "status" => $is_liked, "notify" => array("msg" => $msg, "id" => $milliseconds, "url" => "./post.php?id=" . $mid . "&r=" . $milliseconds)), JSON_UNESCAPED_UNICODE);
    }
}