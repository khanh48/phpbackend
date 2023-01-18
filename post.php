<?php
require "./includes/connect.php";
require_once("./includes/header.php");
include_once("./object/posts.php");
include_once("./object/comments.php");
$postOj = new Post($con);
$commentOj = new Comment($con);

if (isset($_GET["r"])) {
    $read = $_GET["r"];
    $con->query("UPDATE thongbao SET trangthai = true WHERE mathongbao = $read");
}
if (isset($_POST['delete-post']) && $logged) {
    $postOj->deletePost($_POST["delete-post"]);
}
if (isset($_POST['delete-cmt']) && $logged) {
    $commentOj->deleteComment($_POST["delete-cmt"]);
}
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

                    $sql = "SELECT * FROM baiviet WHERE mabaiviet = '$pid'";
                    $result = $con->query($sql);
                    $result_like = $con->query("SELECT COUNT(maluotthich) AS total_like FROM luotthich WHERE mabaiviet = '$pid' AND loai = true")->fetch_assoc();
                    $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
                    $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE mabaiviet = '$pid' AND taikhoan = '$my_id'")->fetch_assoc();
                    $is_liked = '';
                    if ($liked["liked"] > 0)
                        $is_liked = "fas-liked";
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $username = $row['taikhoan'];
                        $poster = $con->query("SELECT * FROM nguoidung WHERE taikhoan = '$username'")->fetch_assoc();
                        echo "<div class='content rm'>
                                <div class='mt-0 d-flex justify-content-between'>
                                    <div class=' c-header'>
                                        <span>
                                        <a class='name' href='./profile?user=" . $poster['taikhoan'] . "'>
                                        <img class='avt' src='" . $poster['anhdaidien'] . "'></span></a>
                                        <div class='c-name'><span>
                                                <a class='name' href='./profile?user=" . $poster['taikhoan'] . "'>" . $poster['hoten'] . "</a>
                                                <div class='time'><small class='text-secondary'>" . getTime($row['ngaytao']) . "</small></div>
                                            </span></div>
                                    </div>";
                        if ($myRank === "Admin" || $poster['taikhoan'] === $my_id) {
                            echo "<button name='delete-notification' class='btn-close py-1 px-3' value='a' data-bs-toggle='modal'
                                        data-bs-target='#delete-post' onclick=\"deletePost($pid)\"></button>";
                        }
                        echo "</div>
                                <div>
                                    <div class='title'>
                                        <div class='name'>" . $row['nhom'] . "</div><span>></span>
                                        <div class='name'>" . $row['tieude'] . "</div>
                                    </div>
                                </div>
                                <div class='c-body'>
                                    " . $row['noidung'] . "
                                </div>
                                <div class='m-0 hide wh' style='text-align: end;'><span class='read-more'></span></div>";
                        $images = $con->query("SELECT * FROM hinhanh WHERE `loai` = 'post' AND mabaiviet = " . $row['mabaiviet']);

                        if ($images->num_rows > 0) {
                            echo "<div id='forpost" . $row['mabaiviet'] . "' class='carousel slide mt-1' data-bs-ride='carousel'>
                                        <div class='carousel-inner '>";
                            $i = 0;
                            while ($img = $images->fetch_assoc()) {
                                $active = $i == 0 ? "active" : "";
                                $i++;
                                echo "<div class='carousel-item $active'><img src='" . $img['url'] . "' class='d-block w-100 postImg' alt='...'></div>";
                            }
                            echo "</div>
                                    <button class='carousel-control-prev' type='button' data-bs-target='#forpost" .
                                $row['mabaiviet'] . "' data-bs-slide='prev'>
                                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Previous</span>
                                    </button>
                                    <button class='carousel-control-next' type='button' data-bs-target='#forpost" .
                                $row['mabaiviet'] . "' data-bs-slide='next'>
                                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Next</span>
                                    </button>
                                    </div> ";
                        }
                        echo "<hr class='m-0'>
                            <div class='interactive p-1 m-0'>
                                <button class='like p-1' onclick=\" like(" . $row['mabaiviet'] . ",true,'" . $my_id
                            . "','" . $poster["taikhoan"] . "');\">
                    <i class='fas fa-heart action " . $is_liked . "' id='pl" . $row['mabaiviet'] . "''></i>
                                    <span class='count-like' id='p" . $row['mabaiviet'] . "'>" . $total_like . "</span>
                                </button>
                                <button class='share p-1'><i class='fas fa-share action'></i><span class='count-share'></span>
                                </button>
                            </div>
                        </div>";

                        if ($logged)
                            echo "<div class='content'>
                            <div>
                                <form method='post' id='sendCmt'>
                                    <div class='form-group p-1'>
                                        <textarea class='form-control f-sm' id='cmtContent' placeholder='Bình luận'
                                            name='comment' required></textarea>
                                    </div>
                                    <button type='submit' name='send' class='btn btn-danger mb-2'>Gửi</button>
                                </form>
                            </div>
                        </div>";
                    } else {
                        echo "<div class='content'>
                                <div class='mt-0' style='text-align:center'>Không có bài viết nào.</div>
                        </div>";
                    }
                } ?>

                <div id='cmt'></div>
            </div>

        </div>


        <div class="modal modal-alert py-5" tabindex="-1" role="dialog" id="delete-post">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-body p-4 text-center">
                        <h5 class="mb-0">Xóa bài viết?</h5>
                        <p class="mb-0">Bài viết sẽ bị xóa vĩnh viễn.</p>
                    </div>
                    <form method="post">
                        <div class="modal-footer flex-nowrap p-0">
                            <button name="delete-post"
                                class="btn btn-lg btn-link text-danger fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"
                                id="confirm-yes"><strong>Xóa</strong></button>
                            <button type="button"
                                class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"
                                data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal modal-alert py-5" tabindex="-1" role="dialog" id="delete-cmt">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-body p-4 text-center">
                        <h5 class="mb-0">Xóa bình luận?</h5>
                        <p class="mb-0">Bình luận sẽ bị xóa vĩnh viễn.</p>
                    </div>
                    <form method="post">
                        <div class="modal-footer flex-nowrap p-0">
                            <button name="delete-cmt"
                                class="btn btn-lg btn-link text-danger fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"
                                id="confirm-yes-1"><strong>Xóa</strong></button>
                            <button type="button"
                                class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"
                                data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
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