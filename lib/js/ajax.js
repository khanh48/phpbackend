
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
    $('#register').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "./includes/register.php",
            data: $(this).serialize(),
            success: function (respone) {
                data = JSON.parse(respone);
                if (data.type === "name_size") {
                    $("#err").text(data.message);
                    $("#err1").text("");
                    $("#err2").text("");
                    $("#err3").text("");
                } else if (data.type === "user_format") {
                    $("#err").text("");
                    $("#err1").text(data.message);
                    $("#err2").text("");
                    $("#err3").text("");
                } else if (data.type === "pass_format") {
                    $("#err").text("");
                    $("#err1").text("");
                    $("#err3").text(data.message);
                    $("#err2").text("");
                } else if (data.type === "repass_not_same") {
                    $("#err").text("");
                    $("#err1").text("");
                    $("#err3").text("");
                    $("#err2").text(data.message);
                } else {
                    $("#err").text("");
                    $("#err1").text("");
                    $("#err2").text("");
                    $("#err3").text("");

                    $('#headerToast').text("Thông báo");
                    $('#toastMessage').text(data.message);
                    const toast = new bootstrap.Toast($('#liveToast'))
                    toast.show()
                }
            }
        })
    });
})

