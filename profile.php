<?php
require "./includes/connect.php";
require_once("./includes/header.php");
?>

<body>
    <?php include('./includes/topbar.php') ?>

    <div class="main">
        <?php include('./includes/barleft.php') ?>
        <div class="right">
            <?php
            if (isset($_SESSION['userID'])) {
                $userID = $_SESSION['userID'];
                $sql = "SELECT * FROM users WHERE user_name = '$userID'";
                $post = "SELECT COUNT(post_id) AS total_post FROM posts WHERE user_name = '$userID'";
                $re_post = $con->query($post);
                $re = $con->query($sql);
                if ($re->num_rows > 0) {
                    $row = $re->fetch_assoc();
                    echo "<div class='content'>
                    <div class='mt-0 ms-2'>
                    <div class=' c-header'>
                        <img class='avt-profile rounded-circle' src='./lib/images/default_avatar.png'>
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
                    echo "<div class='content'>
                        <div><form method='post'>
                        <div class='form-group p-1'>
                        <table>
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
                                <td><input type='date' class='form-control f-sm mb-1' name='ngaysinh' id='ngaysinh' value='" . $row['ngaysinh'] . "' /></td>
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
                        <button class='f-sm btn btn-success my-2' name='save'>Lưu</button>
                        </div></form></div>
                        </div>";
                }


                if (isset($_POST['save'])) {
                    $ht = $_POST['hoten'];
                    $gt = $_POST['gioitinh'];
                    $ns = $_POST['ngaysinh'] == '' ? 'null' : $_POST['ngaysinh'];
                    $sdt = $_POST['sdt'];
                    $st = $_POST['sothich'];

                    $isnull = $con->query("UPDATE users SET hoten = '$ht', gioitinh = '$gt', ngaysinh = $ns, sodienthoai = '$sdt', sothich = '$st' WHERE user_name = '$userID'");
                    $notnull = $con->query("UPDATE users SET hoten = '$ht', gioitinh = '$gt', ngaysinh = '$ns', sodienthoai = '$sdt', sothich = '$st' WHERE user_name = '$userID'");
                    if ($ns == 'null' ? $isnull : $notnull) {
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else echo $con->error;
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