<?php
require "./includes/connect.php";
require_once("./includes/header.php");
?>

<body>
    <div class="body">
        <?php include('./includes/topbar.php') ?>

        <div class="main">

            <?php include('./includes/barleft.php') ?>
            <div class="right">
                <div class="content">
                    <?php
                    if (isset($_SESSION['userID'])) {
                        include("./includes/formpost.php");
                        if (isset($_POST['post'])) {
                            $title = isset($_POST['tieude']) ? $_POST['tieude'] : '';
                            $content = isset($_POST['noidung']) ? $_POST['noidung'] : '';
                            $group = isset($_POST['nhom']) ? $_POST['nhom'] : 'Bắc';
                            $user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
                            $files = isset($_FILES['uploadImg']) ? $_FILES['uploadImg'] : NULL;
                            $milliseconds = intval(microtime(true) * 1000);

                            $target_dir = "./lib/images/" . $my_id . "/";
                            if (!file_exists($target_dir)) {
                                mkdir($target_dir, 0777, true);
                            }
                            $totalFile = count(array_filter($files['name']));
                            for ($i = 0; $i < $totalFile; $i++) {
                                $check = true;
                                $target_file = $target_dir . str_replace(" ", "_", basename($files["name"][$i]));

                                if (getimagesize($files["tmp_name"][$i]) !== false)
                                    $check = true;
                                else
                                    $check = false;

                                if ($check) {
                                    if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
                                        $con->query("INSERT INTO images(`owner`,`type`,`url`,`post_id`) VALUES('$my_id', 'post', '$target_file', $milliseconds)");
                                    }
                                }
                            }
                            $sql = "INSERT INTO posts(post_id, title, content, user_name, nhom) VALUES($milliseconds, '$title', '$content', '$user_id','$group')";
                            if ($con->query($sql)) {
                                echo "<meta http-equiv='refresh' content='0,url=./post.php?id=" . $milliseconds . "'>";
                            } else {
                                echo "Lỗi: " . $con->error;
                            }
                        }
                    }
                    ?>
                </div>
                <div id="listPosts"></div>

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
        <script src="./lib/js/filecustom.js"></script>
    </div>
</body>

</html>