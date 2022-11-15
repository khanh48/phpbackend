<?php
session_start();
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
    <script src="./lib/js/main.js"></script>
</head>

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
                    <div class='mt-0 ml-2'>
                    <div class=' c-header'>
                        <img class='avt-profile' src='./lib/images/default_avatar.png'>
                        <div>
                        <div class='name'>" . $row['hoten'] . "</div>";
                    if ($re_post->num_rows > 0) {
                        $r = $re_post->fetch_assoc();
                        echo "<p class='pl-2 mb-0'>Bài viết: " . $r['total_post'] . "</p>";
                    }
                    echo "<p class='pl-2 mb-0'>Giới tính: " . $row['gioitinh'] . "</p>";
                    echo "<p class='pl-2 mb-0'>Ngày sinh: " . $row['ngaysinh'] . "</p>";
                    echo "<p class='pl-2 mb-0'>Số điện thoại: " . $row['sodienthoai'] . "</p>";
                    echo "<p class='pl-2 mb-0'>Sở thích: " . $row['sothich'] . "</p>";

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
                        <button class='f-sm btn btn-success' name='save'>Lưu</button>
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
    <footer id="ft">
        <div class="top animated"></div>
        <div class="bot">
            <div>Run For Your Life</div>

        </div>
    </footer>


    </div>
</body>

</html>