<?php
require_once "./includes/connect.php";
require_once("./includes/header.php");
include_once("./object/posts.php");
$postObject = new Post($con);
if ($myRank !== 'Admin') {
    die("You do not have permission to access.");
}
?>

<body>
    <div class="body">
        <header class="header">
            <div class="logo">
                <a href="./index.php"><img class="img" src="./lib/images/cdlncd.png" /></a>
                <form method="get">
                    <div class="search-group">
                        <input class="search" type="text" name="find" placeholder="Tìm kiếm" />
                        <button type="submit" name="go" class="search-btn"><img
                                src="./lib/images/search_icon.png"></button>
                    </div>
                    <?php
                    if (isset($_GET['go'])) {
                        $content = $_GET['find'];
                        echo "<meta http-equiv='refresh' content='0;url=./search.php?search-content=" . $content . "&type=0&search' />";
                    }
                    ?>
                </form>
            </div>
            <nav id="menu">
                <div>
                    <marquee behavior="scroll" direction="left">
                        Cuộc Đời Là Những Chuyến Đi
                    </marquee>
                </div>
                <?php
                if (!isset($_SESSION['userID'])) {
                    echo "<ul><li class='effect gr-i-m ef open-login' id='login'>Đăng nhập</li>";
                    echo "<li class='effect gr-i-m ef open-reg' id='reg'>Đăng ký</li>
                        </ul>";
                }
                ?>
            </nav>
            <div class="menu-toggle">
                <div>
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </div>
        </header>
        <div class="full-s-menu" id="full-menu">
            <nav id="item">
                <ul><?php
                    if (!isset($_SESSION['userID'])) {
                        echo "<li class='log'>
                        <a class='open-login'>Đăng nhập</a></li>
                        <li class='log'>
                        <a class='open-reg'>Đăng ký</a></li>";
                    }
                    ?>
                    <li>
                        <a href="./index.php">Trang chủ</a>
                    </li>
                    <li>
                        <a href="./profile.php">Hồ sơ cá nhân</a>
                    </li>
                    <?php
                    if (isset($_SESSION['userID'])) {
                        $user_id = $_SESSION['userID'];
                        $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                        $re = $con->query($sql)->fetch_assoc();
                        if ($re['chucvu'] === 'Admin') {
                            echo "<li>
                            <a href='./admin.php#qltv' id='members'>Quản lý thành viên</a></li><li>
                            <a href='./admin.php#qlbv' id='posts'>Quản lý bài viết</a></li>";
                        }
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['userID'])) {
                        echo "
                            <li>
                                <a href='index.php?logout'>Đăng xuất</a></li>";
                    }
                    if (isset($_GET['logout']) && isset($_SESSION['userID'])) {
                        unset($_SESSION['userID']);
                        echo "<script>sessionStorage.removeItem('uid');
                        window.location.href = './';</script>";
                    }
                    ?>
                </ul>
            </nav>
        </div>

        <div class="main d-block">

            <div class="content w-100" id="qltv">
                <div>
                    <h3 class="ms-2">Quản lý thành viên</h3>
                    <form action="" method="get">
                        <div class="row">
                            <div class="form-group col-5 ms-2">
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
                                    $f_user = isset($_GET['find-user']) ? $_GET['f-user'] : '';
                                    $f = $my_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name = '$f_user' AND user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE user_name = '$f_user' AND chucvu <> 'Admin'");
                                    $notf = $my_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE  chucvu <> 'Admin'");
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
                                        if ($myRank === 'Admin') {
                                            if (isset($_POST['checkbox'])) {
                                                $cbarr = $_POST['checkbox'];
                                                foreach ($cbarr as $id) {
                                                    if ($userObj->deleteUser($id)) {
                                                        echo "<meta http-equiv='refresh' content='0'>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if (isset($_POST['save']) && isset($_SESSION['userID'])) {
                                        if ($myRank === 'Admin') {
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
                            <caption>

                                <span class='my-auto'>
                                    <input type="checkbox" id='checkBoxAll' class="invisible">
                                    <label for="checkBoxAll" class="checkAll">Chọn tất cả</label>
                                </span>
                                <button type="submit" class="btn-success btn ms-2" name="save">Lưu</button>
                                <button type="submit" class="btn-danger btn ms-2" name="del">Xoá</button>
                            </caption>
                        </form>
                    </table>
                </div>
            </div>


            <div class="content w-100" id="qlbv">
                <div>
                    <h3 class="ms-2">Quản lý bài viết</h3>
                    <form action="" method="get">
                        <div class="row">
                            <div class="form-group col-5 ms-2">
                                <input class="form-control f-sm" type="text" name="f-post" placeholder="Tiêu đề">
                            </div>
                            <button type="submit" class="btn btn-success" name="find-post">Tìm</button>
                        </div>
                    </form>
                    <table class="table table-hover">
                        <form action="" method="post">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung</th>
                                    <th>Người đăng</th>
                                    <th>Nhóm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="form-group">
                                    <?php
                                    $user_id = $my_id;
                                    $f_post = isset($_GET['f-post']) ? $_GET['f-post'] : "";
                                    $f = $postObject->likePost($f_post);
                                    $notf = $con->query("SELECT * FROM posts");
                                    $re = isset($_GET['find-post']) ? $f : $notf;

                                    if ($re->num_rows > 0) {
                                        while ($row = $re->fetch_assoc()) {
                                            $bac = $row['nhom'] == 'Bắc' ? 'selected' : '';
                                            $trung = $row['nhom'] == 'Trung' ? 'selected' : '';
                                            $nam = $row['nhom'] == 'Nam' ? 'selected' : '';
                                            echo "<tr>
                                                    <td><input type='checkbox' name='check[]' value='" . $row['post_id'] . "'></td>
                                                    <td>" . $row['post_id'] . "</td>
                                                    <td><input class='form-control f-sm' type='text' name='title[]' value='" . $row['title'] . "' /></td>
                                                    <td><textarea class='form-control f-sm' name='content[]'/>" . $row['content'] . "</textarea></td>
                                                    <td>" . $row['user_name'] . "</td>
                                                    <td><select class='form-control f-sm mb-1' name='group[]'>
                                                    <option value='Bắc' " . $bac . ">Bắc</option>
                                                    <option value='Trung' " . $trung . ">Trung</option>
                                                    <option value='Nam' " . $nam . ">Nam</option>
                                                    </select></td>
                                                    </tr>";
                                        }
                                    }
                                    if (isset($_POST['del-post']) && isset($_SESSION['userID'])) {
                                        if ($myRank === 'Admin') {
                                            if (isset($_POST['check'])) {
                                                $carr = $_POST['check'];
                                                foreach ($carr as $post) {
                                                    if ($postObject->deletePost($post)) {
                                                        echo "<meta http-equiv='refresh' content='0'>";
                                                    } else {
                                                        echo $con->error;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if (isset($_POST['save-post']) && isset($_SESSION['userID'])) {
                                        if ($myRank === 'Admin') {
                                            if (isset($_POST['check'])) {
                                                $carr = $_POST['check'];
                                                $post = $con->query("SELECT * FROM posts");
                                                $checked = array();
                                                $pos = array();
                                                if ($post->num_rows > 0) {
                                                    $dem = 0;
                                                    while ($row = $post->fetch_assoc()) {
                                                        for ($j = 0; $j < count($carr); $j++) {
                                                            if ($carr[$j] === $row['post_id']) {
                                                                $checked[$dem] = 'true';
                                                            }
                                                        }
                                                        if (!isset($checked[$dem]))
                                                            $checked[$dem] = 'false';
                                                        $dem++;
                                                    }
                                                }
                                                $update = array($_POST['check'], $_POST['title'], $_POST['content'], $_POST['group']);

                                                $dem2 = 0;
                                                for ($i = 0; $i < count($checked); $i++) {
                                                    if ($checked[$i] == "true") {
                                                        $update[0][$i] = $carr[$dem2];
                                                        $p_id = $update[0][$i];
                                                        $title = $update[1][$i];
                                                        $content = $update[2][$i];
                                                        $nhom = $update[3][$i];

                                                        $resuilt = $postObject->updatePost($p_id, $title, $content, $nhom);
                                                        if ($resuilt) {
                                                            echo "<meta http-equiv='refresh' content='0'>";
                                                        }
                                                        echo $con->error;
                                                        $dem2++;
                                                    }
                                                }
                                                // for ($i = 0; $i < count($update); $i++) {
                                                //     for ($j = 0; $j < count($update[$i]); $j++) {
                                                //         echo $update[$i][$j] . "|";
                                                //     }
                                                //     echo "<br>";
                                                // }
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </tbody>
                            <caption>
                                <span class='my-auto'>
                                    <input type="checkbox" id='checkAll' class="invisible">
                                    <label for="checkAll" class="checkAll">Chọn tất cả</label>
                                </span>
                                <button type="submit" class="btn-success btn ms-2" name="save-post">Lưu</button>
                                <button type="submit" class="btn-danger btn ms-2" name="del-post">Xoá</button>
                            </caption>
                        </form>
                    </table>
                </div>
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