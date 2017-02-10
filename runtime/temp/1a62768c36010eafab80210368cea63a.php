<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\upload.html";i:1486549855;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
</head>
<body>
    <div class="container">
        <form style="padding: 50px 20px">
            <label style="padding-top: 20px">请选择性别：</label>
            <select name="sex">
                <?php if(is_array($sex) || $sex instanceof \think\Collection || $sex instanceof \think\Paginator): $i = 0; $__LIST__ = $sex;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择年龄：</label>
            <select name="age_group">
                <?php if(is_array($age_group) || $age_group instanceof \think\Collection || $age_group instanceof \think\Paginator): $i = 0; $__LIST__ = $age_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />


            <label style="padding-top: 20px">请选择游戏类型：</label>
            <select name="game_type">
                <?php if(is_array($game_type) || $game_type instanceof \think\Collection || $game_type instanceof \think\Paginator): $i = 0; $__LIST__ = $game_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择终端类型：</label>
            <select name="terminal_type">
                <?php if(is_array($terminal_type) || $terminal_type instanceof \think\Collection || $terminal_type instanceof \think\Paginator): $i = 0; $__LIST__ = $terminal_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />


            <label style="padding-top: 20px">请选择游戏经历：</label>
            <select name="game_experience">
                <?php if(is_array($game_experience) || $game_experience instanceof \think\Collection || $game_experience instanceof \think\Paginator): $i = 0; $__LIST__ = $game_experience;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择游戏年限：</label>
            <select name="game_year">
                <?php if(is_array($game_year) || $game_year instanceof \think\Collection || $game_year instanceof \think\Paginator): $i = 0; $__LIST__ = $game_year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $name['id']; ?>"><?php echo $name['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <button type="button">提交</button>
        </form>

    </div>

</body>
    <script>
        (function () {
            $('button').click(function () {
                var data = $('form').serialize();
                console.log(data);
            })
        })()
    </script>
</html>