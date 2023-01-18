<?php

class Like
{
    public $conn;

    function __construct(mixed $con)
    {
        $this->conn = $con;
    }

    function getTotalLikeFromPost($postID)
    {
        $total = $this->conn->query("SELECT COUNT(maluotthich) AS total FROM luotthich WHERE mabaiviet = '$postID'")->fetch_assoc()['total'];
        return $total;
    }

    function getTotalLikeFromComment($commentID)
    {
        $total = $this->conn->query("SELECT COUNT(maluotthich) AS total FROM luotthich WHERE mabinhluan = '$commentID'")->fetch_assoc()['total'];
        return $total;
    }

    function deleteLikeWithPostID($postID)
    {
        $del = $this->conn->query("DELETE FROM luotthich WHERE mabaiviet = $postID");
        return $del ? true : false;
    }

    function deleteLikeWithCommentID($cmtID)
    {
        $del = $this->conn->query("DELETE FROM luotthich WHERE mabinhluan = $cmtID");
        return $del ? true : false;
    }
    function deleteLikeWithUserID($userID)
    {
        $del = $this->conn->query("DELETE FROM luotthich WHERE taikhoan = '$userID'");
        return $del ? true : false;
    }
}