<?php
require_once "./includes/connect.php";
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
    $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
    $re = $con->query($sql)->fetch_assoc();
    if ($re['chucvu'] !== 'Admin') {
        echo "<script>
        window.stop()</script>";
    }
} else {
    echo "<script>
    window.stop()</script>";
}
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
    <script src="./lib/js/jquery.min.js"></script>
    <script src="./lib/js/main.js"></script>
    <script src="./lib/js/ajax.js"></script>
</head>

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
                        header('Location: ./index.php');
                    }
                    ?>
                </ul>
            </nav>
        </div>

        <div class="main d-block manager">
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