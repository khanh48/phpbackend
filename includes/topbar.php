<header class="header">
    <div class="logo">
        <a href="./index.php"><img class="img" src="./lib/images/cdlncd.png" /></a>
        <form method="get">
            <div class="search-group">
                <input class="search" type="text" name="find" placeholder="Tìm kiếm" />
                <button type="submit" name="go" class="search-btn"><img src="./lib/images/search_icon.png"></button>
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
            echo "<ul><li class='effect gr-i-m ef open-login' id='login' data-bs-toggle='modal' data-bs-target='#modal-login'>Đăng nhập</li>";
            echo "<li class='effect gr-i-m ef open-reg' id='reg' data-bs-toggle='modal' data-bs-target='#modal-reg'>Đăng ký</li>
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
                        <a class='open-login' data-bs-toggle='modal' data-bs-target='#modal-login'>Đăng nhập</a></li>
                        <li class='log'>
                        <a class='open-reg data-bs-toggle='modal' data-bs-target='#modal-reg'>Đăng ký</a></li>";
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
                            <a href='./admin.php'>Quản lý</a></li>";
                }
            }
            ?>
            <li>
                <a href="./weather.html">Thời tiết</a>
            </li>
            <?php
            if (isset($_SESSION['userID'])) {
                echo "
                            <li>
                                <a href='index.php?logout'>Đăng xuất</a></li>";
            }
            if (isset($_GET['logout']) && isset($_SESSION['userID'])) {
                unset($_SESSION['userID']);
                header('Location: ./index.php');
            }
            ?>

        </ul>
    </nav>
</div>
<?php
if (!isset($_SESSION['userID'])) {
    include("loginform.php");

    if (isset($_POST['reg'])) {
        $fname = isset($_POST['fullName']) ? $_POST['fullName'] : "";
        $uname = isset($_POST['userName']) ? $_POST['userName'] : "";
        $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : "";
        $tel = isset($_POST['tel']) ? $_POST['tel'] : "";
        $sql = "INSERT INTO users(user_name, pass, hoten, sodienthoai) VALUES('$uname', '$pwd', '$fname', '$tel')";

        if ($con->query($sql)) {
            echo "<script type='text/javascript'>window.alert('Đăng ký thành công.');</script>";
        } else {
            echo "<script type='text/javascript'>window.alert('Tài khoản đã tồn tại.');</script>";
        }
    }

    // if (isset($_POST['log'])) {
    //     $uname = isset($_POST['userNameLog']) ? $_POST['userNameLog'] : "";
    //     $pwd = isset($_POST['pwdLog']) ? $_POST['pwdLog'] : "";
    //     $sql = "SELECT * FROM users WHERE user_name = '$uname'";
    //     $row = $con->query($sql)->fetch_assoc();

    //     if (isset($row['user_name']) && $row['user_name'] === $uname && $row['pass'] === $pwd) {
    //         $_SESSION['userID'] = $uname;
    //         echo "<meta http-equiv='refresh' content='0'>";
    //     } else {
    //         echo "<script type='text/javascript'>window.alert('Sai tài khoản hoặc mật khẩu.');</script>";
    //     }
    // }
}

?>