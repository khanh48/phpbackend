<?php

class Comment
{
    public $likes;
    public $conn;

    function __construct(mixed $con)
    {
        include_once("likes.php");
        $this->conn = $con;
        $this->likes = new Like($con);
    }

    function getComments($postID)
    {
        $comment = $this->conn->query("SELECT * FROM binhluan WHERE mabaiviet = $postID");
        return $comment;
    }
    function getCommentFromUser($userID)
    {
        $comment = $this->conn->query("SELECT * FROM binhluan WHERE taikhoan = '$userID'");
        return $comment;
    }

    function getTotalComment($postID)
    {
        $total = $this->conn->query("SELECT COUNT(mabinhluan) AS total FROM binhluan WHERE mabaiviet = '$postID'")->fetch_assoc()['total'];
        return $total;
    }

    function deleteComments($postID)
    {
        $comments = $this->getComments($postID);
        if ($comments->num_rows > 0) {
            while ($result = $comments->fetch_assoc()) {
                $this->likes->deleteLikeWithCommentID($result['mabinhluan']);
            }
        }

        $del = $this->conn->query("DELETE FROM binhluan WHERE mabaiviet = $postID");
        return $del ? true : false;
    }

    function deleteComment($cmtID)
    {
        $this->likes->deleteLikeWithCommentID($cmtID);
        $del = $this->conn->query("DELETE FROM binhluan WHERE mabinhluan = $cmtID");

        return $del ? true : false;
    }

    function deleteCommentWithUser($userID)
    {
        $comments = $this->getCommentFromUser($userID);
        if ($comments->num_rows > 0) {
            while ($result = $comments->fetch_assoc()) {
                $this->likes->deleteLikeWithCommentID($result['mabinhluan']);
            }
        }

        $del = $this->conn->query("DELETE FROM binhluan WHERE taikhoan = '$userID'");
        return $del ? true : false;
    }
}