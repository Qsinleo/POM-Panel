<?php
include_once "avoid_visit.php";
include_once "log.php";
$filename = "status.json";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $writein = [
        "switch" => "off",
        "mode" => $_REQUEST["mode"],
        "interval" => (int)$_REQUEST["interval"],
        "keyword" => $_REQUEST["keyword"]
    ];
    if (key_exists("switch", $_REQUEST)) {
        $writein["switch"] = $_REQUEST["switch"];
    }
    $res = fopen($filename, "w");
    fwrite($res, json_encode($writein, JSON_UNESCAPED_UNICODE));
    makelog("将配置更改为" . json_encode($writein, JSON_UNESCAPED_UNICODE));
}
$res = fopen($filename, "r");
$json = json_decode(fread($res, filesize($filename)), true);
makelog("读取了配置，是" . json_encode($json));
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="main.css" />
</head>

<body>
    <div id="main">
    <h1>爬虫管理</h1>
    <div style="background-color: coral;font-size:x-large;font-weight:bold;text-align:center;border:red 1px solid;border-radius: 5px;margin:15px;">
        欢迎使用Leopillar爬虫！<br />
        <small><i>Welcome to use Leopillar spider!</i></small>
    </div>
    <table class="disinfo">
        <tr>
            <th>爬虫状态</th>
            <th>爬虫模式</th>
            <th>爬虫关键字</th>
            <th>爬虫间隔（分）</th>
        </tr>
        <tr>
            <td id="spiderswitch"><?php echo $json["switch"] ?></td>
            <td id="spidermode"><?php echo $json["mode"] ?></td>
            <td id="spiderkeyword"><?php echo $json["keyword"] ?></td>
            <td id="spiderinterval"><?php echo $json["interval"] ?></td>
        </tr>

    </table>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top: 20px;">
        <table class="changeinfo">
            <thead>
                <th>说明</th>
                <th>更改值</th>
            </thead>
            <tr>
                <th>爬虫状态</th>
                <td><input type="checkbox" name="switch" value="on" /></label></td>
            </tr>
            <tr>
                <th>爬虫模式</th>
                <td>
                    <select name="mode">
                        <option value="hot">热门</option>
                        <option value="new">实时</option>
                        <option value="hotnew">热门+实时</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>爬虫关键字</th>
                <td><input maxlength="10" name="keyword" value="<?php echo $json["keyword"]; ?>" /></span></td>
            </tr>
            <tr>
                <th>爬虫间隔（分）</th>
                <td><input type="range" max="60" min="10" step="5" name="interval" /><span id="interval-section"><?php echo $json["interval"]; ?></span></td>

            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="提交更改" class="btn"/></td>
            </tr>
        </table>
    </form>
    <form action="clearlog.php" method="post" style="margin-top: 20px">
        <a href="run.log" target="_blank" style="font-size: large;">查看运行日志</a>
        <a href="run.log" target="_blank" style="font-size: large;" download="run.log">下载运行日志</a>
        <input type="submit" value="× 清除运行日志" class="btn clearlog" />
    </form>
    <form action="logout.php" method="post" style="margin-top: 20px">
        <a href="index.php" style="font-size: large;font-weight:bold;">← 返回</a>
        <input type="submit" value="登出" class="btn logout"/>
    </form>
    <!-- 爬虫记录 -->
    <table>
        <script>
            document.getElementsByName("interval")[0].value = document.getElementById("interval-section").innerText;
            if (document.getElementById("spiderswitch").innerText == "on") {
                document.getElementById("spiderswitch").innerText = "开启";
                document.getElementsByName("switch")[0].checked = true;
            } else if (document.getElementById("spiderswitch").innerText == "off") {
                document.getElementById("spiderswitch").innerText = "关闭";
                document.getElementsByName("switch")[0].checked = false;
            } else {
                document.getElementById("spiderswitch").innerText = "未知/错误";
                document.getElementsByName("switch")[0].checked = false;
            }
            switch ("<?php echo $json["mode"] ?>") {
                case "hot":
                    document.getElementById("spidermode").innerText = "热门";
                    document.getElementsByName("mode")[0].value = "hot";
                    break;
                case "new":
                    document.getElementById("spidermode").innerText = "实时";
                    document.getElementsByName("mode")[0].value = "new";
                    break;
                case "hotnew":
                    document.getElementById("spidermode").innerText = "热门+实时";
                    document.getElementsByName("mode")[0].value = "hotnew";
                    break;
                default:
                    document.getElementById("spidermode").innerText = "未知";
            }
            document.getElementsByName("interval")[0].oninput = function() {
                document.getElementById("interval-section").innerText = document.getElementsByName("interval")[0].value;
            }
        </script>
    </div>
</body>

</html>