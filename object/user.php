<?php
class User
{
    public $conn;
    public $comments;
    public $posts;
    public $likes;


    function __construct(mixed $con)
    {
        include_once("comments.php");
        include_once("posts.php");
        $this->conn = $con;
        $this->comments = new Comment($con);
        $this->posts = new Post($con);
        $this->likes = new Like($con);
    }

    function getUser($userID)
    {
        $post = $this->conn->query("SELECT * FROM users WHERE user_name ='$userID'");
        return $post->fetch_assoc();
    }

    function changePassword($user, $password)
    {
        $post = $this->conn->query("UPDATE users SET pass = '$password' WHERE user_name ='$user'");
        return $post ? true : false;
    }

    function deleteUser($userID)
    {
        $this->likes->deleteLikeWithUserID($userID);
        $this->comments->deleteCommentWithUser($userID);
        $this->posts->deletePostWithUserName($userID);
        $this->conn->query("DELETE FROM images WHERE owner = '$userID'");
        $this->conn->query("DELETE FROM notifications WHERE to_user = '$userID' OR from_user = '$userID'");
        $del = $this->conn->query("DELETE FROM users WHERE user_name = '$userID'");
        return $del ? true : false;
    }
}