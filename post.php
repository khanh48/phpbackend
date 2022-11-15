<?php
session_start();
require "./includes/connect.php";
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
    <script src="./lib/js/main.js"></script>
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
                                <div class='time'><small class='text-secondary'>... phút trước</small></div>
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
                    <button class='like p-1'><i class='fas fa-heart'></i>
                        <span class='count-like'></span>
                    </button>
                    <button class='share p-1'><i class='fas fa-share'></i><span class='count-share'></span>
                    </button>
                </div>
            </div>";
                        echo "<div class='content'>
            <div> <form method='post'>
            <div class='form-group p-1'>
                <textarea class='form-control f-sm' placeholder='Bình luận' name='comment' oninvalid=\"this.setCustomValidity('Vui lòng nhập nội dung cần bình luận.')\" required></textarea>
            </div>
            <button type='submit' name='send' class='btn btn-danger mb-2'>Enter</button>
            </form>
                </div>
            </div>";

                        if (isset($_POST['send'])) {
                            $comment = $_POST['comment'];
                            $id = $_GET['id'];
                            $username = $_SESSION['userID'];
                            $sql = "INSERT INTO comments(content, user_name, post_id) VALUES('$comment', '$username', '$id')";
                            if ($con->query($sql)) {
                                echo "<meta http-equiv='refresh' content='0'>";
                            }
                        }
                        $result = $con->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$pid'");
                        $row = $result->fetch_assoc();
                        $total_records = $row['total'];

                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $limit = 10;

                        $total_page = ceil($total_records / $limit);

                        if ($current_page > $total_page) {
                            $current_page = $total_page;
                        } else if ($current_page < 1) {
                            $current_page = 1;
                        }
                        if ($current_page > 0)
                            $start = ($current_page - 1) * $limit;
                        else
                            $start = 0;

                        $re = $con->query("SELECT * FROM comments WHERE post_id = '$pid' ORDER BY comment_id DESC LIMIT $start, $limit");
                        if ($re->num_rows ? $re->num_rows > 0 : false) {
                            while ($row = $re->fetch_assoc()) {
                                $username = $row['user_name'];
                                $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();
                                echo "<div class='content'>
                                    <div>
                                        <div class=' c-header'>
                                            <span>
                                                <img class='avt' src='" . $poster['avatar'] . "'></span>
                                            <div class='c-name'><span>
                                                    <div class='name'>" . $poster['hoten'] . "</div>
                                                    <div class='time'><small class='text-secondary'>... phút trước</small></div>
                                                </span></div>
                                        </div>
                                    </div>
                                    <div class='c-body'>
                                    " . $row['content'] . "
                                    </div>
                                    <div class='m-0' style='text-align: end;'>
                                        <span class='read-more'></span>
                                    </div>
                                    <hr class='m-0'>
                                    <div class='interactive p-1 m-0'>
                                        <button class='like p-1'><i class='fas fa-heart'></i>
                                            <span class='count-like'></span>
                                        </button>
                                    </div>
                                </div>";
                            }

                            if ($total_records > 10) {
                                echo "<div class='content'>
                                <div class='page'>
                                    <div>";
                                if ($current_page > 1 && $total_page > 1) {
                                    echo "<span class='page-item'><a href='post.php?id=" . $pid . "&page=" . ($current_page - 1) . "'><</a></span>";
                                }

                                for ($i = 1; $i <= $total_page; $i++) {
                                    if ($i == $current_page) {
                                        echo "<span class='cur-page'>" . $i . "</span>";
                                    } else {
                                        echo "<span class='page-item'><a href='post.php?id=" . $pid . "&page=" . $i . "'>" . $i . "</a></span>";
                                    }
                                }

                                if ($current_page < $total_page && $total_page > 1) {
                                    echo "<span class='page-item'><a href='post.php?id=" . $pid . "&page=" . ($current_page + 1) . "'>></a></span>";
                                }
                                echo "
                            </div>
                        </div>
                    </div>";
                            }
                        }
                    } else {
                        echo "<div class='content'>
                        <div class='mt-0' style='text-align:center'>Không có bài viết nào.</div>
                        </div>";
                    }
                }
                ?>
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