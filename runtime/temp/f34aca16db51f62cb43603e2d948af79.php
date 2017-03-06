<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\testerandgame.html";i:1488443156;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>设置被试和游戏属性</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
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
        <form id="tester">
            <h3>添加被试</h3>

            <label style="padding-top: 20px">请输入被试名字：</label>
            <input class="form-control" type="text" name="tester_name">

            <label style="padding-top: 20px">请选择性别：</label>
            <select name="sex">
                <?php if(is_array($sex) || $sex instanceof \think\Collection || $sex instanceof \think\Paginator): $i = 0; $__LIST__ = $sex;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sex): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $sex['id']; ?>"><?php echo $sex['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择年龄：</label>
            <select name="age_group">
                <?php if(is_array($age_group) || $age_group instanceof \think\Collection || $age_group instanceof \think\Paginator): $i = 0; $__LIST__ = $age_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$age): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $age['id']; ?>"><?php echo $age['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择游戏经历：</label>
            <select name="game_experience">
                <?php if(is_array($game_experience) || $game_experience instanceof \think\Collection || $game_experience instanceof \think\Paginator): $i = 0; $__LIST__ = $game_experience;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$exp): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $exp['id']; ?>"><?php echo $exp['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择游戏年限：</label>
            <select name="game_year">
                <?php if(is_array($game_year) || $game_year instanceof \think\Collection || $game_year instanceof \think\Paginator): $i = 0; $__LIST__ = $game_year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $year['id']; ?>"><?php echo $year['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <button id="tester_btn" type="button" class="btn btn-primary" style="margin-top: 20px">添加</button>

        </form>


        <form id="game">

            <label style="padding-top: 20px">请输入游戏名称：</label>
            <input class="form-control" type="text" name="game_name">

            <label style="padding-top: 20px">请选择游戏类型：</label>
            <select name="game_type">
                <?php if(is_array($game_type) || $game_type instanceof \think\Collection || $game_type instanceof \think\Paginator): $i = 0; $__LIST__ = $game_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g_type): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $g_type['id']; ?>"><?php echo $g_type['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择终端类型：</label>
            <select name="terminal_type">
                <?php if(is_array($terminal_type) || $terminal_type instanceof \think\Collection || $terminal_type instanceof \think\Paginator): $i = 0; $__LIST__ = $terminal_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$term): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $term['id']; ?>"><?php echo $term['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />
            <button id="game_btn" type="button" class="btn btn-primary" style="margin-top: 20px">添加</button>

        </form>

        <button id="upload" class="btn btn-info" style="margin-top: 20px">文件上传</button>
    </div>

</body>
    <script>
        (function () {
            $('button').click(function () {
                var data,url;
                var val =   $(this).parents('form').find('input[type="text"]').val();
                if (val == ''){
                    tips.error("请输入数据(～￣▽￣)～");
                }else{
                    if ($(this).attr('id')=='tester_btn'){
                        data = $('#tester').serialize();
                        console.log(data);
                        url = "<?php echo url('demo/hello/addtester'); ?>";
                        console.log(url);

                    }else if($(this).attr('id')=='upload'){
                        window.location.assign("<?php echo url('demo/hello/upload'); ?>");
                    }else {
                        data = $('#game').serialize();
                        console.log(data);
                        url = "<?php echo url('demo/hello/addgame'); ?>";
                        console.log(url);
                    }
                    $.ajax({
                        type:"POST",
                        data:data,
                        url:url,
                        dataType:"json",
                        success:function (data) {
                            console.log(data.sta);
                            if (data.sta ==1){
                                tips.success("提交成功");
                                setTimeout(function () {
                                    window.location.reload();
                                },3000)
                            }
                        }
                    })

                }


            });
        })()
    </script>
</html>