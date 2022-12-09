<header class="header">
    <div class="logo">
        <a href="./index"><img class="img" src="./lib/images/cdlncd.png" /></a>
        <form method="get">
            <div class="search-group">
                <input class="search" type="text" name="find" placeholder="Tìm kiếm" />
                <button type="submit" name="go" class="search-btn"><img src="./lib/images/search_icon.png"></button>
            </div>
            <?php
            if (isset($_GET['go'])) {
                $content = $_GET['find'];
                echo "<meta http-equiv='refresh' content='0;url=./search?search-content=" . $content . "&type=0&search' />";
            }
            ?>
        </form>
    </div>
    <nav id="menu">
        <div>
            <marquee behavior="scroll" direction="left">
                <!-- Cuộc Đời Là Những Chuyến Đi -->
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
            if (!isset($_SESSION['userID'])) { ?>
            <li class='log'>
                <a class='open-login' data-bs-toggle='modal' data-bs-target='#modal-login'>Đăng nhập</a>
            </li>
            <li class='log'>
                <a class='open-reg' data-bs-toggle='modal' data-bs-target='#modal-reg'>Đăng ký</a>
            </li> <?php } ?>

            <li>
                <a href="./index.php">Trang chủ</a>
            </li>

            <?php
            if (isset($_SESSION['userID'])) {
                $user_id = $_SESSION['userID'];
                $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                $re = $con->query($sql)->fetch_assoc();
                echo "<li>
                    <a href='./profile?user=$my_id'>Hồ sơ cá nhân</a>
                </li><li class='log'>
                <a href='./notification'>Thông báo</a>
            </li>";

                if ($re['chucvu'] === 'Admin') {
                    echo "<li><a href='./admin.php'>Quản lý</a></li>";
                }
            }
            ?>
            <li>
                <a href="./weather.html">Thời tiết</a>
            </li>
            <?php
            if (isset($_SESSION['userID'])) {
                echo "<li>
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
<?php
if (!isset($_SESSION['userID'])) {
    include("loginform.php");
}

?>