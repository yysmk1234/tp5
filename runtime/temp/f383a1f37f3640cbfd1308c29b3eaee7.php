<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"D:\xampp\htdocs\tp5/application/demo\view\hello\upload.html";i:1492239853;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
    <link href="/tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/tp5/public/static/layer/skin/default/layer.css">
    <script src="/tp5/public/static/dist/js/jquery.min.js"></script>
    <script src="/tp5/public/static/dist/js/bootstrap.js"></script>
    <script src="/tp5/public/static/layer/layer.js"></script>
    <script src="/tp5/public/static/layer/layer_m.js"></script>
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
        <form id="form" style="padding: 50px 20px" enctype="multipart/form-data" >
            <label style="padding-top: 20px">请输入测试名称：</label>
            <input type="text" class="form-control" name="test_name">

            <label style="padding-top: 20px">请输入测试次序：</label>
            <input type="text" class="form-control" name="status">

            <label style="padding-top: 20px">请选择被试：</label>
            <select name="tester">
                <?php if(is_array($tester) || $tester instanceof \think\Collection || $tester instanceof \think\Paginator): $i = 0; $__LIST__ = $tester;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tester): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $tester['u_id']; ?>"><?php echo $tester['u_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br/>

            <label style="padding-top: 20px">请选择测试游戏：</label>
            <select name="game">
                <?php if(is_array($game) || $game instanceof \think\Collection || $game instanceof \think\Paginator): $i = 0; $__LIST__ = $game;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$game): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $game['g_id']; ?>"><?php echo $game['g_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br/>

            <label style="padding-top: 20px">请选择index文件：</label>
            <input type="file" name="index">
            <br />

            <label style="padding-top: 20px">请选择tags文件：</label>
            <input type="file" name="tags">
            <br />

            <button id="submit" class="btn btn-info" type="button">提交</button>
            <button id="return" class="btn btn-info" type="button">返回</button>
        </form>

    </div>

</body>
    <script>
        (function () {
            $('button').click(function () {
                var flag = $(this).attr('id');
//                console.log(flag);
                if (flag=='return'){
                    window.location.assign("<?php echo url('demo/hello/testerandgame'); ?>");
                }else if (flag == 'submit'){
                    if ($("input[name='test_name']").val()==''){
                        tips.error("请输入测试名称");
                    }else if ($("input[name='status']").val()==''){
                        tips.error("请输入测试顺序");
                    }else if (($("input[name='index']").val()!='') && ($("input[name='tags']").val()!='')){
//                        alert("success");
                        var load =  tips.wait("文件上传中……");        //打开加载层
                        var data = new FormData(document.getElementById('form'));
//                console.log(data);
                        $.ajax({
                            type:"POST",
                            url:"<?php echo url('demo/hello/upfile'); ?>",
                            data:data,
                            dataType:"json",
                            processData:false,
                            contentType:false,
                            success:function (data) {
//                                console.log(data);
                                if (data.sta == 1 ){
                                    layer.close(load);    //如果服务器返回值，关闭加载层
                                    tips.success("文件上传成功！");
                                    setTimeout(function () {
                                        window.location.reload();
                                    },3000)
                                }
                            }
                        })
                    }else {
                        tips.error("请添加文件");
                    }
                }
            })
        })()
    </script>
</html>