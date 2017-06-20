<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"C:\xampp\htdocs\tp5/application/demo\view\hello\projectcount.html";i:1495779506;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目选择</title>
    <link href="../../../../../tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
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
                <li><a href="<?php echo url('demo/hello/projectcount'); ?>">数据计算</a></li>
            </ul>
        </div>

    </div>
</nav>
    <div class="container">
        <h2>选择项目</h2>
        <ul class="list-group" style="margin-top: 50px">
            <?php if(count($project)==0): ?>
            <li class="list-group-item">暂时没有分组</li>
            <?php else: if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pname): $mod = ($i % 2 );++$i;?>
            <li class="list-group-item" data="<?php echo $pname['project_id']; ?>">
                <label style="width: 93%"><?php echo $pname['project_name']; ?></label>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>

        </ul>
    </div>
</body>
<script>
    (function () {
        $('.container ul li').on("click",function () {
            var projcet_id =  $(this).attr('data');
            $.cookie("project_id",projcet_id);
            window.location.assign("<?php echo url('demo/hello/count'); ?>");
        })
    })()
</script>
</html>