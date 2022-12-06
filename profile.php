<?php
require "./includes/connect.php";
require_once("./includes/header.php");
?>

<body>
    <?php include('./includes/topbar.php') ?>

    <div class="main">
        <?php include('./includes/barleft.php') ?>
        <div class="right">
            <?php
            if (isset($_GET["user"])) {
                $userID = $_GET["user"];
                $sql = "SELECT * FROM users WHERE user_name = '$userID'";
                $post = "SELECT COUNT(post_id) AS total_post FROM posts WHERE user_name = '$userID'";
                $re_post = $con->query($post);
                $re = $con->query($sql);
                $originAvt = '';
                if ($re->num_rows > 0) {
                    $row = $re->fetch_assoc();
                    $originAvt = $row['avatar'];
                    echo "<div class='content'>
                    <div class='mt-0 ms-2'>
                    <div class=' c-header'>
                        <img class='avt-profile rounded-circle' src='".$originAvt."'>
                        <div>
                        <div class='name'>" . $row['hoten'] . "</div>";
                    if ($re_post->num_rows > 0) {
                        $r = $re_post->fetch_assoc();
                        echo "<p class='ps-2 mb-0'>Bài viết: " . $r['total_post'] . "</p>";
                    }
                    echo "<p class='ps-2 mb-0'>Giới tính: " . $row['gioitinh'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Ngày sinh: " . $row['ngaysinh'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Số điện thoại: " . $row['sodienthoai'] . "</p>";
                    echo "<p class='ps-2 mb-0'>Sở thích: " . $row['sothich'] . "</p>";

                    echo "</div></div>
                        </div></div>";
                    $nam = $row['gioitinh'] == 'Nam' ? 'selected' : '';
                    $nu = $row['gioitinh'] == 'Nữ' ? 'selected' : '';

                    if($userID === $my_id){
                    echo"<div class='content'><div><form method='post'  enctype='multipart/form-data'>
                        <div class='form-group p-1'>
                        <table>
                        <tr>
                            <td>
                            <label for='avt'>Ảnh đại diện:</label></td>
                            <td>
                            <input type='file' class='form-control f-sm mb-1' name='avt' id='avt' accept='image/jpeg,image/jpg,image/png' /></td>
                        </tr>
                            <tr>
                                <td>
                                <label for='hoten'>Họ tên:</label></td>
                                <td>
                                <input type='text' class='form-control f-sm mb-1' name='hoten' id='hoten' value='" . $row['hoten'] . "' /></td>
                            </tr>
                            <tr>
                                <td><label for='gioitinh'>Giới tính:</label></td>
                                <td><select id='gioitinh' class='form-control f-sm mb-1' name='gioitinh'>
                                <option value=''>Khác</option>
                                <option value='Nam' " . $nam . ">Nam</option>
                                <option value='Nữ' " . $nu . ">Nữ</option>
                                </select></td>
                            </tr>
                            <tr>
                                <td><label for='ngaysinh'>Ngày sinh:</label></td>
                                <td><input type='date' class='form-control f-sm mb-1' name='ngaysinh' id='ngaysinh' value='" . $row['ngaysinh'] . "' /></td>
                            </tr>
                            <tr>
                                <td>
                                <label for='sdt'>Số điện thoại:</label></td>
                                <td>
                                <input type='text' class='form-control f-sm mb-1' name='sdt' id='sdt' value='" . $row['sodienthoai'] . "' /></td>
                            </tr>
                            <tr>
                                <td><label for='sothich'>Sở thích:</label></td>
                                <td><textarea class='form-control f-sm' name='sothich' id='sothich' />" . $row['sothich'] . "</textarea></td>
                            </tr>
                        </table>
                        <button class='f-sm btn btn-success my-2' name='save'>Lưu</button>
                        </div></form></div></div>";
                    }else{
                        $posts = $con->query("SELECT * FROM posts WHERE user_name='$userID' ORDER BY date DESC");
                        if($posts->num_rows > 0){
                            while($row = $posts->fetch_assoc()){
                                $post = $row['post_id'];
                                $poster = $con->query("SELECT * FROM users WHERE user_name = '$userID'")->fetch_assoc();
                                $result_cmt = $con->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$post'")->fetch_assoc();
                                $result_like = $con->query("SELECT COUNT(like_id) AS total_like FROM likes WHERE post_id = '$post' AND is_post = true")->fetch_assoc();
                                $total_cmt = $result_cmt['total'] > 0 ? $result_cmt['total'] : '';
                                $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
                                $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE post_id = '$post' AND user_name = '$my_id'")->fetch_assoc();
                                $is_liked = '';
                                if ($liked["liked"] > 0)
                                    $is_liked = "fas-liked";
                                echo "<div class='content rm'>
                                        <div >
                                            <div class=' c-header'>
                                                <span><img class='avt' src='" . $poster['avatar'] . "'></span>
                                                <div class='c-name'>
                                                    <span><div class='name'>" . $poster['hoten'] . "</div>
                                                        <div class='time'><small class='text-secondary'>" . getTime($row['date']) . "</small>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class='title'>
                                                <div class='name'>" . $row['nhom'] . "</div><span>></span>
                                                <div class='name'>" . $row['title'] . "</div>
                                            </div>
                                        </div>
                                        <div class='c-body'>
                                        " . $row['content'] . "
                                        </div>
                                        <div class='m-0 hide wh' style='text-align: end;'><span class='read-more'></span></div>";
                        
                                $images = $con->query("SELECT * FROM images WHERE `type` = 'post' AND post_id = " . $row['post_id']);
                                if ($images->num_rows > 0) {
                                    echo "<div id='forpost" . $row['post_id'] . "' class='carousel slide mt-1' data-bs-ride='carousel'>
                                        <div class='carousel-inner '>";
                                    $i = 0;
                                    while ($img = $images->fetch_assoc()) {
                                        $active = $i == 0 ? "active" : "";
                                        $i++;
                                        echo "<div class='carousel-item $active'>
                                                <img src='" . $img['url'] . "' class='d-block w-100 postImg' alt='...'>
                                                </div>";
                                    }
                                    echo "</div>
                                            <button class='carousel-control-prev' type='button' data-bs-target='#forpost" . $row['post_id'] . "' data-bs-slide='prev'>
                                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                                <span class='visually-hidden'>Previous</span>
                                            </button>
                                            <button class='carousel-control-next' type='button' data-bs-target='#forpost" . $row['post_id'] . "' data-bs-slide='next'>
                                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                                <span class='visually-hidden'>Next</span>
                                            </button>
                                            </div> ";
                                }
                        
                                echo " <hr class='m-0'>
                                    <div class='interactive p-1 m-0'>
                                        <button class='like p-1' onclick=\" like(" . $row['post_id'] . ",true,'" . $my_id . "', '" . $poster['user_name'] . "');\">
                                            <i class='fas fa-heart " . $is_liked . "' id='pl" . $row['post_id'] . "'></i>
                                            <span class='count-like' id='p" . $row['post_id'] . "'>" . $total_like . "</span>
                                        </button>
                                        <button class='comment p-1' onclick=\" window.location.href='./post.php?id=" . $row['post_id'] . "'\">
                                    <i class='fas fa-comment'></i>
                                    <span class='count-comment'><a href='./post.php'></a>" . $total_cmt . "</span>
                        
                                    </svg>
                                    </button>
                                    <button class='share p-1'><i class='fas fa-share'></i><span class='count-share'></span>
                                    </button>
                                    </div>
                                </div>";
                            }
                            echo "<script>loadReadMore()</script>";
                        }
                    }
                }

                if (isset($_POST['save'])) {
                    $ht = $_POST['hoten'];
                    $gt = $_POST['gioitinh'];
                    $ns = $_POST['ngaysinh'] == '' ? 'null' : $_POST['ngaysinh'];
                    $sdt = $_POST['sdt'];
                    $st = $_POST['sothich'];
                    $avt = $originAvt;
                    if($_FILES['avt']['tmp_name'] !== ''){
                        $file = $_FILES['avt'];
                        $target_dir = "./lib/images/" . $my_id . "/";
                        if (!file_exists($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                        $check = true;
                        $avt = $target_dir . str_replace(" ", "_", basename($file["name"]));

                        if (getimagesize($file["tmp_name"]) !== false)
                            $check = true;
                        else
                            $check = false;

                        if ($check) {
                            move_uploaded_file($file["tmp_name"], $avt);
                        }
                    }

                    $sql = $con->query("UPDATE users SET hoten = '$ht', gioitinh = '$gt', ngaysinh = '$ns', sodienthoai = '$sdt', sothich = '$st', avatar = '$avt' WHERE user_name = '$userID'");
                    if ($sql) {
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else echo $con->error;
                }
            }

            ?>
        </div>

    </div>
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <!-- <img src="..." class="rounded me-2" alt="..."> -->
                <strong class="me-auto" id="headerToast"></strong>
                <small id="toastTime"></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
            </div>
        </div>
    </div>

    <footer id="ft">
        <div class="top animated"></div>
        <div class="bot">
            <div>Run For Your Life</div>

        </div>
    </footer>

    <script src="./lib/js/ajax.js"></script>

    </div>
</body>

</html>