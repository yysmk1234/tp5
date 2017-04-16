<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"C:\xampp\htdocs\tp5/application/demo\view\hello\count.html";i:1492327212;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>排序</title>
    <link href="/tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/tp5/public/static/layer/skin/default/layer.css">
    <script src="/tp5/public/static/dist/js/jquery.min.js"></script>
    <script src="/tp5/public/static/dist/js/bootstrap.js"></script>
    <script src="/tp5/public/static/layer/layer.js"></script>
    <script src="/tp5/public/static/layer/layer_m.js"></script>
    <script src="/tp5/public/static/cookie/jquery.cookie.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">文件设置<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url('demo/hello/setattr'); ?>">添加关键字段</a></li>
                        <li><a href="<?php echo url('demo/hello/testerandgame'); ?>">添加被试</a></li>
                        <li><a href="<?php echo url('demo/hello/upload'); ?>">文件上传</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo url('demo/hello/project'); ?>">排序计算</a></li>
                <li><a href="<?php echo url('demo/hello/count'); ?>">数据计算</a></li>
            </ul>
        </div>

    </div>
</nav>
<div class="" style="margin-top: 50px;padding-top: 40px">

    <table class="table table-condensed" style="margin-top: 20px">
        <tr>
            <td>game</td>
            <td>emoi</td>
            <td>scl</td>
            <td>High alpha</td>
            <td>gamma</td>
            <td>emoi_</td>
            <td>scl_</td>
            <td>high_alpha_</td>
            <td>gamma_</td>
        </tr>
        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $data['game']; ?></td>
            <td><?php echo $data['emoi']; ?></td>
            <td><?php echo $data['scl']; ?></td>
            <td><?php echo $data['High_alpha']; ?></td>
            <td><?php echo $data['gamma']; ?></td>
            <td><?php echo $data['emoi_']; ?></td>
            <td><?php echo $data['scl_']; ?></td>
            <td><?php echo $data['Higt_a_']; ?></td>
            <td><?php echo $data['gamma_']; ?></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </table>

</div>
</body>
</html>