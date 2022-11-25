<?php
if (isset($_GET["target"])) {
    if ($_GET["target"] === "members")
        echo "./includes/member_manager.php";
    if ($_GET["target"] === "posts")
        echo "./includes/posts_manager.php";
}