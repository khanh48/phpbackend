<div class="left">
    <div class="info">
        <div class="info-top"><a href="./profile.php">Hồ sơ cá nhân</a></div>
        <div class="thongbao">
            <div class="tb">Thông báo</div>
            <div class="notify">
                <?php
                $getNotify = $con->query("SELECT * FROM notify WHERE to_user = '$my_id' ORDER BY notify_id DESC");
                if ($getNotify->num_rows > 0) {
                    while ($notify = $getNotify->fetch_assoc()) {
                        echo "<a href='" . $notify["url"] . "&r=" . $notify["notify_id"] . "'><div class='notify-content " . ($notify["readed"] ? "" : "unread") . "'>
                        " . $notify["msg"] . "
                        </div></a>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="group">
        <div class="name group-name">Bắc</div>
        <div class="pl-1">
            <?php
            $sql = "SELECT * FROM posts WHERE nhom = 'Bắc' ORDER BY post_id DESC LIMIT 0,3";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div><a href='./post.php?id=" . $row['post_id'] . "'>" . $row['title'] . "</a>
                                </div>";
                }
            }
            ?>
        </div>
    </div>
    <div class="group">
        <div class="name group-name">Trung</div>
        <div class="pl-1">
            <?php
            $sql = "SELECT * FROM posts WHERE nhom = 'Trung' ORDER BY post_id DESC LIMIT 0,3";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div><a href='./post.php?id=" . $row['post_id'] . "'>" . $row['title'] . "</a>
                                </div>";
                }
            }
            ?>
        </div>
    </div>
    <div class="group">
        <div class="name group-name">Nam</div>
        <div class="pl-1">
            <?php
            $sql = "SELECT * FROM posts WHERE nhom = 'Nam' ORDER BY post_id DESC LIMIT 0,3";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div><a href='./post.php?id=" . $row['post_id'] . "'>" . $row['title'] . "</a>
                                </div>";
                }
            }
            ?>
        </div>
    </div>
</div>