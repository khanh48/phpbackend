<div class="left">
    <div class="info">
        <a href="./profile.php?user=<?php echo $my_id ?>">
            <div class="info-top">Hồ sơ cá nhân</div>
        </a>
        <div class="thongbao">
            <a href="./notification">
                <div class="tb">Thông báo</div>
            </a>
            <div class="notify">
                <?php
                $getNotify = $con->query("SELECT * FROM notificstions WHERE to_user = '$my_id' ORDER BY date DESC");
                if ($getNotify->num_rows > 0) {
                    while ($notify = $getNotify->fetch_assoc()) {
                        echo "<a href='" . $notify["url"] . "&r=" . $notify["id"] . "'><div class='notify-content " . ($notify["readed"] ? "" : "unread") . "'>
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
        <div class="ps-1">
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
        <div class="ps-1">
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
        <div class="ps-1">
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