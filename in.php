<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="./lib/js/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Document</title>

</head>

<body>
    <div id="show"></div>
    <form id="f" method="post">
        <input type="text" name="msg" id="msg">
        <input type="submit" value="Enter">
    </form>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#f').submit(function(e) {
            console.log(e)
            e.preventDefault();
            // console.log($('#msg').val());
            // $('#show').load('test.php')

            // $.post("test.php", { msg: $('#msg').val() }, function (data, status, xhr) {
            //     $('#show').html(data)
            // })

            $.ajax({
                type: 'POST',
                url: 'test.php',
                data: {
                    msg: $('#msg').val()
                },
                success: function(data) {
                    $('#show').html(data)
                }
            })
        })
    })
    </script>
</body>

</html>