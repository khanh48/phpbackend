<?php require_once("./connect.php") ?>
<div class="content w-100" id="qltv">
    <div>
        <h3 class="ml-2">Quản lý thành viên</h3>
        <form action="" method="get">
            <div class="row">
                <div class="form-group col-5 ml-2">
                    <input class="form-control f-sm" type="text" name="f-user" placeholder="User name">
                </div>
                <button type="submit" class="btn btn-success" name="find-user">Tìm</button>
            </div>
        </form>
        <table class="table table-hover">
            <form action="" method="post">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Tên User</th>
                        <th>Họ Tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Sở thích</th>
                        <th>Số điện thoại</th>
                        <th>Chức vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <div class="form-group">
                        <?php
                        $user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
                        $f_user = isset($_GET['find-user']) ? $_GET['f-user'] : '';
                        $f = $user_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name = '$f_user' AND user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE user_name = '$f_user' AND chucvu <> 'Admin'");
                        $notf = $user_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE  chucvu <> 'Admin'");
                        $re = isset($_GET['find-user']) ? $f : $notf;

                        if (($f_user == '' ? $notf : $re)->num_rows > 0) {
                            while ($row = ($f_user == '' ? $notf : $re)->fetch_assoc()) {
                                $nam = $row['gioitinh'] == 'Nam' ? 'selected' : '';
                                $nu = $row['gioitinh'] == 'Nữ' ? 'selected' : '';
                                $ad = $row['chucvu'] == 'Admin' ? 'selected' : '';
                                $tv = $row['chucvu'] == 'Thành viên' ? 'selected' : '';
                                echo "<tr>
                                        <td><input type='checkbox' name='checkbox[]' value='" . $row['user_name'] . "'></td>
                                        <td>" . $row['user_name'] . "</td>
                                        <td><input class='form-control f-sm' type='text' name='hoten[]' value='" . $row['hoten'] . "' /></td>
                                        <td><input class='form-control f-sm' type='date' name='ngaysinh[]' value='" . $row['ngaysinh'] . "' /></td>
                                        <td><select class='form-control f-sm mb-1' name='gioitinh[]'>
                                        <option value=''>Khác</option>
                                        <option value='Nam' " . $nam . ">Nam</option>
                                        <option value='Nữ' " . $nu . ">Nữ</option>
                                        </select></td>
                                        <td><textarea class='form-control f-sm' name='sothich[]'/>" . $row['sothich'] . "</textarea></td>
                                        <td><input type='text' class='form-control f-sm mb-1' name='sdt[]' value='" . $row['sodienthoai'] . "' /></td>
                                        
                                        <td><select class='form-control f-sm mb-1' name='chucvu[]'>
                                        <option value='Thành viên' " . $tv . ">Thành viên</option>
                                        <option value='Admin' " . $ad . ">Admin</option>
                                        </select></td>
                                    </tr>";
                            }
                        }
                        if (isset($_POST['del']) && isset($_SESSION['userID'])) {
                            $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                            $re = $con->query($sql)->fetch_assoc();
                            if ($re['chucvu'] === 'Admin') {
                                if (isset($_POST['checkbox'])) {
                                    $cbarr = $_POST['checkbox'];
                                    foreach ($cbarr as $id) {
                                        $post = $con->query("SELECT post_id FROM posts INNER JOIN users ON users.user_name = posts.user_name WHERE users.user_name = '$id'");
                                        if ($post->num_rows > 0) {
                                            while ($p = $post->fetch_assoc()) {
                                                $pid = $p['post_id'];
                                                $con->query("DELETE FROM comments WHERE comments.post_id = '$pid'");
                                            }
                                        }
                                        $con->query("DELETE FROM posts WHERE user_name = '$id'");
                                        $sql = $con->query("DELETE FROM users WHERE user_name = '$id'");
                                        if ($sql) {
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($_POST['save']) && isset($_SESSION['userID'])) {
                            $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                            $re = $con->query($sql)->fetch_assoc();
                            if ($re['chucvu'] === 'Admin') {
                                if (isset($_POST['checkbox'])) {
                                    $cbarr = $_POST['checkbox'];
                                    $check = $user_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE  chucvu <> 'Admin'");
                                    $checked = array();
                                    $uname = '';
                                    if ($check->num_rows > 0) {
                                        $dem = 0;
                                        while ($row = $check->fetch_assoc()) {
                                            foreach ($cbarr as $cb) {
                                                if ($cb === $row['user_name']) {
                                                    $checked[$dem] = "true";
                                                }
                                            }
                                            if (!isset($checked[$dem])) {
                                                $checked[$dem] = 'false';
                                            }
                                            $dem++;
                                        }
                                    }
                                    $update = array($_POST['checkbox'], $_POST['hoten'], $_POST['ngaysinh'], $_POST['gioitinh'], $_POST['sothich'], $_POST['sdt'], $_POST['chucvu']);

                                    $dem2 = 0;
                                    for ($i = 0; $i < count($checked); $i++) {
                                        if ($checked[$i] == "true") {
                                            $update[0][$i] = $cbarr[$dem2];
                                            $uname = $update[0][$i];
                                            $hoten = $update[1][$i];
                                            $ngaysinh = $update[2][$i] == '' ? 'null' : $update[2][$i];
                                            $gioitinh = $update[3][$i];
                                            $sothich = $update[4][$i];
                                            $sdt = $update[5][$i];
                                            $chucvu = $update[6][$i];
                                            $isnull = $con->query("UPDATE users SET hoten ='$hoten', ngaysinh = $ngaysinh, gioitinh = '$gioitinh', sothich = '$sothich', sodienthoai = '$sdt', chucvu ='$chucvu' WHERE user_name = '$uname'");
                                            $notnull = $con->query("UPDATE users SET hoten ='$hoten', ngaysinh = '$ngaysinh', gioitinh = '$gioitinh', sothich = '$sothich', sodienthoai = '$sdt', chucvu ='$chucvu' WHERE user_name = '$uname'");
                                            $checknull = $ngaysinh == 'null' ? $isnull : $notnull;
                                            if ($checknull) {
                                                echo "<meta http-equiv='refresh' content='0'>";
                                            }
                                            $dem2++;
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </tbody>
                <caption><button type="submit" class="btn-success btn ml-2" name="save">Lưu</button>
                    <button type="submit" class="btn-danger btn ml-2" name="del">Xoá</button>
                </caption>
            </form>
        </table>
    </div>
</div>