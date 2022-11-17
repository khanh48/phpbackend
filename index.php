<?php
require "./includes/connect.php";
?>
<!DOCTYPE html>
<html lang="vi-VN" class="no-js">

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
    <script src="./lib/js/main.js"></script>
    <script src="./lib/js/ajax.js"></script>
    <!--Xoá phần input file default-->
    <script>
    (function(e, t, n) {
        var r = e.querySelector(".no-js");
        r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
    })
    (document, window, 0);
    </script>
</head>

<body>
    <div class="body">
        <?php include('./includes/topbar.php') ?>

        <div class="main">

            <?php include('./includes/barleft.php') ?>
            <div class="right">
                <div class="content">
                    <form method="post">
                        <div class="form-group p-1">
                            <input type="text" class="form-control f-sm mb-1" placeholder="Tiêu đề" name="tieude">
                            <textarea class="form-control f-sm" placeholder="Nội dung" name="noidung"></textarea>
                        </div>
                        <div class="group-file">
                            <select class="custom-select custom-select-sm" name="nhom">
                                <option selected disabled value="">Groups</option>
                                <option value="Bắc">Bắc</option>
                                <option value="Trung">Trung</option>
                                <option value="Nam">Nam</option>
                            </select>
                            <input type="file" id="file-1" class="inputfile inputfile-1"
                                data-multiple-caption="{count} files selected" accept="image/*" multiple />
                            <label for="file-1"> <i class="fas fa-images"></i> <span>Choose
                                    images&hellip;</span></label>
                            <button type="submit" name="post" class="btn btn-danger">Đăng</button>
                        </div>

                        <?php
                        if (isset($_POST['post'])) {
                            if (isset($_SESSION['userID'])) {
                                $title = isset($_POST['tieude']) ? $_POST['tieude'] : '';
                                $content = isset($_POST['noidung']) ? $_POST['noidung'] : '';
                                $group = isset($_POST['nhom']) ? $_POST['nhom'] : 'Bắc';
                                $user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
                                $sql = "INSERT INTO posts(title, content, user_name, nhom) VALUES('$title', '$content', '$user_id','$group')";
                                if ($con->query($sql)) {
                                    echo "<meta http-equiv='refresh' content='3'>";
                                } else {
                                    echo "Lỗi: " . $con->error;
                                }
                            } else {
                                echo "<script>window.alert('Bạn cần đăng nhập để đăng bài.')</script>";
                            }
                        }
                        ?>
                    </form>
                </div>

                <!-- Phân trang -->
                <?php

                $result = $con->query('SELECT COUNT(post_id) AS total FROM posts');
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
                $start = ($current_page - 1) * $limit;
                if ($current_page > 0)
                    $start = ($current_page - 1) * $limit;
                else
                    $start = 0;

                $re = $con->query("SELECT * FROM posts ORDER BY post_id DESC LIMIT $start, $limit");
                if ($re->num_rows > 0) {
                    while ($row = $re->fetch_assoc()) {
                        $username = $row['user_name'];
                        $post = $row['post_id'];
                        $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();
                        $result_cmt = $con->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$post'")->fetch_assoc();
                        $result_like = $con->query("SELECT COUNT(like_id) AS total_like FROM likes WHERE post_id = '$post' AND is_post = true")->fetch_assoc();
                        $total_cmt = $result_cmt['total'] > 0 ? $result_cmt['total'] : '';
                        $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
                        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE post_id = '$post' AND user_name = '$my_id'")->fetch_assoc();
                        $is_liked = '';
                        if ($liked["liked"] > 0)
                            $is_liked = "fas-liked";
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
                            <button class='like p-1' onclick=\"like(" . $row['post_id'] . ",true,'" . $my_id . "');\">
                                <i class='fas fa-heart " . $is_liked . "' id='pl" . $row['post_id'] . "'></i>
                                <span class='count-like' id='p" . $row['post_id'] . "'>" . $total_like . "</span>
                            </button>
                            <button class='comment p-1' onclick=\"window.location.href='./post.php?id=" . $row['post_id'] . "'\">
                            <i class='fas fa-comment'></i>
                            <span class='count-comment'><a href='./post.php'></a>" . $total_cmt . "</span>
    
                                </svg>
                            </button>
                            <button class='share p-1'><i class='fas fa-share'></i><span class='count-share'></span>
                            </button>
                        </div>
                    </div>";
                    }
                    if ($total_records > 10) {
                        echo "<div class='content'>
                    <div class='page'>
                        <div>";
                        if ($current_page > 1 && $total_page > 1) {
                            echo "<span class='page-item'><a href='index.php?page=" . ($current_page - 1) . "'><</a></span>";
                        }

                        for ($i = 1; $i <= $total_page; $i++) {
                            if ($i == $current_page) {
                                echo "<span class='cur-page'>" . $i . "</span>";
                            } else {
                                echo "<span class='page-item'><a href='index.php?page=" . $i . "'>" . $i . "</a></span>";
                            }
                        }

                        if ($current_page < $total_page && $total_page > 1) {
                            echo "<span class='page-item'><a href='index.php?page=" . ($current_page + 1) . "'>></a></span>";
                        }
                        echo "</div>
                    </div>
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


        <script src="./lib/js/filecustom.js"></script>
    </div>
</body>

</html>