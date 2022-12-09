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
        $total = $this->conn->query("SELECT COUNT(like_id) AS total FROM likes WHERE post_id = '$postID'")->fetch_assoc()['total'];
        return $total;
    }

    function getTotalLikeFromComment($commentID)
    {
        $total = $this->conn->query("SELECT COUNT(like_id) AS total FROM likes WHERE cmt_id = '$commentID'")->fetch_assoc()['total'];
        return $total;
    }

    function deleteLikeWithPostID($postID)
    {
        $del = $this->conn->query("DELETE FROM likes WHERE post_id = $postID");
        return $del ? true : false;
    }

    function deleteLikeWithCommentID($cmtID)
    {
        $del = $this->conn->query("DELETE FROM likes WHERE cmt_id = $cmtID");
        return $del ? true : false;
    }
    function deleteLikeWithUserID($userID)
    {
        $del = $this->conn->query("DELETE FROM likes WHERE user_name = '$userID'");
        return $del ? true : false;
    }
}