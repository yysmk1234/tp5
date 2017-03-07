<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\project.html";i:1488872192;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>项目</title>
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
          <form style="padding-top: 50px">
              <label>请输入项目名称：</label>
              <input type="text" name="project_name" class="form-control" style="margin-bottom: 20px">

              <button class="btn btn-info" type="button">添加</button>
              <button class="btn btn-danger" type="button">删除</button>

          </form>

       <ul class="list-group" style="margin-top: 50px">
           <?php if(count($project_name)==0): ?>
           <li class="list-group-item">暂时没有项目</li>
           <?php else: if(is_array($project_name) || $project_name instanceof \think\Collection || $project_name instanceof \think\Paginator): $i = 0; $__LIST__ = $project_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pname): $mod = ($i % 2 );++$i;?>
           <li class="list-group-item"><?php echo $pname['project_name']; ?></li>
           <?php endforeach; endif; else: echo "" ;endif; endif; ?>

       </ul>


      </div>

</body>
<script>
    (function () {
        $('.btn-info').click(function () {
            var data = $('form').serialize();
            $.ajax({
                url: "<?php echo url('demo/hello/addproject'); ?>",
                type:"POST",
                data:data,
                dataType:"json",
                success:function (data) {
                    console.log(data.sta);
                    if (data.sta == 1){
                        tips.success("添加成功！！");
                        setTimeout(function () {
                            window.location.reload();
                        },3000)
                    }
                }
            });
        });
        $('.list-group-item').click(function () {
            var project_name = $(this).text();
            $.cookie("project_name",project_name);
            window.location.assign("<?php echo url('demo/hello/group'); ?>");
//            alert($.cookie("project_name"));
        });
    })()
</script>
</html>