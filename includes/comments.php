<?php
require("./connect.php");
include_once(dirname(__DIR__) . "/object/comments.php");
include_once(dirname(__DIR__) . "/object/posts.php");
$postOj = new Post($con);
$commentOj = new Comment($con);


$id = isset($_POST['id']) ? $_POST['id'] : '';
if (isset($_POST['comment'])) {
    $milliseconds = intval(microtime(true) * 1000);
    $comment = $_POST['comment'];
    $post = $con->query("SELECT * FROM posts WHERE post_id = '$id'")->fetch_assoc();
    $poster = isset($post['user_name']) ? $post['user_name'] : '';
    $sql = "INSERT INTO comments(content, user_name, post_id) VALUES('$comment', '$my_id', '$id')";
    if ($con->query($sql)) {
        if ($my_id !== $poster) {
            $getUser = $con->query("SELECT * FROM users WHERE user_name = '$my_id'")->fetch_assoc();
            $fullName = isset($getUser["hoten"]) ? $getUser["hoten"] : "";
            $msg = $fullName . " đã bình luận trong bài viết của bạn.";
            $con->query("INSERT INTO notifications(id, from_user, msg, to_user, url) VALUES($milliseconds,'$my_id', '$msg', '" . $poster . "', './post.php?id=$id')");

            echo "<script>sendcm('$my_id','" . $poster . "', $milliseconds, '" . $msg . "', $id)</script>";
        }
    }
}
$result = $con->query("SELECT COUNT(comment_id) AS total FROM comments WHERE post_id = '$id'");
$row = $result->fetch_assoc();
$total_records = $row['total'];

$current_page = isset($_POST['page']) ? $_POST['page'] : 1;
$limit = 10;

$total_page = ceil($total_records / $limit);

if ($current_page > $total_page) {
    $current_page = $total_page;
} else if ($current_page < 1) {
    $current_page = 1;
}
if ($current_page > 0)
    $start = ($current_page - 1) * $limit;
else
    $start = 0;

$re = $con->query("SELECT * FROM comments WHERE post_id = '$id' ORDER BY date DESC LIMIT $start, $limit");
if ($re->num_rows ? $re->num_rows > 0 : false) {
    while ($row = $re->fetch_assoc()) {
        $username = $row['user_name'];
        $cmt_id = $row["comment_id"];
        $poster = $con->query("SELECT * FROM users WHERE user_name = '$username'")->fetch_assoc();

        $result_like = $con->query("SELECT COUNT(like_id) AS total_like FROM likes WHERE cmt_id = '$cmt_id' AND is_post = false")->fetch_assoc();
        $total_like = $result_like['total_like'] > 0 ? $result_like['total_like'] : '';
        $liked = $con->query("SELECT COUNT(like_id) AS liked FROM likes WHERE cmt_id = '$cmt_id' AND user_name = '$my_id'")->fetch_assoc();
        $is_liked = '';
        if ($liked["liked"] > 0)
            $is_liked = "fas-liked";
?>

<div class='content rm' id='cm<?php echo $cmt_id; ?>'>
    <div class="d-flex justify-content-between">
        <div class=' c-header'>
            <span>
                <a class='name' href='./profile?user=<?php echo $poster['user_name']; ?>'>
                    <img class='avt' src='<?php echo $poster['avatar']; ?>'>
                </a>
            </span>
            <div class='c-name'>
                <span>
                    <a class='name' href='./profile?user=<?php echo $poster['user_name']; ?>'>
                        <?php echo $poster['hoten']; ?>
                    </a>
                    <div class='time'><small class='text-secondary'><?php echo getTime($row['date']); ?></small></div>
                </span>
            </div>
        </div>
        <?php if ($my_id === $poster['user_name'] || $my_id === $postOj->getPost($id)['user_name']) { ?>
        <button name='delete-notification' class='btn-close py-1 px-3' value='a' data-bs-toggle='modal'
            data-bs-target='#delete-cmt' onclick="deleteCmt(<?php echo $cmt_id; ?>)"></button>
        <?php } ?>
    </div>
    <div class='c-body'><?php echo $row['content']; ?>
    </div>
    <div class='m-0 hide wh' style='text-align: end;'>
        <span class='read-more'></span>
    </div>
    <hr class='m-0'>
    <div class='interactive p-1 m-0'>
        <button class='like p-1'
            onclick=" like('<?php echo $cmt_id; ?>', false, '<?php echo $my_id; ?>', '<?php echo $poster['user_name']; ?>');">
            <i class='fas fa-heart action <?php echo $is_liked; ?>' id='cl<?php echo $cmt_id; ?>'></i>
            <span class='count-like' id='c<?php echo $cmt_id; ?>'><?php echo $total_like; ?></span>
        </button>
    </div>
</div>
<?php }
    echo "<script>loadReadMore()</script>";
    if ($total_records > 10) {
        echo "<div class='content'>
                <div class='page'>
                    <div>";
        if ($current_page > 1 && $total_page > 1) {
            echo "<span class='page-item'><a href='post.php?id=" . $id . "&page=" . ($current_page - 1) . "'><</a></span>";
        }

        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $current_page) {
                echo "<span class='cur-page'>" . $i . "</span>";
            } else {
                echo "<span class='page-item'><a href='post.php?id=" . $id . "&page=" . $i . "'>" . $i . "</a></span>";
            }
        }

        if ($current_page < $total_page && $total_page > 1) {
            echo "<span class='page-item'><a href='post.php?id=" . $id . "&page=" . ($current_page + 1) . "'>></a></span>";
        }
        echo "
            </div>
        </div>
    </div>";
    }
}