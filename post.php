<?php
require "./includes/connect.php";

if (isset($_GET["r"])) {
    $read = $_GET["r"];
    $con->query("UPDATE notify SET readed = true WHERE notify_id = $read");
}
require_once("./includes/header.php");
?>

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
                        echo "<div class='content rm'>
                                <div class='mt-0'>
                                    <div class=' c-header'>
                                        <span>
                                        <a class='name' href='./profile?user=".$poster['user_name']."'>
                                        <img class='avt' src='" . $poster['avatar'] . "'></span></a>
                                        <div class='c-name'><span>
                                                <a class='name' href='./profile?user=".$poster['user_name']."'>" . $poster['hoten'] . "</a>
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
                                <div class='m-0 hide wh' style='text-align: end;'><span class='read-more'></span></div>";
                        $images = $con->query("SELECT * FROM images WHERE `type` = 'post' AND post_id = " . $row['post_id']);

                        if ($images->num_rows > 0) {
                            echo "<div id='forpost" . $row['post_id'] . "' class='carousel slide mt-1' data-bs-ride='carousel'>
                                        <div class='carousel-inner '>";
                            $i = 0;
                            while ($img = $images->fetch_assoc()) {
                                $active = $i == 0 ? "active" : "";
                                $i++;
                                echo "<div class='carousel-item $active'>
                                                    <img src='" . $img['url'] . "' class='d-block w-100 postImg' alt='...'>
                                                  </div>";
                            }
                            echo "</div>
                                    <button class='carousel-control-prev' type='button' data-bs-target='#forpost" . $row['post_id'] . "' data-bs-slide='prev'>
                                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Previous</span>
                                    </button>
                                    <button class='carousel-control-next' type='button' data-bs-target='#forpost" . $row['post_id'] . "' data-bs-slide='next'>
                                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Next</span>
                                    </button>
                                    </div> ";
                        }

                        echo "<hr class='m-0'>
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
                }
                if ($logged)
                    echo "<div class='content'>
                    <div>
                        <form method='post' id='sendCmt'>
                            <div class='form-group p-1'>
                                <textarea class='form-control f-sm' id='cmtContent' placeholder='Bình luận'
                                    name='comment' required></textarea>
                            </div>
                            <button type='submit' name='send' class='btn btn-danger mb-2'>Enter</button>
                        </form>
                    </div>
                </div>";
                ?>

                <div id='cmt'></div>
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
        <script src="./lib/js/ajax.js"></script>
    </div>
</body>

</html>