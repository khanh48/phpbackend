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
        $post = $this->conn->query("SELECT * FROM posts WHERE post_id = '$postID'");
        return $post->fetch_assoc();
    }


    function likePost($postID)
    {
        $post = $this->conn->query("SELECT * FROM posts WHERE title LIKE '%$postID%'");
        return $post;
    }

    function getPostFromUser($userID)
    {
        $post = $this->conn->query("SELECT * FROM posts WHERE user_name = '$userID'");
        return $post;
    }

    function addPost($title, $content, $userID, $group = "Bắc")
    {
        $add = $this->conn->query("INSERT INTO posts (title, content, user_name, nhom) VALUES ('$title', '$content', '$userID', '$group')");
        return $add ? true : false;
    }

    function updatePost($postID, $title, $content, $group)
    {
        $update = $this->conn->query("UPDATE posts SET title ='$title', content = '$content', nhom = '$group' WHERE post_id = '$postID'");
        return $update ? true : false;
    }

    function deletePost($postID)
    {
        $del = false;
        if ($this->comments->deleteComments($postID) && $this->likes->deleteLikeWithPostID($postID))
            $del = $this->conn->query("DELETE FROM posts WHERE post_id = $postID") ? true : false;
        return $del;
    }

    function deletePostWithUserName($userID)
    {
        $posts = $this->getPostFromUser($userID);
        if ($posts->num_rows > 0) {
            while ($row = $posts->fetch_assoc()) {
                $this->comments->deleteComments($row['post_id']);
                $this->likes->deleteLikeWithPostID($row['post_id']);
            }
        }
        $del = $this->conn->query("DELETE FROM posts WHERE user_name = '$userID'") ? true : false;
        return $del;
    }
}