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
        $comment = $this->conn->query("SELECT * FROM comments WHERE post_id = $postID");
        return $comment;
    }
    function getCommentFromUser($userID)
    {
        $comment = $this->conn->query("SELECT * FROM comments WHERE user_name = '$userID'");
        return $comment;
    }

    function getTotalComment($postID)
    {
        $total = $this->conn->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$postID'")->fetch_assoc()['total'];
        return $total;
    }

    function deleteComments($postID)
    {
        $comments = $this->getComments($postID);
        if ($comments->num_rows > 0) {
            while ($result = $comments->fetch_assoc()) {
                $this->likes->deleteLikeWithCommentID($result['comment_id']);
            }
        }

        $del = $this->conn->query("DELETE FROM comments WHERE post_id = $postID");
        return $del ? true : false;
    }

    function deleteComment($cmtID)
    {
        $this->likes->deleteLikeWithCommentID($cmtID);
        $del = $this->conn->query("DELETE FROM comments WHERE comment_id = $cmtID");

        return $del ? true : false;
    }

    function deleteCommentWithUser($userID)
    {
        $comments = $this->getCommentFromUser($userID);
        if ($comments->num_rows > 0) {
            while ($result = $comments->fetch_assoc()) {
                $this->likes->deleteLikeWithCommentID($result['comment_id']);
            }
        }

        $del = $this->conn->query("DELETE FROM comments WHERE user_name = '$userID'");
        return $del ? true : false;
    }
}