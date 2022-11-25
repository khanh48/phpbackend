var websocket = new WebSocket("ws://localhost:8002/socket.php");

websocket.onopen = function () {
    console.log("connected");
}
websocket.onmessage = function (event) {
    var Data = JSON.parse(event.data);
    if (Data.type === "request") {
        var accepted = {
            type: "accepted",
            user: sessionStorage.getItem("uid")
        }
        websocket.send(JSON.stringify(accepted));
    }
    else {
        $(".notify").prepend("<a href='" + Data.notify.url + "'><div class='notify-content unread'>" + Data.notify.msg + "</div></a>");
        $(".notify").addClass("newNotify")
    }

};
websocket.onerror = function (event) {
    console.log("Problem due to some Error");
};

websocket.onclose = function (event) {
    console.log("Connection Closed");
};
function stop() {
    this.event.preventDefault()
}
function sendm(from, to, notify) {
    var messageJSON = {
        from: from,
        to: to,
        notify: notify
    };
    websocket.send(JSON.stringify(messageJSON));
};

function sendcm(from, to, milliseconds, msg, id) {
    var messageJSON = {
        from: from,
        to: to,
        notify:
        {
            msg: msg,
            id: id,
            url: "./post.php?id=" + id + "&r=" + milliseconds
        }
    };

    websocket.onopen = function () {
        websocket.send(JSON.stringify(messageJSON));
    }
    websocket.onmessage = function () {
        location.href = "./post.php?id=" + id;
    }
};
