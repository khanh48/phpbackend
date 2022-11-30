<?php
require("./connect.php");

$result = $con->query('SELECT COUNT(post_id) AS total FROM posts');
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

$re = $con->query("SELECT * FROM posts ORDER BY date DESC LIMIT $start, $limit");
if ($re->num_rows > 0) {
    while ($row = $re->fetch_assoc()) {
        $username = $row['user_name'];
        $post = $row['post_id'];
        $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();
        $result_cmt = $con->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$post'")->fetch_assoc();
        $result_like = $con->query("SELECT COUNT(like_id) AS total_like FROM likes WHERE post_id = '$post' AND is_post = true")->fetch_assoc();
        $total_cmt = $result_cmt['total'] > 0 ? $result_cmt['total'] : '';
        $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE post_id = '$post' AND user_name = '$my_id'")->fetch_assoc();
        $is_liked = '';
        if ($liked["liked"] > 0)
            $is_liked = "fas-liked";
        echo "<div class='content'>
        <div>
            <div class=' c-header'>
                <span>
                    <img class='avt' src='" . $poster['avatar'] . "'></span>
                                <div class='c-name'><span>
                                        <div class='name'>" . $poster['hoten'] . "</div>
                                        <div class='time'><small class='text-secondary'>" . getTime($row['date']) . "</small></div>
                                    </span></div>
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
                        <div class='m-0' style='text-align: end;'><span class='read-more'></span></div>
                        <hr class='m-0'>
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
    if ($total_records > 10) {
        echo "<div class='content'>
            <div class='page'>
                <div>";
        if ($current_page > 1 && $total_page > 1) {
            echo "<span class='page-item'><a href='index.php?page=" . ($current_page - 1) . "'>
                            << /a></span>";
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