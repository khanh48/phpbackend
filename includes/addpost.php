<?php
require("./connect.php");

if (isset($_POST['tieude']) && isset($_POST['noidung'])) {
    if (isset($_SESSION['userID'])) {
        $title = $_POST['tieude'];
        $content = $_POST['noidung'];
        $group = isset($_POST['nhom']) ? $_POST['nhom'] : 'Bắc';
        $files = isset($_FILES['uploadImg']) ? $_FILES['uploadImg'] : NULL;

        $target_dir = "../lib/images/" . $my_id . "/";
        $totalFile = count($files);
        for ($i = 0; $i < $totalFile; $i++) {
            $check = true;
            $target_file = $target_dir . str_replace(" ", "_", basename($files["name"][$i]),);

            if (getimagesize($files["tmp_name"][$i]) !== false)
                $check = true;
            else
                $check = false;

            if ($check) {
                move_uploaded_file($files["tmp_name"][$i], $target_file);
            }
        }

        // $sql = "INSERT INTO posts(title, content, user_name, nhom) VALUES('$title', '$content', '$user_id','$group')";
        // if ($con->query($sql)) {
        //     echo "<meta http-equiv='refresh' content='3'>";
        // }
    } else {
        echo "<script>
            $('#headerToast').text('Thông báo');
            $('#toastMessage').text('Bạn cần đăng nhập để đăng bài.');
            const toast = new bootstrap.Toast($('#liveToast'))
            toast.show()
        </script>";
    }
}