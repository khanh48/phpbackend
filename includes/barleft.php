<div class="left">
    <div class="info">
        <div class="info-top"><a href="./profile.php">Hồ sơ cá nhân</a></div>
        <div class="thongbao">
            <div class="tb">Thông báo</div>
            <div class="notify">
                <div class="notify-content">...
                </div>
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