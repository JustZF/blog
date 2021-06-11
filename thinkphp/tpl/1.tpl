{__NOLAYOUT__}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>跳转提示</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <style type="text/css">

    </style>
</head>
<body>
<div class="layui-row" style="padding-top: 150px;">
    <div class="layui-col-md6 layui-col-md-offset3 layui-col-xs10 layui-col-xs-offset1">
        <?php switch ($code) {?>
        <?php case 1:?>
        <blockquote class="layui-elem-quote">
        <h2><?php echo(strip_tags($msg));?></h2>
        <p class="jump">
        页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
        </p>
        </blockquote>
        <?php break;?>

        <?php case 0:?>
        <blockquote class="layui-elem-quote" style="border-left: 5px solid rgb(178,87,85);">
        <h2><?php echo(strip_tags($msg));?></h2>
        <p class="jump">
        页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
        </p>
        </blockquote>
        <?php break;?>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    (function () {
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function () {
            var time = --wait.innerHTML;
            if (time <= 0) {
                location.href = href;
                clearInterval(interval);
            }
            ;
        }, 1000);
    })();
</script>
</body>
</html>