
function like(id, isPost, userName) {
    var tagID = isPost ? "p" + id : "c" + id;
    var tagLike = isPost ? "pl" + id : "cl" + id;
    $.ajax({
        type: 'POST',
        url: 'like.php',
        data: {
            "id": id,
            "isPost": isPost,
            "userName": userName
        },
        success: function (respone) {
            var data = JSON.parse(respone)
            $('#' + tagID).text(data.count);
            $classLiked = "fas-liked";
            $tagLikeSelected = $('#' + tagLike);
            if (data.status) {
                if (!$tagLikeSelected.hasClass($classLiked))
                    $tagLikeSelected.addClass($classLiked);
            } else {
                $('#' + tagLike).removeClass($classLiked);
            }
        }
    })
}