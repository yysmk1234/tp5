<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\group.html";i:1492074669;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>分组</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
    <script src="../../../../public/static/cookie/jquery.cookie.js"></script>
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

            </ul>
        </div>

    </div>
</nav>
<div class="container">
    <label style="margin-top: 50px">项目:<?php echo $project_n; ?></label>
    <form style="padding-top: 50px" >
        <label>请输入组别名称：</label>
        <input type="text" name="project_name" class="form-control" style="margin-bottom: 20px">

        <button class="btn btn-info" type="button">添加</button>
        <button class="btn btn-danger" type="button">删除</button>
    </form>
    <ul class="list-group" style="margin-top: 50px">
        <?php if(count($group_name)==0): ?>
        <li class="list-group-item">暂时没有分组</li>
        <?php else: if(is_array($group_name) || $group_name instanceof \think\Collection || $group_name instanceof \think\Paginator): $i = 0; $__LIST__ = $group_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gname): $mod = ($i % 2 );++$i;?>
        <li class="list-group-item" data="<?php echo $gname['group_name']; ?>">
            <label style="width: 93%"><?php echo $gname['group_name']; ?></label>
            <button type="button" class="btn btn-danger project_delete">删除</button>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>

    </ul>


</div>

</body>
<script>
    (function () {
//        alert($.cookie("project_name"));
        $('.btn-info').click(function () {
            var data = $('form').serialize();
            $.ajax({
                url: "<?php echo url('demo/hello/addgroup'); ?>",
                type:"POST",
                data:data,
                dataType:"json",
                success:function (data) {
                    console.log(data.sta);
                    if (data.sta == 1){
                        tips.success("添加成功");
                        setTimeout(function () {
                            window.location.reload();
                        },3000)
                    }
                }
            });
        });
        $('.list-group-item').click(function () {
            var group_name = $(this).attr('data');
            $.cookie("group_name",group_name);
            $.cookie("project_id",<?php echo $project_id; ?>);
            window.location.assign("<?php echo url('demo/hello/sortnew'); ?>");
        });
        $('.project_delete').click(function (e) {
            var that = $(this);
            layer.confirm('确定要删除组？'+'<br />'+'删除组后组内的所有数据都会被删除！！',{
                btn:['确定','取消']},function () {
                var project_name  = {
                    p_n:that.parent().attr('data')
                };
                $.ajax({
                    url:"<?php echo url('demo/hello/del_project'); ?>",
                    type:"POST",
                    data:project_name,
//                datatype:"JSON",
                    success:function (data) {
//                        console.log(data);
                        if (data == 1){
                            tips.success('删除成功')
                        }
                    }
                })
            });
            e.stopPropagation();
        });
    })()
</script>
</html>