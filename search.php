<?php
require "./includes/connect.php";
require_once("./includes/header.php");
?>

<body>
    <?php include('./includes/topbar.php') ?>

    <div class="main">
        <?php include('./includes/barleft.php') ?>
        <div class="right">
            <div class="content">
                <form method="get">
                    <div class="form-group p-1">
                        <input type="text" class="form-control f-sm mb-1" placeholder="Tìm kiếm" name="search-content">
                    </div>
                    <div class="group-file">
                        <select class="form-select form-select-sm" name="type">
                            <option selected disabled value="" required>Tìm kiếm theo..</option>
                            <option value="0">Họ tên</option>
                            <option value="1">Tiêu đề bài viết</option>
                        </select>
                        <button type="submit" name="search" class="btn btn-danger btn-sm">Tìm</button>
                    </div>
                </form>
            </div>
            <?php

            if (isset($_GET['search'])) {
                $content = isset($_GET['search-content']) ? $_GET['search-content'] : '';
                $type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
                if ($type == 1) {
                    $sql = "SELECT * FROM posts WHERE title LIKE '%$content%'";
                    $re = $con->query($sql);
                    if ($re->num_rows > 0) {
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
                            <button class='comment p-1'><i class='fas fa-comment'></i><span class='count-comment'>1</span>
    
                                </svg>
                            </button>
                            <button class='share p-1'><i class='fas fa-share'></i><span class='count-share'></span>
                            </button>
                        </div>
                    </div>";
                        }
                    }
                } else {
                    $sql = "SELECT * FROM users WHERE hoten LIKE '%$content%'";
                    $re = $con->query($sql);
                    if ($re->num_rows > 0) {
                        while ($row = $re->fetch_assoc()) {
                            $username = $row['user_name'];
                            $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();
                            echo "<div class='content'>
                                <div class='pb-2'>
                                <div class=' c-header'>
                                <span>
                                    <img class='avt' src='" . $poster['avatar'] . "'></span>
                                <div class='c-name'><span>
                                        <div class='name'>" . $row['hoten'] . "</div>
                                        <div class='time'><small class='text-secondary'>Hoạt động ... phút trước</small></div>
                                    </span></div>
                            </div></div></div>";
                        }
                    }
                }
            }
            ?>
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