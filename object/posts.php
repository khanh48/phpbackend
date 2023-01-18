<?php

class Post
{
    public $conn;
    public $comments;
    public $likes;

    function __construct(mixed $con)
    {
        include_once("comments.php");
        include_once("likes.php");
        $this->conn = $con;
        $this->comments = new Comment($con);
        $this->likes = new Like($con);
    }

    function getPost($postID)
    {
        $post = $this->conn->query("SELECT * FROM baiviet WHERE mabaiviet = '$postID'");
        return $post->fetch_assoc();
    }


    function likePost($postID)
    {
        $post = $this->conn->query("SELECT * FROM baiviet WHERE tieude LIKE '%$postID%'");
        return $post;
    }

    function getPostFromUser($userID)
    {
        $post = $this->conn->query("SELECT * FROM baiviet WHERE taikhoan = '$userID'");
        return $post;
    }

    function addPost($title, $content, $userID, $group = "Bắc")
    {
        $add = $this->conn->query("INSERT INTO baiviet (tieude, noidung, taikhoan, nhom) VALUES ('$title', '$content', '$userID', '$group')");
        return $add ? true : false;
    }

    function updatePost($postID, $title, $content, $group)
    {
        $update = $this->conn->query("UPDATE baiviet SET tieude ='$title', noidung = '$content', nhom = '$group' WHERE mabaiviet = '$postID'");
        return $update ? true : false;
    }

    function deletePost($postID)
    {
        $del = false;
        if ($this->comments->deleteComments($postID) && $this->likes->deleteLikeWithPostID($postID))
            $del = $this->conn->query("DELETE FROM baiviet WHERE mabaiviet = $postID") ? true : false;
        return $del;
    }

    function deletePostWithUserName($userID)
    {
        $posts = $this->getPostFromUser($userID);
        if ($posts->num_rows > 0) {
            while ($row = $posts->fetch_assoc()) {
                $this->comments->deleteComments($row['mabaiviet']);
                $this->likes->deleteLikeWithPostID($row['mabaiviet']);
            }
        }
        $del = $this->conn->query("DELETE FROM baiviet WHERE taikhoan = '$userID'") ? true : false;
        return $del;
    }
}