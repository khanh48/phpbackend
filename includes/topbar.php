<?php
require_once("./includes/connect.php");
if (isset($_GET['logout']) && isset($_SESSION['userID'])) {
    unset($_SESSION['userID']);
    echo "<script>sessionStorage.removeItem('uid');
                window.location.href = './';</script>";
}
?>
<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        
        <div class="logo">
        <a class="navbar-brand mt-2 mt-lg-0" href="./">
            <img src="./lib/images/logo.png" height="50" alt="logo">
        </a><i class="bi bi-bag-fill"></i>
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

            <!-- Icon -->
            <!-- <a class="text-reset me-3" href="#">
                <i class="fas fa-shopping-cart"></i>
            </a> -->

            <?php
            if (!$logged) {
            ?>
            <a class="nav-link log me-2" href="./search">Tìm kiếm</a>
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
                <a class="dropdown" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown"
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
                    <?php if ($myRank === "Admin") { ?>
                    <li>
                        <a class="dropdown-item" href="./admin">Quản lý</a>
                    </li>
                    <?php } ?>
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