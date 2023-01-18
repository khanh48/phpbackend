<?php
require("./includes/connect.php");
include("./includes/header.php");

if (isset($_GET['delete-notification']) && $logged) {
    $con->query("DELETE FROM thongbao WHERE mathongbao = " . $_GET['delete-notification']);
}
if (isset($_POST['make-as-read']) && $logged) {
    $con->query("UPDATE thongbao SET trangthai = 1 WHERE nguoinhan = '$my_id'");
}
if (isset($_POST['delete-notifications'])) {
    $con->query("DELETE FROM thongbao WHERE trangthai = 1 AND nguoinhan = '$my_id'");
}
?>

<body>
    <div class="body"></div>
    <?php
    include("./includes/topbar.php");
    ?>
    <div class="main">
        <?php
        include("./includes/barleft.php");
        ?>
        <div class="right">
            <div class="content mb-3">
                <form method="post">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <button name="make-as-read" class="nav-link invisible-button">
                                Đánh dấu tất cả là đã đọc
                            </button>
                        </li>
                        <li class="nav-item">
                            <button name="delete-notifications" class="nav-link invisible-button">
                                Xóa tất cả thông báo đã đọc
                            </button>
                        </li>
                    </ul>
                </form>
            </div>

            <div class="content">
                <form method="get">
                    <?php
                    $notifications = $con->query("SELECT * FROM thongbao WHERE nguoinhan = '$my_id' ORDER BY ngaytao DESC");
                    if ($notifications->num_rows > 0) {
                        while ($row = $notifications->fetch_assoc()) {
                    ?>

                    <div class="alert alert-dismissible py-0 my-0">
                        <button name="delete-notification" class="btn-close py-1"
                            value="<?php echo $row['mathongbao']; ?>"></button>
                        <a href="<?php echo $row['url'] . "&r=" . $row['mathongbao']; ?>">
                            <div class="notification d-flex justify-content-between">
                                <span
                                    class="<?php echo $row['trangthai'] ? "" : "unread"; ?>"><?php echo $row['noidung']; ?></span>
                                <small class="text-secondary"><?php echo getTime($row['ngaytao']); ?></small>
                            </div>
                        </a>
                    </div>

                    <?php
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <footer id="ft">
        <div class="top animated"></div>
        <div class="bot">
            <div>Run For Your Life</div>

        </div>
    </footer>
</body>

</html>