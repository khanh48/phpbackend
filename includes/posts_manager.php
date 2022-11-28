<?php require_once("./connect.php") ?>
<div class="content w-100" id="qlbv">
    <div>
        <h3 class="ml-2">Quản lý bài viết</h3>
        <form action="" method="get">
            <div class="row">
                <div class="form-group col-5 ml-2">
                    <input class="form-control f-sm" type="text" name="f-post" placeholder="Post ID">
                </div>
                <button type="submit" class="btn btn-success" name="find-post">Tìm</button>
            </div>
        </form>
        <table class="table table-hover">
            <form action="" method="post">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Người đăng</th>
                        <th>Nhóm</th>
                    </tr>
                </thead>
                <tbody>
                    <div class="form-group">
                        <?php
                        $user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
                        $f_post = isset($_GET['find-post']) ? $_GET['f-post'] : '';
                        $f = $con->query("SELECT * FROM posts WHERE post_id = '$f_post'");
                        $notf = $con->query("SELECT * FROM posts");
                        $re = isset($_GET['find-post']) ? $f : $notf;

                        if (($f_post == '' ? $notf : $re)->num_rows > 0) {
                            while ($row = ($f_post == '' ? $notf : $re)->fetch_assoc()) {
                                $bac = $row['nhom'] == 'Bắc' ? 'selected' : '';
                                $trung = $row['nhom'] == 'Trung' ? 'selected' : '';
                                $nam = $row['nhom'] == 'Nam' ? 'selected' : '';
                                echo "<tr>
                                        <td><input type='checkbox' name='check[]' value='" . $row['post_id'] . "'></td>
                                        <td>" . $row['post_id'] . "</td>
                                        <td><input class='form-control f-sm' type='text' name='title[]' value='" . $row['title'] . "' /></td>
                                        <td><textarea class='form-control f-sm' name='content[]'/>" . $row['content'] . "</textarea></td>
                                        <td>" . $row['user_name'] . "</td>
                                        <td><select class='form-control f-sm mb-1' name='group[]'>
                                        <option value='Bắc' " . $bac . ">Bắc</option>
                                        <option value='Trung' " . $trung . ">Trung</option>
                                        <option value='Nam' " . $nam . ">Nam</option>
                                        </select></td>
                                    </tr>";
                            }
                        }
                        if (isset($_POST['del-post']) && isset($_SESSION['userID'])) {
                            $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                            $re = $con->query($sql)->fetch_assoc();
                            if ($re['chucvu'] === 'Admin') {
                                if (isset($_POST['check'])) {
                                    $carr = $_POST['check'];
                                    foreach ($carr as $post) {
                                        $allCmt = $con->query("SELECT * FROM comments WHERE comments.post_id = '$post'");
                                        if ($allCmt->num_rows > 0) {
                                            while ($row = $allCmt->fetch_assoc()) {
                                                $con->query("DELETE FROM likes WHERE cmt_id = '" . $row['comment_id'] . "'");
                                            }
                                        }
                                        $con->query("DELETE FROM comments WHERE comments.post_id = '$post'");
                                        $con->query("DELETE FROM likes WHERE post_id = '" . $post . "'");
                                        $sql = $con->query("DELETE FROM posts WHERE post_id = '$post'");
                                        if ($sql) {
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        } else {
                                            echo $con->error;
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($_POST['save-post']) && isset($_SESSION['userID'])) {
                            $sql = "SELECT * FROM users WHERE user_name = '$user_id'";
                            $re = $con->query($sql)->fetch_assoc();
                            if ($re['chucvu'] === 'Admin') {
                                if (isset($_POST['check'])) {
                                    $carr = $_POST['check'];
                                    $post = $con->query("SELECT * FROM posts");
                                    $checked = array();
                                    $pos = array();
                                    if ($post->num_rows > 0) {
                                        $dem = 0;
                                        while ($row = $post->fetch_assoc()) {
                                            for ($j = 0; $j < count($carr); $j++) {
                                                if ($carr[$j] === $row['post_id']) {
                                                    $checked[$dem] = 'true';
                                                }
                                            }
                                            if (!isset($checked[$dem]))
                                                $checked[$dem] = 'false';
                                            $dem++;
                                        }
                                    }
                                    $update = array($_POST['check'], $_POST['title'], $_POST['content'], $_POST['group']);

                                    $dem2 = 0;
                                    for ($i = 0; $i < count($checked); $i++) {
                                        if ($checked[$i] == "true") {
                                            $update[0][$i] = $carr[$dem2];
                                            $p_id = $update[0][$i];
                                            $title = $update[1][$i];
                                            $content = $update[2][$i];
                                            $nhom = $update[3][$i];

                                            $resuilt = $con->query("UPDATE posts SET title ='$title', content = '$content', nhom = '$nhom' WHERE post_id = '$p_id'");
                                            if ($resuilt) {
                                                echo "<meta http-equiv='refresh' content='0'>";
                                            }
                                            echo $con->error;
                                            $dem2++;
                                        }
                                    }
                                    // for ($i = 0; $i < count($update); $i++) {
                                    //     for ($j = 0; $j < count($update[$i]); $j++) {
                                    //         echo $update[$i][$j] . "|";
                                    //     }
                                    //     echo "<br>";
                                    // }
                                }
                            }
                        }
                        ?>
                    </div>
                </tbody>
                <caption><button type="submit" class="btn-success btn ml-2" name="save-post">Lưu</button>
                    <button type="submit" class="btn-danger btn ml-2" name="del-post">Xoá</button>
                </caption>
            </form>
        </table>
    </div>
</div>