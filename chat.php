<?php
// chat.php
session_start();
$chatFile = 'chat.txt';
// 处理消息发送
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = htmlspecialchars($_POST['message']);
        $username = $_SESSION['username'] ?? 'Guest'; // 默认用户名为 Guest
        $time = date('H:i');
        $newMessage = "<p><strong>$username</strong> ($time): $message</p>";
        file_put_contents($chatFile, $newMessage, FILE_APPEND | LOCK_EX);
        // 输出新消息
        echo $newMessage;
        exit;
    }
    if (isset($_POST['username'])) {
        $_SESSION['username'] = htmlspecialchars($_POST['username']);
        exit;
    }
}

// 读取聊天记录
if (file_exists($chatFile)) {
    $chatContent = file_get_contents($chatFile);
} else {
    $chatContent = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>罗泓宇的简易聊天室</title>
<style>
    body {
        background-color: #8ccfff;
        font-family: Arial, sans-serif;
        padding: 20px; /* 增加整体页面的内边距 */
    }
    h2 {
        margin-top: 0; /* 清除标题默认的上边距 */
        margin-bottom: 20px; /* 增加标题与内容之间的下边距 */
    }
    #chatbox {
        width: 1000px;
        height: 600px;
        background-color: #c2dcef;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        padding: 10px;
        margin-bottom: 20px; /* 增加聊天框与输入框之间的下边距 */
        /* 让聊天框可以滚动 */
        overflow-y: scroll;
    }
    #message {
        width: 300px;
        margin-bottom: 10px; /* 增加输入框与按钮之间的下边距 */
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #87CEEB;
        padding: 20px;
        border-radius: 10px; /* 设置容器的圆角 */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    input[type="text"], button {
        padding: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px; /* 设置输入框和按钮的圆角 */
    }
    button {
        background-color: #16b777;
        color: white;
        cursor: pointer;
    }
    button:hover {
        background-color: #006400;
    }
</style>
</head>
<body>
    <h2>罗泓宇的简易聊天室</h2>
    <div id="chatbox">
        <?php echo $chatContent; ?>
    </div>
    <form id="chatform">
        <input type="text" id="username" placeholder="输入用户名...">
        <input type="text" id="message" placeholder="输入消息...">
        <button type="submit">发送</button>
    </form>

    <script>
        document.getElementById('chatform').addEventListener('submit', function(e) {
            e.preventDefault();
            var usernameInput = document.getElementById('username').value.trim();
            var messageInput = document.getElementById('message').value.trim();
            if (usernameInput === '' || messageInput === '') {
                return;
            }

            // 设置用户名
            var setUsername = new XMLHttpRequest();
            setUsername.open('POST', 'chat1.php', true);
            setUsername.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            setUsername.onreadystatechange = function() {
                if (setUsername.readyState === 4 && setUsername.status === 200) {
                    var sendMessage = new XMLHttpRequest();
                    sendMessage.open('POST', 'chat1.php', true);
                    sendMessage.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                    sendMessage.onreadystatechange=function(){
                        if(sendMessage.readyState===4&&sendMessage.status===200) {
                            document.getElementById('chatbox').innerHTML+=sendMessage.responseText;
                            document.getElementById('message').value = '';
                            document.getElementById('chatbox').scrollTop=document.getElementById('chatbox').scrollHeight;
                        }
                    };
                    sendMessage.send('message=' + encodeURIComponent(messageInput));
                }
            };
            setUsername.send('username=' + encodeURIComponent(usernameInput));
        });
    </script>
<script language="javascript" type="text/javascript" src="https://cdn.staticfile.org/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
var a_idx = 0;
jQuery(document).ready(function($) {
$("body").click(function(e) {
var a = new Array("富强", "民主", "文明", "和谐", "自由", "平等", "公正" ,"法治", "爱国", "敬业", "诚信", "友善");
var $i = $("<span/>").text(a[a_idx]);
a_idx = (a_idx + 1) % a.length;
var x = e.pageX,
y = e.pageY;
$i.css({
"z-index": 999999999999999999999999999999999999999999999999999999999999999999999,
"top": y - 20,
"left": x,
"position": "absolute",
"font-weight": "bold",
"color": "#ff6651"
});
$("body").append($i);
$i.animate({
"top": y - 180,
"opacity": 0
},
1500,
function() {
$i.remove();
});
});
});
</script>
</body>
</html>
