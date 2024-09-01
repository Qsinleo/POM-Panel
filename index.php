<?php
include_once "search.php";
include_once "config.php";
function unicodeDecode($unicode_str)
{
    $json = '{"str":"' . $unicode_str . '"}';
    $arr = json_decode($json, true);
    if (empty($arr)) return '';
    return $arr['str'];
}
$reallen = count($posts);
//判断GET是否有效
$reqst = key_exists("se-start", $_REQUEST) ? (int)$_REQUEST["se-start"] : 0;
$reqle = key_exists("se-length", $_REQUEST) ? (int)$_REQUEST["se-length"] : $reallen;
if (key_exists("se-start", $_REQUEST) && key_exists("se-length", $_REQUEST)) {
    if ((int)$_REQUEST["se-length"] > 0 && $reallen > (int)$_REQUEST["se-start"] && (int)$_REQUEST["se-start"] >= 0) {
        $posts = array_slice($posts, $_REQUEST["se-start"], $_REQUEST["se-length"]);
    }
}
// 获取IP
function getip()
{
    static $ip = '';
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] as $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}
// 获取设备
function equipmentSystem()
{
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    if (stristr($agent, 'iPad')) {
        $fb_fs = "iPad";
    } else if (preg_match('/Android (([0-9_.]{1,3})+)/i', $agent, $version)) {
        $fb_fs = "手机(Android " . $version[1] . ")";
    } else if (stristr($agent, 'Linux')) {
        $fb_fs = "电脑(Linux)";
    } else if (preg_match('/iPhone OS (([0-9_.]{1,3})+)/i', $agent, $version)) {
        $fb_fs = "手机(iPhone " . $version[1] . ")";
    } else if (preg_match('/Mac OS X (([0-9_.]{1,5})+)/i', $agent, $version)) {
        $fb_fs = "电脑(OS X " . $version[1] . ")";
    } else if (preg_match('/unix/i', $agent)) {
        $fb_fs = "Unix";
    } else if (preg_match('/windows/i', $agent)) {
        $fb_fs = "电脑(Windows)";
    } else {
        $fb_fs = "Unknown";
    }
    return $fb_fs;
}
makelog("访客（IP为" . getip() . "用" . equipmentSystem() . "访问了面板！");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="main.css" />
    <title>Document</title>
</head>

<body>
    <div id="main">
        <?php
        if (key_exists("logined", $_SESSION) && $_SESSION["logined"]) {
        ?>
            <!-- 内容窗口 -->
            <div class="popup" id="popup">
                <div style="font-size: large;" class="title-bar">
                    <button onclick="closepop()" class="close">x</button>
                    <button onclick="minipop()" class="mini" id="minipop">-</button>
                    <!-- 还有按钮 -->
                    <b id="title">内容</b>
                </div>
                <div id="pop-inner-cont">

                </div>
                <div id="pop-inner-form">
                    <form action="proceed.php" method="post">
                        <input type="hidden" name="alter-with" value="1" id="alter-with" />
                        <b>数据评级</b><br /><label>评级：<input type="range" min="1" max="9" step="1" name="level" oninput="document.getElementById('level-section').innerText = this.value;" /><span id="level-section">
                                <script>
                                    document.write(document.getElementsByName("level")[0].value)
                                </script>
                            </span></label><br />
                        <textarea name="excomm" maxlength="50" placeholder="请输入额外留言（最多50字）" id="excomm"></textarea>
                        <input type="submit" value="确认评级" />
                    </form>
                </div>
            </div>
            <h1>舆论动向监视面板<sub>POMP</sub><br /><small><b style="color: #464646; font-size: small;">已登录</b>
                    <a href="analyze.php" target="_blank" style="font-size:large;font-weight:bold;">管理</a></small>

            </h1>
            <div class="general-view">
                <span id="total">统共帖子<?php echo count($posts) ?>个</span>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="get">
                    <label>从第<input type="number" name="se-start" min="0" max="<?php if ($reallen > 0) {
                                                                                    echo $reallen - 1;
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>" value="<?php echo $reqst ?>" />
                        (<b id="real"></b>)个开始，
                    </label>
                    <label>搜索<input type="number" name="se-length" min="1" max="<?php if ($reallen < 1) {
                                                                                    echo 0;
                                                                                } elseif ($reallen < 50) {
                                                                                    echo $reallen - 1;
                                                                                } else {
                                                                                    echo 50;
                                                                                }; ?>" value="<?php echo $reqle ?>" />/<span id="maxlen">
                            <script>
                                document.write(document.getElementsByName("se-length")[0].max)
                            </script>
                        </span>个</label>
                    <input type="submit" value="查询" class="btn" />
                </form>
            </div>
            <table>
                <caption>舆论数据</caption>
                <tr>
                    <th>ID</th>
                    <th>头像</th>
                    <th>昵称</th>
                    <th>发布内容</th>
                    <th>发布时间</th>
                    <th>来自</th>
                    <th>点赞</th>
                    <th>评论</th>
                    <th>转发</th>
                    <th>评级Lv.</th>
                    <th>注释</th>
                    <th>操作</th>
                </tr>
                <?php
                foreach ($posts as $each) {
                    echo "<tr>";
                    foreach ($each as $a => $eeaa) {
                        echo "<td>";
                        if ($a == "avator") {
                            echo "<img src=\"https://image.baidu.com/search/down?url=" . $eeaa . "\" alt=\"头像\" title=\"头像\" width=\"100\" height=\"100\"/></td>";
                        } elseif ($a == "postcont") {
                            echo "<button onclick=\"showpop(this,'内容')\">展示<div style=\"display:none;\">" . unicodeDecode($eeaa) . "</div></button>";
                        } else {
                            echo $eeaa;
                        }
                    }
                    echo "</td><td><button onclick=\"showpop(this,'评级')\">评级<span style=\"display:none;\">" . $each["id"] . "</span><div style=\"display:none;\">" . $each["excomm"] . "</div></button></td></tr>";
                }
                ?>
            </table>
            <button onclick="scrollToTop()" class="topbut">↑</button>
            <script>
                var save = document.getElementById("popup").innerHTML; //声明保存HTML的变量
                function showpop(obj, title) {
                    reshowpop();
                    document.getElementById("popup").style.display = "unset";
                    if (title == "内容") {
                        document.getElementById("pop-inner-form").style.display = "none";
                        document.getElementById("pop-inner-cont").style.display = "unset";
                        document.getElementById("minipop").style.display = "unset";
                        document.getElementById("pop-inner-cont").innerText = obj.children[0].innerText;
                    } else if (title == "评级") {
                        document.getElementById("pop-inner-cont").style.display = "none";
                        document.getElementById("pop-inner-form").style.display = "unset";
                        document.getElementById("minipop").style.display = "none";
                        document.getElementById("alter-with").value = obj.children[0].innerText;
                        document.getElementById("excomm").value = obj.children[1].innerText;
                    }
                    document.getElementById("title").innerText = title;
                    save = document.getElementById("popup").innerHTML;

                }

                function closepop() {
                    document.getElementById("popup").style.display = "none";
                }

                function reshowpop() {
                    var pop = document.getElementById("popup");
                    pop.style.bottom = "unset";
                    pop.style.left = "unset";
                    pop.style.left = "15%";
                    pop.style.top = "15%";
                    pop.style.width = "70%";
                    pop.style.height = "70%";
                }

                function minipop() {
                    var pop = document.getElementById("popup");
                    save = pop.innerHTML;
                    pop.style.left = "unset";
                    pop.style.top = "unset";
                    pop.style.width = "25%";
                    pop.style.height = "30px";
                    pop.style.bottom = "2px";
                    pop.style.left = "0";
                    pop.innerHTML = '<b>小窗</b><button onclick="closepop()" class="close" style="margin-left:20px;margin-right:5px;">x</button><button onclick="reshowpop();document.getElementById(\'popup\').innerHTML = save;" class="reshow" style="font-size:large;background-color:green;color:white;margin-top:2px;margin-right:20px;width:50px;border-radius:10%">^</button>';
                }


                const scrollToTop = () => {
                    let sTop = document.documentElement.scrollTop || document.body.scrollTop
                    if (sTop > 1) {
                        window.requestAnimationFrame(scrollToTop)
                        window.scrollTo(0, sTop - sTop / 5)
                    }
                }
                var starter = document.getElementsByName("se-start")[0];
                var lengther = document.getElementsByName("se-length")[0];
                starter.oninput = lengther.oninput = (() => {
                    if (starter.max == 0) {
                        document.getElementById("real").innerText = "0";
                    } else {
                        document.getElementById("real").innerText = parseInt(starter.value) + 1;
                    }
                    if (starter.value > starter.max) {
                        starter.value = starter.max;
                    } else if (starter.value < starter.min) {
                        starter.value = starter.min;
                    }
                    if (lengther.value > lengther.max - starter.value) {
                        lengther.value = lengther.max - starter.value;
                    } else if (lengther.value < lengther.min) {
                        lengther.value = lengther.min;
                    }
                    document.getElementById("maxlen").innerText = lengther.max - starter.value;
                })
            </script>
        <?php
        } else {
            $error = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($_REQUEST["username"] === $username && $_REQUEST["password"] === $password) {
                    $_SESSION["logined"] = true;
                    echo "<script>document.write('加载中……');location.reload();</script>";
                } else {
                    $error = "登录失败（有参数填写错误）";
                }
            }
        ?>
            <h1>面板登录</h1>
            <hr />
            <!-- 登录表单 -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" target="_self" method="post">
                <table>
                    <tr>
                        <th><label style="font-size: large;">用户名：</th>
                        <td><input name="username" style="width: 90%;font-size: large;" /></td></label>
                    </tr>
                    <th><label style="font-size: large;">密码：</th>
                    <td><input name="password" type="password" style="width: 90%;font-size: large;" /></td></label>
                    <tr>
                </table>

                <input type="submit" value="登录" class="login-btn btn" />
            </form>
            <div class="help" style="text-align: center;"><span style="color: red;"><b><?php echo $error; ?></b></span>
                <button onclick="alert('初始密码和密码的更改可在README.md找到');">忘记密码？</button>
            </div>
        <?php
        }
        ?>
        <footer>
            <b>VERSION 1.0 </b>
            <small>
                Made by <a href="https://bi2nb9o3.xyz" target="_blank">Bi2nb9o3</a> and <a href="https://leom.fun" target="_blank">Leost</a>.Thanks for using.
            </small>
        </footer>
    </div>
</body>

</html>