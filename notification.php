<?php
require("./includes/connect.php");
include("./includes/header.php");

if (isset($_GET['delete-notification']) && $logged) {
    $con->query("DELETE FROM notificstions WHERE id = " . $_GET['delete-notification']);
}
if (isset($_POST['make-as-read']) && $logged) {
    $con->query("UPDATE notificstions SET readed = 1 WHERE to_user = '$my_id'");
}
if (isset($_POST['delete-notifications'])) {
    $con->query("DELETE FROM notificstions WHERE readed = 1 AND to_user = '$my_id'");
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
                    $notifications = $con->query("SELECT * FROM notificstions WHERE to_user = '$my_id' ORDER BY date DESC");
                    if ($notifications->num_rows > 0) {
                        while ($row = $notifications->fetch_assoc()) {
                    ?>

                    <div class="alert alert-dismissible py-0 my-0">
                        <button name="delete-notification" class="btn-close py-1"
                            value="<?php echo $row['id']; ?>"></button>
                        <a href="<?php echo $row['url'] . "&r=" . $row['id']; ?>">
                            <div class="notification d-flex justify-content-between">
                                <span
                                    class="<?php echo $row['readed'] ? "" : "unread"; ?>"><?php echo $row['msg']; ?></span>
                                <small class="text-secondary"><?php echo getTime($row['date']); ?></small>
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