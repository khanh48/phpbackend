<?php
define('HOST_NAME', "localhost");
define('PORT', "8002");
$null = NULL;
$acceptedClient = array();

require_once("handler.php");
$Handler = new Handler();
//D:\xampp\htdocs\websocket\php-socket.php
$socketResource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socketResource, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socketResource, HOST_NAME, PORT);
socket_listen($socketResource);

$clientSocketArray = array($socketResource);
while (true) {
    $newSocketArray = $clientSocketArray;
    socket_select($newSocketArray, $null, $null, 0, 10);

    if (in_array($socketResource, $newSocketArray)) {
        $newSocket = socket_accept($socketResource);
        $clientSocketArray[] = $newSocket;

        $header = socket_read($newSocket, 1024);
        $Handler->doHandshake($header, $newSocket, HOST_NAME, PORT);

        $Handler->sendTo($newSocket, $Handler->seal(json_encode(array("type" => "request"))));

        $newSocketIndex = array_search($socketResource, $newSocketArray);
        unset($newSocketArray[$newSocketIndex]);
    }

    foreach ($newSocketArray as $newSocketArrayResource) {
        while (socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1) {

            $socketMessage = $Handler->unseal($socketData);
            $messageObj = json_decode($socketMessage);
            if (isset($messageObj->type) && $messageObj->type === "accepted")
                $acceptedClient[$messageObj->user] = $newSocketArrayResource;


            if ($messageObj != null && isset($messageObj->from) && $messageObj->to) {
                if ($messageObj->from !== $messageObj->to) {
                    $chat_box_message = $Handler->newNotification($messageObj->notify, $messageObj->to);
                    if (isset($acceptedClient[$messageObj->to]))
                        $Handler->sendTo($acceptedClient[$messageObj->to], $chat_box_message);
                }
            }
            break 2;
        }

        $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
        if ($socketData === false) {
            $newSocketIndex = array_search($newSocketArrayResource, $clientSocketArray);
            unset($clientSocketArray[$newSocketIndex]);
        }
    }
}
socket_close($socketResource);