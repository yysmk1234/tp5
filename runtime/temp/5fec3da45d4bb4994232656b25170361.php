<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\datachoose.html";i:1487818683;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数据筛选</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
    <script src="../../../../public/static/cookie/jquery.cookie.js"></script>
</head>
<body>
    <div class="container" style="padding-top: 50px">
        <form method="post" action="<?php echo url('demo/hello/datachoose'); ?>">
            <label>选择筛选条件</label>
            <br />
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

            <label style="padding-top: 20px">请选择测试顺序：</label>
            <select name="status">
                <?php if(is_array($status) || $status instanceof \think\Collection || $status instanceof \think\Paginator): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$statu): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $statu['status_']; ?>"><?php echo $statu['status_']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />
            <button class="btn btn-info">筛选</button>
        </form>
        <table class="table table-condensed" style="margin-top: 20px">
            <tr>
                <td>#</td>
                <td>emoi</td>
                <td>scl</td>
                <td>High alpha</td>
                <td>gamma</td>
                <td>tag</td>
            </tr>

        </table>
    </div>

</body>
</html>