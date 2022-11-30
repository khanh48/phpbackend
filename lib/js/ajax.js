
function like(id, isPost, userName, toUser) {
    var tagID = isPost ? "p" + id : "c" + id;
    var tagLike = isPost ? "pl" + id : "cl" + id;
    $.ajax({
        type: 'POST',
        url: 'like.php',
        data: {
            "id": id,
            "isPost": isPost,
            "userName": userName,
            "to": toUser
        },
        success: function (respone) {
            var data = JSON.parse(respone)
            $('#' + tagID).text(data.count);
            $classLiked = "fas-liked";
            $tagLikeSelected = $('#' + tagLike);
            if (data.status) {
                if (!$tagLikeSelected.hasClass($classLiked))
                    $tagLikeSelected.addClass($classLiked);
                sendLike(userName, toUser, data.notify)
            } else {
                $('#' + tagLike).removeClass($classLiked);
            }
        }
    })

}

$(document).ready(function () {
    var url = new URL(location.href);
    if (url.pathname.match(/^.*[/]post.php.*$/)) {
        var id = url.searchParams.get("id");
        $.ajax({
            type: 'POST',
            url: './includes/comments.php',
            data: {
                id: id,
                page: url.searchParams.get("page")
            },
            success: function (respone) {
                $('#cmt').html(respone);
            }
        })

        $("#sendCmt").submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: './includes/comments.php',
                data: {
                    id: id,
                    comment: $('#cmtContent').val()
                },
                success: function (respone) {
                    $('#cmt').html(respone);
                    $('#cmtContent').val("")
                }
            })
        })
    } else {
        $.ajax({
            type: 'GET',
            url: './includes/posts.php',
            data: {
                page: url.searchParams.get("page")
            },
            success: function (respone) {
                $('#listPosts').html(respone);
            }
        })
    }
})

