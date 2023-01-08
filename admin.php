<?php
require_once "./includes/connect.php";
require_once("./includes/header.php");
include_once("./object/posts.php");
$postObject = new Post($con);
if ($myRank !== 'Admin') {
    die("You do not have permission to access.");
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
            $check = $my_id === 'admin' ? $con->query("SELECT * FROM users WHERE user_name <> 'admin'") : $con->query("SELECT * FROM users WHERE  chucvu <> 'Admin'");
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
            $update = array($_POST['checkbox'], $_POST['hoten'], $_POST['ngaysinh'], $_POST['gioitinh'], $_POST['sdt'], $_POST['chucvu']);

            $dem2 = 0;
            for ($i = 0; $i < count($checked); $i++) {
                if ($checked[$i] == "true") {
                    $update[0][$i] = $cbarr[$dem2];
                    $uname = $update[0][$i];
                    $hoten = $update[1][$i];
                    $ngaysinh = $update[2][$i] == '' ? 'null' : $update[2][$i];
                    $gioitinh = $update[3][$i];
                    $sdt = $update[4][$i];
                    $chucvu = $update[5][$i];
                    
                    $isnull = $con->query("UPDATE users SET hoten ='$hoten', ngaysinh = $ngaysinh, gioitinh = '$gioitinh', sodienthoai = '$sdt', chucvu ='$chucvu' WHERE user_name = '$uname'");
                    $notnull = $con->query("UPDATE users SET hoten ='$hoten', ngaysinh = '$ngaysinh', gioitinh = '$gioitinh', sodienthoai = '$sdt', chucvu ='$chucvu' WHERE user_name = '$uname'");
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

<body>
    <div class="body"><?php
require_once("./includes/connect.php");
if (isset($_GET['logout']) && isset($_SESSION['userID'])) {
    unset($_SESSION['userID']);
    echo "<script>sessionStorage.removeItem('uid');
                window.location.href = './';</script>";
}
?>
<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-sm">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <a class="navbar-brand mt-2 mt-lg-0" href="./">
            <img src="./lib/images/logo.png" height="50" alt="logo">
        </a><i class="bi bi-bag-fill"></i>
        <div class="navbar-collapse collapse">
        <ul class="navbar-nav me-0 mb-sm-0">
            <li class="nav-item"><a href="./admin" class="nav-link">Thành viên</a></li>
            <li class="nav-item"><a href="./admin?post" class="nav-link">Bài viết</a></li>
        </ul>
        <form method="get">
            <div class="ms-2 search-group">
                <input class="search" type="text" name="find" placeholder="Tìm kiếm" />
                <button type="submit" name="go" class="search-btn">
                    <img src="./lib/images/search_icon.png">
                </button>
            </div> 
        </form><?php
                    if (isset($_GET['go'])) {
                        $content = $_GET['find'];
                        echo "<meta http-equiv='refresh' content='0;url=./search.php?search-content=" . $content . "&type=0&search' />";
                    }
                    ?></div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center nav-right">

            <!-- Icon cart
            <a class="text-reset me-3" href="#">
                <i class="fas fa-shopping-cart"></i>
            </a> -->

            <?php
            if (!$logged) {
            ?>
            <a class="nav-link" href="#" data-bs-toggle='modal' data-bs-target='#modal-login'>Đăng nhập</a>
            <?php } else {
            ?>
            <!-- Notifications -->
            <div class="dropdown">
                <a class="me-3 dropdown" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                    aria-expanded="true">

                    <i class="fas fa-bell">
                        <?php
                            $total = $con->query("SELECT count(id) AS total FROM notifications WHERE to_user = '$my_id' AND readed = 'false'")->fetch_assoc()['total'];

                            if ($total > 0) {
                            ?><span
                            class="badge rounded-pill position-absolute top-0 start-100 translate-middle bg-danger"><?php echo $total > 99 ? '99+' : $total; ?></span>
                        <?php
                            }
                            ?>
                    </i>

                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-notify">
                    <?php
                        $sql = $con->query("SELECT * FROM notifications WHERE to_user = '$my_id' ORDER BY date DESC ");
                        if ($sql->num_rows > 0) {
                            while ($row = $sql->fetch_assoc()) {
                        ?>
                    <li>
                        <a class="dropdown-item text-wrap" href="<?php echo $row['url'] . '&r=' . $row['id']; ?>">
                            <p class="small mb-0"><?php echo getTime($row['date']); ?></p>
                            <p class="mb-0 <?php if ($row['readed'] == false) echo 'unread'; ?>">
                                <?php echo $row['msg']; ?></p>
                        </a>
                    </li>
                    <?php
                            }
                        } else {
                            echo "<li><a class='dropdown-item' href='#'>Không có thông báo.</a></li>";
                        }
                        ?>
                </ul>
            </div>
            <!-- Avatar -->
            <div class="dropdown">
                <a class="dropdown" href="#" id="navbarDropdownMenuAvatar" role="button./admin" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="<?php echo $userObj->getUser($my_id)["avatar"]; ?>" class="rounded-circle" height="25"
                        alt="avatar">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <a class="dropdown-item" href="./profile?user=<?php echo $my_id; ?>">Trang cá nhân</a>
                    </li>
                    
                    <li>
                        <a class="dropdown-item" href="./notification">Thông báo</a>
                    </li>
                    <li class="log">
                        <a class="dropdown-item" href="./search">Tìm kiếm</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="./index?logout">Đăng xuất</a>
                    </li>
                </ul>
            </div>
            <?php } ?>
        </div>
        <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<?php
if (!$logged) {
    include("loginform.php");
}
?>
<!-- Navbar -->

        <div class="main d-block">
<?php if(!isset($_GET["post"])){ ?>
            <div class="content w-100" id="qltv">
                <div>
                    <h3 class="ms-2">Quản lý thành viên</h3>
                    <form action="" method="get">
                        <div class="row">
                            <div class="form-group col-5 ms-2">
                                <input class="form-control f-sm" type="text" name="f-user" placeholder="User name">
                            </div>
                            <button type="submit" class="btn btn-success btn-sm" name="find-user">Tìm</button>
                        </div>
                    </form>
                    <table class="table table-striped table-hover">
                        <form action="" method="post">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>Tên User</th>
                                    <th>Họ Tên</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
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
                                        <td><input type='text' class='form-control f-sm mb-1' name='sdt[]' value='" . $row['sodienthoai'] . "' /></td>
                                        
                                        <td><select class='form-control f-sm mb-1' name='chucvu[]'>
                                        <option value='Thành viên' " . $tv . ">Thành viên</option>
                                        <option value='Admin' " . $ad . ">Admin</option>
                                        </select></td>
                                    </tr>";
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
                                <button type="submit" class="btn-success btn ms-2 btn-sm" name="save">Lưu</button>
                                <button type="submit" class="btn-danger btn ms-2 btn-sm" name="del">Xoá</button>
                            </caption>
                        </form>
                    </table>
                </div>
            </div>


            <?php }else{ ?>
            <div class="content w-100" id="qlbv">
                <div>
                    <h3 class="ms-2">Quản lý bài viết</h3>
                    <form action="./admin" method="get">
                        <div class="row">
                            <div class="form-group col-5 ms-2">
                                <input class="form-control f-sm" type="text" name="f-post" placeholder="Tiêu đề">
                            </div>
                            <button type="submit" class="btn btn-success btn-sm" name="post">Tìm</button>
                        </div>
                    </form>
                    <table class="table table-striped table-hover">
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
                                    $f_post = isset($_GET['f-post']) ? $_GET['f-post'] : "";
                                    $f = $postObject->likePost($f_post);
                                    $notf = $con->query("SELECT * FROM posts");
                                    $re = isset($_GET['post']) ? $f : $notf;

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
                                    ?>
                                </div>
                            </tbody>
                            <caption>
                                <span class='my-auto'>
                                    <input type="checkbox" id='checkAll' class="invisible">
                                    <label for="checkAll" class="checkAll">Chọn tất cả</label>
                                </span>
                                <button type="submit" class="btn-success btn ms-2 btn-sm" name="save-post">Lưu</button>
                                <button type="submit" class="btn-danger btn ms-2 btn-sm" name="del-post">Xoá</button>
                            </caption>
                        </form>
                    </table>
                </div>
            </div>

            
            <?php } ?>

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