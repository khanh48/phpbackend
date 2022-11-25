
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
                sendm(userName, toUser, data.notify)
            } else {
                $('#' + tagLike).removeClass($classLiked);
            }
        }
    })

}
function login() {
    this.event.preventDefault();

    $.ajax({
        type: 'POST',
        url: './includes/login.php',
        data: {
            "username": $("#user-name-log").val(),
            "pass": $("#pwd-log").val()
        },
        success: function (respone) {
            var result = JSON.parse(respone);

            if (result.message === "failed") {
                $('#err1-log').text("Sai tên tài khoản hoặc mật khẩu.")
            }
            else if (result.message === "success") {
                $('#err1-log').text("")
                location.reload();
            }
        }
    })
    return false;
}

$(document).ready(function () {
    $(".manager").load("./includes/member_manager.php");
    $("a").click(function () {
        option = $(this).attr("id");
        $.ajax({
            type: "GET",
            url: "./includes/manager.php",
            data: { "target": option },
            success: function (data) {
                $(".manager").load(data);
            }
        })
    })
})