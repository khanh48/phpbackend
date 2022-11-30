<?php
require "./includes/connect.php";

if (isset($_GET["r"])) {
    $read = $_GET["r"];
    $con->query("UPDATE notify SET readed = true WHERE notify_id = $read");
}
?>
<!DOCTYPE html>
<html lang="vi-VN">

<head>
    <meta charset="UTF-8">
    <title>Phượt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./lib/images/favicon.png">
    <link rel="stylesheet" href="./lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="./lib/css/main.css">
    <script src="./lib/js/jquery.min.js"></script>
    <script src="./lib/js/bootstrap.min.js"></script>
    <script src="./lib/js/main.js"></script>
    <script src="./lib/js/socket.js"></script>
    <script src="./lib/js/ajax.js"></script>
</head>

<body>
    <div class="body">
        <?php include('./includes/topbar.php') ?>

        <div class="main">
            <?php include('./includes/barleft.php') ?>
            <div class="right">
                <?php

                if (isset($_GET['id'])) {
                    $pid = $_GET['id'];

                    $sql = "SELECT * FROM posts WHERE post_id = '$pid'";
                    $result = $con->query($sql);
                    $result_like = $con->query("SELECT COUNT(like_id) AS total_like FROM likes WHERE post_id = '$pid' AND is_post = true")->fetch_assoc();
                    $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
                    $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE post_id = '$pid' AND user_name = '$my_id'")->fetch_assoc();
                    $is_liked = '';
                    if ($liked["liked"] > 0)
                        $is_liked = "fas-liked";
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $username = $row['user_name'];
                        $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();
                        echo "<div class='content'>
                                <div class='mt-0'>
                                    <div class=' c-header'>
                                        <span>
                                            <img class='avt' src='" . $poster['avatar'] . "'></span>
                                        <div class='c-name'><span>
                                                <div class='name'>" . $poster['hoten'] . "</div>
                                                <div class='time'><small class='text-secondary'>" . getTime($row['date']) . "</small></div>
                                            </span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class='title'>
                                        <div class='name'>" . $row['nhom'] . "</div><span>></span>
                                        <div class='name'>" . $row['title'] . "</div>
                                    </div>
                                </div>
                                <div class='c-body'>
                                " . $row['content'] . "
                                </div>
                                <div class='m-0' style='text-align: end;'><span class='read-more'></span></div>
                                <hr class='m-0'>
                                <div class='interactive p-1 m-0'>
                                    <button class='like p-1' onclick=\"like(" . $row['post_id'] . ",true,'" . $my_id . "','" . $poster["user_name"] . "');\">
                                        <i class='fas fa-heart " . $is_liked . "' id='pl" . $row['post_id'] . "''></i>
                                        <span class='count-like' id='p" . $row['post_id'] . "'>" . $total_like . "</span>
                                    </button>
                                    <button class='share p-1'><i class='fas fa-share'></i><span class='count-share'></span>
                                    </button>
                                </div>
                            </div>";
                    } else {
                        echo "<div class='content'>
                                <div class='mt-0' style='text-align:center'>Không có bài viết nào.</div>
                        </div>";
                    }
                } ?>

                <div class='content'>
                    <div>
                        <form method='post' id="sendCmt">
                            <div class='form-group p-1'>
                                <textarea class='form-control f-sm' id="cmtContent" placeholder='Bình luận'
                                    name='comment' required></textarea>
                            </div>
                            <button type='submit' name='send' class='btn btn-danger mb-2'>Enter</button>
                        </form>
                    </div>
                </div>

                <div id="cmt"></div>
            </div>

        </div>
        <div class="toast-container position-fixed bottom-0 start-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <!-- <img src="..." class="rounded me-2" alt="..."> -->
                    <strong class="me-auto" id="headerToast"></strong>
                    <small id="toastTime"></small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toastMessage">
                </div>
            </div>
        </div>

        <footer id="ft">
            <div class="top animated"></div>
            <div class="bot">
                <div>Run For Your Life</div>

            </div>
        </footer>


    </div>
</body>

</html>