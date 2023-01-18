<?php
require "./includes/connect.php";
require_once("./includes/header.php");
include_once("./object/posts.php");
$postOj = new Post($con);

if (isset($_POST['delete-post']) && $logged) {
    $postOj->deletePost($_POST["delete-post"]);
}
?>

<body>
    <?php include('./includes/topbar.php') ?>

    <div class="main">
        <?php include('./includes/barleft.php') ?>
        <div class="right">
            <?php
            if (isset($_GET["user"])) {
                $userID = $_GET["user"];
                $sql = "SELECT * FROM nguoidung WHERE taikhoan = '$userID'";
                $post = "SELECT COUNT(mabaiviet) AS total_post FROM baiviet WHERE taikhoan = '$userID'";
                $re_post = $con->query($post);
                $re = $con->query($sql);
                $originAvt = '';
                if ($re->num_rows > 0) {
                    $row = $re->fetch_assoc();

                    $originAvt = $row['anhdaidien'];
                    echo "<div class='content'>
                    <div class='mt-0 ms-2'>
                    <div class=' c-header'>
                        <img class='avt-profile rounded-circle' src='" . $originAvt . "'>
                        <div>
                        <div class='name'>" . $row['hoten'] . "</div>";
                    if ($re_post->num_rows > 0) {
                        $r = $re_post->fetch_assoc();
                        echo "<p class='ps-2 mb-0'>Bài viết: " . $r['total_post'] . "</p>";
                    }
                    echo "<p class='ps-2 mb-0'>Giới tính: " . $row['gioitinh'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Ngày sinh: " . $row['ngaysinh'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Số điện thoại: " . $row['sodienthoai'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Sở thích: " . $row['sothich'] . "</p>";

                    echo "</div></div>
                        </div></div>";
                    $nam = $row['gioitinh'] == 'Nam' ? 'selected' : '';
                    $nu = $row['gioitinh'] == 'Nữ' ? 'selected' : '';

                    if ($userID === $my_id) {
                        echo "<div class='content'><div><form method='post'  enctype='multipart/form-data'>
                        <div class='form-group p-1'>
                        <table>
                        <tr>
                            <td>
                            <label for='avt'>Ảnh đại diện:</label></td>
                            <td>
                            <input type='file' class='form-control f-sm mb-1' name='avt' id='avt' accept='image/jpeg,image/jpg,image/png' /></td>
                        </tr>
                            <tr>
                                <td>
                                <label for='hoten'>Họ tên:</label></td>
                                <td>
                                <input type='text' class='form-control f-sm mb-1' name='hoten' id='hoten' value='" . $row['hoten'] . "' /></td>
                            </tr>
                            <tr>
                                <td><label for='gioitinh'>Giới tính:</label></td>
                                <td><select id='gioitinh' class='form-control f-sm mb-1' name='gioitinh'>
                                <option value=''>Khác</option>
                                <option value='Nam' " . $nam . ">Nam</option>
                                <option value='Nữ' " . $nu . ">Nữ</option>
                                </select></td>
                            </tr>
                            <tr>
                                <td><label for='ngaysinh'>Ngày sinh:</label></td>
                                <td><input type='date' class='form-control f-sm mb-1' name='ngaysinh' id='ngaysinh' " . ($row['ngaysinh'] == "0000-00-00" ?  '' : ("value='" . $row['ngaysinh']) . "'") . " /></td>
                            </tr>
                            <tr>
                                <td>
                                <label for='sdt'>Số điện thoại:</label></td>
                                <td>
                                <input type='text' class='form-control f-sm mb-1' name='sdt' id='sdt' value='" . $row['sodienthoai'] . "' /></td>
                            </tr>
                            <tr>
                                <td><label for='sothich'>Sở thích:</label></td>
                                <td><textarea class='form-control f-sm' name='sothich' id='sothich' />" . $row['sothich'] . "</textarea></td>
                            </tr>
                        </table>
                        <div>
                            <button class='btn btn-success my-2 f-sm' name='save'>Lưu</button>
                            <span class='my-auto' data-bs-toggle='modal' data-bs-target='#changePass' id='changePassBtn'>Đổi mật khẩu</span></div>
                        </div></form></div></div>";
                    }
                    $posts = $con->query("SELECT * FROM baiviet WHERE taikhoan='$userID' ORDER BY ngaytao DESC");
                    if ($posts->num_rows > 0) {
                        while ($row = $posts->fetch_assoc()) {
                            $post = $row['mabaiviet'];
                            $poster = $userObj->getUser($userID);
                            $result_cmt = $con->query("SELECT COUNT(mabinhluan) AS total FROM binhluan WHERE mabaiviet = '$post'")->fetch_assoc();
                            $result_like = $con->query("SELECT COUNT(maluotthich) AS total_like FROM luotthich WHERE mabaiviet = '$post' AND loai = true")->fetch_assoc();
                            $total_cmt = $result_cmt['total'] > 0 ? $result_cmt['total'] : '';
                            $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
                            $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE mabaiviet = '$post' AND taikhoan = '$my_id'")->fetch_assoc();
                            $is_liked = '';
                            if ($liked["liked"] > 0)
                                $is_liked = "fas-liked";
                            echo "<div class='content rm'>
                                        <div class='d-flex justify-content-between'>
                                            <div class=' c-header'>
                                                <span><img class='avt' src='" . $poster['anhdaidien'] . "'></span>
                                                <div class='c-name'>
                                                    <span><div class='name'>" . $poster['hoten'] . "</div>
                                                        <div class='time'><small class='text-secondary'>" . getTime($row['ngaytao']) . "</small>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>";
                            if ($myRank === "Admin" || $poster['taikhoan'] === $my_id) {
                                echo "<button name='delete-notification' class='btn-close py-1 px-3' value='a' data-bs-toggle='modal'
                                                            data-bs-target='#delete-post' onclick=\"deletePost($post)\"></button>";
                            }
                            echo "
                                        </div>
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
                                    echo "<div class='carousel-item $active'>
                                                <img src='" . $img['url'] . "' class='d-block w-100 postImg' alt='...'>
                                                </div>";
                                }
                                echo "</div>
                                            <button class='carousel-control-prev' type='button' data-bs-target='#forpost" . $row['mabaiviet'] . "' data-bs-slide='prev'>
                                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                                <span class='visually-hidden'>Previous</span>
                                            </button>
                                            <button class='carousel-control-next' type='button' data-bs-target='#forpost" . $row['mabaiviet'] . "' data-bs-slide='next'>
                                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                                <span class='visually-hidden'>Next</span>
                                            </button>
                                            </div> ";
                            }

                            echo " <hr class='m-0'>
                                    <div class='interactive p-1 m-0'>
                                        <button class='like p-1' onclick=\" like(" . $row['mabaiviet'] . ",true,'" . $my_id . "', '" . $poster['taikhoan'] . "');\">
                                            <i class='fas fa-heart action " . $is_liked . "' id='pl" . $row['mabaiviet'] . "'></i>
                                            <span class='count-like' id='p" . $row['mabaiviet'] . "'>" . $total_like . "</span>
                                        </button>
                                        <button class='comment p-1' onclick=\" window.location.href='./post.php?id=" . $row['mabaiviet'] . "'\">
                                    <i class='fas fa-comment action'></i>
                                    <span class='count-comment'><a href='./post.php'></a>" . $total_cmt . "</span>
                        
                                    </svg>
                                    </button>
                                    <button class='share p-1'><i class='fas fa-share action'></i><span class='count-share'></span>
                                    </button>
                                    </div>
                                </div>";
                        }
                        echo "<script>loadReadMore()</script>";
                    }
                }

                if (isset($_POST['save'])) {
                    $ht = $_POST['hoten'];
                    $gt = $_POST['gioitinh'];
                    $ns = $_POST['ngaysinh'] == '' ? 'null' : $_POST['ngaysinh'];
                    $sdt = $_POST['sdt'];
                    $st = $_POST['sothich'];
                    $avt = $originAvt;
                    if ($_FILES['avt']['tmp_name'] !== '') {
                        $file = $_FILES['avt'];
                        $target_dir = "./lib/images/" . $my_id . "/";
                        if (!file_exists($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                        $check = true;
                        $avt = $target_dir . str_replace(" ", "_", basename($file["name"]));

                        if (getimagesize($file["tmp_name"]) !== false)
                            $check = true;
                        else
                            $check = false;

                        if ($check) {
                            move_uploaded_file($file["tmp_name"], $avt);
                        }
                    }

                    $sql = $con->query("UPDATE nguoidung SET hoten = '$ht', gioitinh = '$gt', ngaysinh = '$ns', sodienthoai = '$sdt', sothich = '$st', anhdaidien = '$avt' WHERE taikhoan = '$userID'");
                    if ($sql) {
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else echo $con->error;
                }
            }

            ?>
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
                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"
                            data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-alert py-5" tabindex="-1" role="dialog" id="changePass">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4">
                    <small class="text-danger" id="failToChangePass"></small>
                    <table>
                        <tr>
                            <td><label class="form-label" for="oldPass">Mật khẩu cũ:</label></td>
                            <td><input class="form-control" type="password" name="oldPass" id="oldPass"></td>
                        </tr>
                        <tr>
                            <td><label class="form-label" for="newPass">Mật khẩu mới:</label></td>
                            <td><input class="form-control" type="password" name="newPass" id="newPass"></td>
                        </tr>
                        <tr>
                            <td><label class="form-label" for="reOldPass">Nhập lại mật khẩu:</label></td>
                            <td><input class="form-control" type="password" name="reOldPass" id="reNewPass"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <button
                        class="btn btn-lg btn-link text-success fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"
                        id="changePassword"><strong>Xác nhận</strong></button>
                    <button class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"
                        data-bs-dismiss="modal">Hủy</button>
                </div>
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