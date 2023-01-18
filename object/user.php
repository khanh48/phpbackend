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
        $post = $this->conn->query("SELECT * FROM nguoidung WHERE taikhoan ='$userID'");
        return $post->fetch_assoc();
    }

    function changePassword($user, $password)
    {
        $post = $this->conn->query("UPDATE nguoidung SET matkhau = '$password' WHERE taikhoan ='$user'");
        return $post ? true : false;
    }

    function deleteUser($userID)
    {
        $this->likes->deleteLikeWithUserID($userID);
        $this->comments->deleteCommentWithUser($userID);
        $this->posts->deletePostWithUserName($userID);
        $this->conn->query("DELETE FROM hinhanh WHERE taikhoan = '$userID'");
        $this->conn->query("DELETE FROM thongbao WHERE nguoinhan = '$userID' OR nguoigui = '$userID'");
        $del = $this->conn->query("DELETE FROM nguoidung WHERE taikhoan = '$userID'");
        return $del ? true : false;
    }
}