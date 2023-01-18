<?php
require("./connect.php");

$result = $con->query('SELECT COUNT(mabaiviet) AS total FROM baiviet');
$row = $result->fetch_assoc();
$total_records = $row['total'];

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;

$total_page = ceil($total_records / $limit);

if ($current_page > $total_page) {
    $current_page = $total_page;
} else if (
    $current_page
    < 1
) {
    $current_page = 1;
}
$start = ($current_page - 1) * $limit;
if ($current_page > 0)
    $start = ($current_page - 1) * $limit;
else
    $start = 0;

$re = $con->query("SELECT * FROM baiviet ORDER BY ngaytao DESC LIMIT $start, $limit");
if ($re->num_rows > 0) {
    $j = 0;
    while ($row = $re->fetch_assoc()) {
        $loggedin = !$logged && $j == 0 ? "mt-0" : "";
        $j++;
        $username = $row['taikhoan'];
        $post = $row['mabaiviet'];
        $poster = $con->query("SELECT * FROM nguoidung WHERE taikhoan = '$username'")->fetch_assoc();
        $result_cmt = $con->query("SELECT COUNT(mabinhluan) AS total FROM binhluan WHERE mabaiviet = '$post'")->fetch_assoc();
        $result_like = $con->query("SELECT COUNT(maluotthich) AS total_like FROM luotthich WHERE mabaiviet = '$post' AND loai = true")->fetch_assoc();
        $total_cmt = $result_cmt['total'] > 0 ? $result_cmt['total'] : '';
        $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
        $liked = $con->query("SELECT COUNT(maluotthich) AS liked FROM luotthich WHERE mabaiviet = '$post' AND taikhoan = '$my_id'")->fetch_assoc();
        $is_liked = '';
        if ($liked["liked"] > 0)
            $is_liked = "fas-liked";

        echo "<div class='content rm'>
                <div class='d-flex justify-content-between $loggedin'>
                    <div class=' c-header'>
                        <span>
                        <a class='name' href='./profile?user=" . $poster['taikhoan'] . "'>
                        <img class='avt' src='" . $poster['anhdaidien'] . "' alt='avatar'></a></span>
                        <div class='c-name'>
                            <span><a class='name' href='./profile?user=" . $poster["taikhoan"] . "'>" . $poster['hoten'] . "</a>
                                <div class='time'><small class='text-secondary'>" . getTime($row['ngaytao']) . "</small>
                                </div>
                            </span>
                        </div>
                    </div>";
        if ($myRank === "Admin" || $my_id === $poster['taikhoan']) {
            echo "<button name='delete-notification' class='btn-close py-1 px-3'
            value='a' data-bs-toggle='modal' data-bs-target='#delete-post' onclick=\"deletePost($post)\"></button>";
        }
        echo "</div>
                <div>
                    <div class='title'>
                        <div class='name'>" . $row['nhom'] . "</div><span>></span>
                        <div class='name'>" . $row['tieude'] . "</div>
                    </div>
                </div>
                <div class='c-body'>
                " . $row['noidung'] . "
                </div>
                <div class='m-0 hide wh' style='text-align: end;'><span class='read-more'></span></div>";

        $images = $con->query("SELECT * FROM hinhanh WHERE `loai` = 'post' AND mabaiviet = " . $row['mabaiviet']);
        if ($images->num_rows > 0) {
            echo "<div id='forpost" . $row['mabaiviet'] . "' class='carousel slide mt-1' data-bs-ride='carousel'>
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
                    <button class='carousel-control-prev' type='button' data-bs-target='#forpost" . $row['mabaiviet'] . "' data-bs-slide='prev'>
                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                        <span class='visually-hidden'>Previous</span>
                    </button>
                    <button class='carousel-control-next' type='button' data-bs-target='#forpost" . $row['mabaiviet'] . "' data-bs-slide='next'>
                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                        <span class='visually-hidden'>Next</span>
                    </button>
                    </div> ";
        }

        echo " <hr class='m-0'>
            <div class='interactive p-1 m-0'>
                <button class='like p-1' onclick=\" like(" . $row['mabaiviet'] . ",true,'" . $my_id . "', '" . $poster['taikhoan'] . "');\">
                    <i class='fas fa-heart action " . $is_liked . "' id='pl" . $row['mabaiviet'] . "'></i>
                    <span class='count-like' id='p" . $row['mabaiviet'] . "'>" . $total_like . "</span>
                </button>
                <button class='comment p-1' onclick=\" window.location.href='./post.php?id=" . $row['mabaiviet'] . "'\">
            <i class='fas fa-comment action'></i>
            <span class='count-comment'><a href='./post.php'></a>" . $total_cmt . "</span>

            </svg>
            </button>
            <button class='share p-1'><i class='fas fa-share action'></i><span class='count-share'></span>
            </button>
            </div>
        </div>";
    }
    echo "<script>loadReadMore()</script>";
    if ($total_records > 10) {
        echo "<div class='content'>
            <div class='page'>
                <div>";
        if ($current_page > 1 && $total_page > 1) {
            echo "<span class='page-item'><a href='index.php?page=" . ($current_page - 1) . "'>
                            <</a></span>";
        }

        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $current_page) {
                echo "<span class='cur-page'>" . $i . "</span>";
            } else {
                echo "<span class='page-item'><a href='index.php?page=" . $i . "'>" . $i . "</a></span>";
            }
        }
        if ($current_page < $total_page && $total_page > 1) {
            echo "<span class='page-item'><a href='index.php?page=" . ($current_page + 1) . "'>></a></span>";
        }
        echo "</div>
            </div>
        </div>";
    }
}